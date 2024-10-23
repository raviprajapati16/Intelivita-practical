<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $user_id = null;
        if ($request->user_id) {
            $user_id = $request->user_id;
        }
        $leaderboard = $this->getLeaderboardData($request);

        return view('leaderboard.index', compact('leaderboard', 'user_id'));
    }


    public function search(Request $request)
    {
        $user = Leaderboard::find($request->user_id);
        return response()->json($user);
    }

    public function filter(Request $request)
    {
        try {
            $query = Leaderboard::query();

            // Apply filters
            if ($request->filled('filter')) {
                $filter = $request->input('filter');
                if ($filter == 'day') {
                    $query->whereDate('datetime', today());
                } elseif ($filter == 'month') {
                    $query->whereMonth('datetime', now()->month);
                } elseif ($filter == 'year') {
                    $query->whereYear('datetime', now()->year);
                }
            }

            // Execute the filtered query
            $filteredLeaderboard = $query->get();

            // Calculate ranks using a separate query
            $rankedLeaderboard = DB::table(DB::raw("(
                SELECT *,
                       DENSE_RANK() OVER (ORDER BY points DESC) AS rank
                FROM (SELECT * FROM leaderboard WHERE id IN (" . implode(',', $filteredLeaderboard->pluck('id')->toArray()) . ")) AS subquery
            ) AS ranked"))
                ->get();


            return response()->json($rankedLeaderboard);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }





    public function getLeaderboardData($request)
    {
        // Get the filtered leaderboard data
        $query = Leaderboard::query();

        if ($request->filled('filter')) {
            $filter = $request->input('filter');
            if ($filter == 'day') {
                $query->whereDate('datetime', today());
            } elseif ($filter == 'month') {
                $query->whereMonth('datetime', now()->month);
            } elseif ($filter == 'year') {
                $query->whereYear('datetime', now()->year);
            }
        }

        $leaderboard = DB::table(DB::raw("(
            SELECT *,
                   DENSE_RANK() OVER (ORDER BY points DESC) AS rank
            FROM leaderboard
        ) AS ranked"))
            ->mergeBindings($query->toBase()) // Merge the bindings from the original query
            ->paginate(20);

        return $leaderboard;
    }
}
