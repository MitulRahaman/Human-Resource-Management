<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Requests\TicketAddRequest;
use App\Http\Requests\TicketEditRequest;
use App\Services\TicketService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class TicketController extends Controller
{
    private $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        View::share('main_menu', 'Tickets');
    }

    public function index()
    {
        View::share('sub_menu', 'Manage Tickets');
        return view('backend.pages.ticket.index');
    }

    public function fetchData()
    {
        return $this->ticketService->fetchData();
    }

    public function create()
    {
        View::share('sub_menu', 'Add Ticket');
        $priority = Config::get('variable_constants.ticket_priority');
        $users = $this->ticketService->getUsers();
        return \view('backend.pages.ticket.create', compact('priority','users'));
    }

    public function store(TicketAddRequest $request)
    {
        try {
            $ticket = $this->ticketService->create($request->validated());
            if(!$ticket)
                return redirect('ticket')->with('error', 'Failed to add ticket');
            return redirect('/ticket')->with('success', 'Ticket added successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Tickets');
        $ticket = $this->ticketService->getTicket($id);
        if($ticket && !is_null($ticket->deleted_at))
            return redirect()->back()->with('error', 'Invalid Ticket');
        $users = $this->ticketService->getUsers();
        $priority = Config::get('variable_constants.ticket_priority');
        return \view('backend.pages.ticket.edit',compact('ticket','users','priority'));
    }

    public function update(TicketEditRequest $request)
    {
        try {
            $ticket = $this->ticketService->update($request->validated());
            if(!$ticket)
                return redirect('ticket')->with('error', 'Failed to update ticket');
            return redirect('/ticket')->with('success', 'ticket updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function close($id)
    {
        try {
            $this->ticketService->close($id);
            return redirect()->back()->with('success', 'Ticket closed');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function hold($id)
    {
        try {
            $this->ticketService->hold($id);
            return redirect()->back()->with('success', 'Ticket on hold');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function complete($id, Request $request)
    {
        try {
            $this->ticketService->complete($id, $request->all());
            return redirect()->back()->with('success', 'Ticket completed');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
