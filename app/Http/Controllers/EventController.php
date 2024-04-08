<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index',compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validatedEventData = $request->validate([
            'eventName' => 'required|string',
            'eventDescription' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'organizer' => 'required|string',
        ]);

        $event = Event::create($validatedEventData);

        // if ($request->filled('ticketName')) {
        //     $ticketNames = $request->input('ticketName');
        //     $ticketDescriptions = $request->input('ticketDescription', []);
        //     $ticketPrices = $request->input('ticketPrice');

        //     foreach ($ticketNames as $index => $ticketName) {
        //         $validatedTicketData = Validator::make([
        //             'ticketName' => $ticketName,
        //             'ticketDescription' => $ticketDescriptions[$index] ?? null,
        //             'ticketPrice' => $ticketPrices[$index],
        //         ], [
        //             'ticketName' => 'required|string',
        //             'ticketDescription' => 'nullable|string',
        //             'ticketPrice' => 'required|numeric|min:0',
        //         ])->validated();

        //         $ticket = new Ticket($validatedTicketData);
        //         $event->tickets()->save($ticket);
        //     }
        // }

        return response()->json(['message' => 'Event created successfully', 'event' => $event]);
    }

    public function ticketStore(Request $request)
    {
        try {
            if ($request->filled('ticketNumber')) {
                $ticketIds = $request->input('ticketId');
                $ticketNumbers = $request->input('ticketNumber');
                $ticketPrices = $request->input('price');

                $tickets = [];

                Log::debug('Ticket IDs: ' . print_r($ticketIds, true));
                Log::debug('Ticket Numbers: ' . print_r($ticketNumbers, true));
                Log::debug('Ticket Prices: ' . print_r($ticketPrices, true));

                foreach ($ticketNumbers as $index => $ticketNumber) {
                    if (isset($ticketIds[$index]) && isset($ticketPrices[$index])) {
                        $validatedTicketData = Validator::make([
                            'ticketId' => $ticketIds[$index],
                            'ticketNumber' => $ticketNumber,
                            'price' => $ticketPrices[$index],
                        ], [
                            'ticketId' => 'required|string',
                            'ticketNumber' => 'required|string',
                            'price' => 'required|numeric|min:0',
                        ])->validated();

                        $ticket = Ticket::create($validatedTicketData);
                        $tickets[] = $ticket;
                    } else {
                        Log::error('Ticket data is incomplete at index ' . $index);
                    }
                }

                return response()->json(['message' => 'Tickets saved successfully', 'tickets' => $tickets], 200);
            } else {
                return response()->json(['error' => 'No ticket data provided'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Failed to save tickets: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save tickets'], 500);
        }
    }
    
    public function updateTicket(Request $request)
    {
        try {
            $ticketId = $request->input('ticketId')[0];
            $ticketNumber = $request->input('ticketNumber')[0];
            $price = $request->input('price')[0];
    
            $ticket = Ticket::find($ticketId);
            // dd($ticket);
            if ($ticket) {
                $ticket->update([
                    'ticketNumber' => $ticketNumber,
                    'price' => $price,
                ]);
    
                Log::debug('$ticketId:', [$ticketId]);
                Log::debug('$ticket:', [$ticket]);
    
                return response()->json(['message' => 'Ticket updated successfully', 'ticket' => $ticket], 200);
            } else {
                return response()->json(['error' => 'Ticket not found'], 404);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to update ticket: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update ticket'], 500);
        }    
    }
    
    public function delete($id)
    {
        try {
            $ticket = Ticket::find($id);
            if (!$ticket) {
                return response()->json(['error' => 'Ticket not found'], 404);
            }
            $ticket->delete();
            
            return response()->json(['message' => 'Ticket deleted successfully'], 200);
        } catch (\Exception $e) {
            \Log::error('Failed to delete ticket: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete ticket'], 500);
        }
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'eventName' => 'required|string',
            'eventDescription' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'organizer' => 'required|string',
        ]);

        $event->update($validatedData);

        return response()->json(['message' => 'Event updated successfully', 'event' => $event]);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully');
    }
}
