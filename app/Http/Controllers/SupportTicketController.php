<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\SupportTicket;
use App\Mail\TicketCreatedMail;

class SupportTicketController extends Controller
{
    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'category' => 'required',
            'priority' => 'required',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'category' => $request->category,
            'priority' => $request->priority,
        ]);

        // Email alert to admin
        Mail::to(config('mail.support_email'))
            ->send(new TicketCreatedMail($ticket));

        return redirect()->back()->with('success', 'Your support ticket has been submitted.');
    }
}

