<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->latest()->paginate(20);
        return view('Admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        return view('Admin.support.show', compact('ticket'));
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $ticket->update(['status' => $request->status]);
        return back()->with('success', 'Ticket status updated');
    }
}

