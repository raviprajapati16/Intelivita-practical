@extends('layouts.app')

@section('content')
<h1 class="mb-4">Leaderboard</h1>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
    .tr-bg-color{
        background-color:antiquewhite;
    }
</style>
<form action="{{ route('leaderboard.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="user_id" value="{{ $user_id }}" class="form-control" placeholder="Search by User ID">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </div>
</form>

<div class="d-flex align-items-center mb-3">
    <div class="me-2 mr-3">
        <select id="filter" class="form-control">
            <option value="">Select Filter</option>
            <option value="day">Today</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
    </div>
    <!-- <form action="{{ route('leaderboard.index') }}" method="POST" class="mb-0 mr-3">
        @csrf
        <button class="btn btn-warning" type="submit">Re-calculate Ranks</button> -->
    <!-- </form> -->
    <a href="{{ route('leaderboard.index') }}" class="btn btn-warning mr-3   ">Re-calculate Ranks</a>
    <a href="{{ route('leaderboard.index') }}" class="btn btn-danger">Clear Filter</a>
</div>

<table class="table table-bordered" id="leaderboard">
    <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Points</th>
            <th>Rank</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leaderboard as $row)
        @php
        $bg_color = ($row->id == $user_id) ? 'tr-bg-color' :'';
        @endphp
        <tr class="{{ $bg_color }}">
            <td>{{ $row->id }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->points }}</td>
            <td>{{ $row->rank }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $leaderboard->links() }}
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function loadLeaderboard(filter = '') {
                $.ajax({
                url: '{{ route("leaderboard.filter") }}',
                type: 'GET',
                data: {
                    filter: filter
                },
                success: function(data) {
                    let leaderboardHtml = '';
                    console.log(data.length)
                    if (data && data.length > 0) {
                        data.forEach(function(item) {
                            leaderboardHtml += `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.points}</td>
                        <td>${item.rank}</td>
                    </tr>
                `;
                        });
                    } else {
                        leaderboardHtml = '<tr><td colspan="4">No data available</td></tr>';
                    }
                    $('#leaderboard tbody').html(leaderboardHtml);
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseJSON.error);
                    console.log('An error occurred: ' + xhr.responseJSON.error);
                }
            });
        }

        // Initial load
        // loadLeaderboard();

        $('#filter').change(function() {
            const filterValue = $(this).val();
            alert('ok');
            loadLeaderboard(filterValue);
        });
    });
</script>

@endsection