@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <h2>Edit Event</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form  id="updateEventForm" data-event-id="{{ $event->id }}">
        @csrf
        @method('PUT')
        <div>
            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" value="{{ old('eventName', $event->eventName) }}">
        </div>
        <div>
            <label for="eventDescription">Event Description:</label>
            <textarea id="eventDescription" name="eventDescription">{{ old('eventDescription', $event->eventDescription) }}</textarea>
        </div>
        <div>
            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="{{ old('startDate', $event->startDate) }}">
        </div>
        <div>
            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" value="{{ old('endDate', $event->endDate) }}">
        </div>
        <div>
            <label for="organizer">Organizer:</label>
            <input type="text" id="organizer" name="organizer" value="{{ old('organizer', $event->organizer) }}">
        </div>
        <button type="submit">Update Event</button>
    </form>
@endsection
