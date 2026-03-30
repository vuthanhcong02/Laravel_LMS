<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Services\SupportService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    protected SupportService $supportService;

    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $tickets = $this->supportService->getAllTickets(15, $status);
        return view('portal.admin.support.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('messages.user', 'user');
        return view('portal.admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
        ]);

        $message = $this->supportService->replyToTicket($ticket, $request->all());

        if ($request->ajax()) {
            $message->load('user');
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi phản hồi cho người dùng.',
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

        return redirect()->back()->with('success', 'Đã gửi phản hồi cho người dùng.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        $this->supportService->updateStatus($ticket, $validated['status']);
        return redirect()->back()->with('success', 'Trạng thái yêu cầu hỗ trợ đã được cập nhật.');
    }
}
