<?php

namespace App\Services;

use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupportService
{
    /**
     * Get tickets for the currently authenticated user.
     */
    public function getUserTickets($perPage = 10)
    {
        return SupportTicket::with('messages')
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all tickets (For Admin).
     */
    public function getAllTickets($perPage = 15, $status = null)
    {
        $query = SupportTicket::with(['user', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->orderBy('updated_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new support ticket alongside the first message.
     */
    public function createTicket(array $data)
    {
        try {
            DB::beginTransaction();

            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'subject' => $data['subject'],
                'priority' => $data['priority'] ?? 'normal',
                'status' => 'open',
            ]);

            $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $data['message'],
                'attachment_path' => $data['attachment_path'] ?? null,
            ]);

            DB::commit();
            return $ticket;
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Có lỗi xảy ra khi tạo ticket: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            throw $e;
        }
    }

    /**
     * Reply to an existing ticket.
     */
    public function replyToTicket(SupportTicket $ticket, array $data)
    {
        try {
            DB::beginTransaction();

            $attachmentPath = $data['attachment_path'] ?? null;
            
            // Handle file upload if present and is an actual file object
            if (isset($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
                $attachmentPath = $data['attachment']->store('support_attachments', 'public');
            }

            $message = $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $data['message'],
                'attachment_path' => $attachmentPath,
            ]);

            // Update ticket's updated_at timestamp so it bumps to the top and set status back to open/in_progress if closed
            $ticket->touch();
            if ($ticket->status === 'closed' || $ticket->status === 'resolved') {
                $ticket->update(['status' => 'in_progress']);
            }

            DB::commit();
            return $message;
        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Có lỗi xảy ra khi reply ticket: ' . $e->getMessage(), ['ticket_id' => $ticket->id]);
            throw $e;
        }
    }

    /**
     * Change ticket status (For Admin).
     */
    public function updateStatus(SupportTicket $ticket, string $status)
    {
        $ticket->update(['status' => $status]);
        return $ticket;
    }
}
