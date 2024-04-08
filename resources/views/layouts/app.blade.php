<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Event Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- <b>IT LABS TEST</b> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item {{ request()->is('events*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('events.index') }}">Events</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session('status'))
    <script>
    swal("{{ session('status') }}");
    </script>
    @endif

    <script>
        $('#eventForm').submit(function(event) {
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("events.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    swal({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        button: 'OK',
                    }).then(() => {
                        window.location.replace('{{ route("events.index") }}');
                    });
                },
                error: function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON.errors) {
                    $('.alert-danger').remove();
                    $.each(error.responseJSON.errors, function(field, messages) {
                        var inputField = $('#' + field);
                        inputField.after('<div class="alert alert-danger">' + messages.join('<br>') + '</div>');
                    });
                } else {
                    alert('Error creating Event. Please check the console for details.');
                }
            }
            });
        });

        $('#updateEventForm').submit(function(event) {
            event.preventDefault();
            var eventId = $(this).data('event-id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/events/' + eventId,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    swal({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        button: 'OK',
                    }).then(() => {
                        window.location.replace('{{ route("events.index") }}');
                    });
                },
                error: function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON.errors) {
                    $('.alert-danger').remove();
                    $.each(error.responseJSON.errors, function(field, messages) {
                        var inputField = $('#' + field);
                        inputField.after('<div class="alert alert-danger">' + messages.join('<br>') + '</div>');
                    });
                } else {
                    alert('Error Updating Event. Please check the console for details.');
                }
            }
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $('#addTicketBtn').click(function() {
            var ticketFieldsHtml = `
                <div class="row mt-3 ticket-inputs">
                    <div class="col-md-3">
                        <input type="text" class="form-control ticketId" name="ticketId[]" placeholder="ID">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control ticketNumber" name="ticketNumber[]" placeholder="Ticket Number">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control ticketPrice" name="price[]" placeholder="Price">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success saveTicketBtn">Save</button>
                    </div>
                </div>
            `;
            $('#ticketFieldsContainer').append(ticketFieldsHtml);
        });

        $(document).on('click', '.editTicketBtn', function() {
            $(this).text('Save');

            $(this).removeClass('btn-primary editTicketBtn').addClass('btn-success saveTicketBtn');

            $(this).closest('.ticket-details').find('input').prop('disabled', false);
        });

        $(document).on('click', '.saveTicketBtn', function() {
            var ticketId = $(this).closest('.ticket-inputs').find('.ticketId').val();
            var ticketNumber = $(this).closest('.ticket-inputs').find('.ticketNumber').val();
            var ticketPrice = $(this).closest('.ticket-inputs').find('.ticketPrice').val();

            var data = {
                'ticketId[]': ticketId,
                'ticketNumber[]': ticketNumber,
                'price[]': ticketPrice
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("tickets.store") }}',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Ticket saved successfully:', response);

                    var ticketHtml = `
                        <div class="row mt-3 ticket-details" data-ticket-id="${ticketId}">
                            <div class="col-md-3">
                                <input type="text" class="form-control ticketId" value="${ticketId}" disabled>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control ticketNumber" value="${ticketNumber}" disabled>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control ticketPrice" value="${ticketPrice}" disabled>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary editTicketBtn">Edit</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger deleteTicketBtn">Delete</button>
                            </div>
                        </div>
                    `;
                    $('#ticketListContainer').append(ticketHtml);

                    $('.ticket-inputs').remove();
                },
                error: function(xhr, status, error) {
                    console.error('Error saving ticket:', error);
                }
            });
        });

        $(document).on('click', '.deleteTicketBtn', function() {
            $(this).closest('.ticket-details').remove();
        });
    });
</script>
<script>
$(document).on('click', '.saveTicketBtn', function() {
    var ticketDetailsContainer = $(this).closest('.ticket-details');
    var ticketId = ticketDetailsContainer.find('.ticketId').val();
    var ticketNumber = ticketDetailsContainer.find('.ticketNumber').val();
    var price = ticketDetailsContainer.find('.ticketPrice').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route("tickets.update") }}',
        type: 'POST',
        data: {
            'ticketId[]': ticketId,
            'ticketNumber[]': ticketNumber,
            'price[]': price
        },
        dataType: 'json',
        success: function(response) {
        },
        error: function(xhr, status, error) {
        }
    });

    ticketDetailsContainer.find('.form-control').prop('disabled', true);

    $(this).text('Edit').removeClass('btn-success saveTicketBtn').addClass('btn-primary editTicketBtn');
});

</script>
<script>
    $(document).on('click', '.deleteTicketBtn', function() {
        var ticketId = $(this).closest('.ticket-details').data('ticket-id');
        var btn = $(this);

        $.ajax({
            url: '/tickets/' + ticketId,
            type: 'DELETE',
            success: function(response) {
                console.log(ticketId);
                console.log('Ticket deleted successfully:', response);
                btn.closest('.ticket-details').remove();
            },
            error: function(xhr, status, error) {
                console.error('Error deleting ticket:', error);
            }
        });
    });
</script>

</body>
</html>