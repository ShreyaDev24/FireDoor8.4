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
            'attachment' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            $attachmentPath = time() . '_' . $file->getClientOriginalName();

            $filepath = public_path('ticket_attachments');

            // create folder if not exists
            if (!file_exists($filepath)) {
                mkdir($filepath, 0777, true);
            }

            $file->move($filepath, $attachmentPath);
        }

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'category' => $request->category,
            'priority' => $request->priority,
            'attachment' => $attachmentPath,
        ]);

        // Email alert to admin
        Mail::to(config('mail.support_email'))
            ->send(new TicketCreatedMail($ticket));

        return redirect()->back()->with([
            'success' => 'Thank you for contacting us! We will check your request and get back to you shortly.',
            'ticket_id' => $ticket->id
        ]);
    
    }
}

