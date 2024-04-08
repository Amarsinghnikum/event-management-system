@extends('layouts.app')

@section('title', 'Event List')

@section('content')
    <h2>Event List</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('events.create') }}" class="btn btn-primary">Create Event</a>

    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Organizer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->eventName }}</td>
                    <td>{{ $event->startDate }}</td>
                    <td>{{ $event->endDate }}</td>
                    <td>{{ $event->organizer }}</td>
                    <td>
                        <a href="{{ route('events.edit', $event->id) }}">Edit</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
