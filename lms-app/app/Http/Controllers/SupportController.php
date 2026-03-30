<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportTicketRequest;
use App\Http\Requests\ReplySupportTicketRequest;
use App\Models\SupportTicket;
use App\Services\SupportService;

class SupportController extends Controller
{
    protected SupportService $supportService;

    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    public function index()
    {
        $tickets = $this->supportService->getUserTickets();
        return view('portal.support.index', compact('tickets'));
    }

    public function create()
    {
        return view('portal.support.create');
    }

    public function store(StoreSupportTicketRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('support_attachments', 'public');
        }

        $this->supportService->createTicket($data);
        return redirect()->route('support.index')->with('success', 'Yêu cầu hỗ trợ đã được gửi thành công.');
    }

    public function show(SupportTicket $ticket)
    {
        // Must belong to user
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }
        $ticket->load('messages.user');
        return view('portal.support.show', compact('ticket'));
    }

    public function reply(ReplySupportTicketRequest $request, SupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $message = $this->supportService->replyToTicket($ticket, $request->all());

        if ($request->ajax()) {
            $message->load('user');
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi phản hồi.',
                'data' => [
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path ? asset('storage/' . $message->attachment_path) : null,
                    'created_at' => $message->created_at->format('H:i d/m/Y'),
                    'user' => [
                        'first_name' => $message->user->first_name,
                        'last_name' => $message->user->last_name,
                        'avatar' => $message->user->avatar_url,
                    ]
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Đã gửi phản hồi.');
    }
}
