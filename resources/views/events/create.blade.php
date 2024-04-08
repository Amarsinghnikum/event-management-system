@extends('layouts.app')

@section('title', 'Create Event')

@section('content')
    <h2>Create Event</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="eventForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="eventName">Event Name:</label>
                    <input type="text" class="form-control" id="eventName" name="eventName" value="{{ old('eventName') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="eventDescription">Event Description:</label>
                    <textarea class="form-control" id="eventDescription" name="eventDescription">{{ old('eventDescription') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" value="{{ old('startDate') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="date" class="form-control" id="endDate" name="endDate" value="{{ old('endDate') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="organizer">Organizer:</label>
                    <input type="text" class="form-control" id="organizer" name="organizer" value="{{ old('organizer') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button type="button" id="addTicketBtn" class="btn btn-success mt-3">Add New Ticket</button>
                </div>
            </div>
        </div>
        <div id="ticketFieldsContainer">

        </div>

        <div id="ticketListContainer">
        
        </div>
        
        <button type="submit" class="btn btn-primary">Save Event</button>
    </form>
@endsection