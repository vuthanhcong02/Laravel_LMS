<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Contact::query()->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'replied', 'closed'])) {
            $query->where('status', $status);
        }

        $contacts = $query->paginate(15);
        
        return view('portal.admin.contacts.index', compact('contacts', 'status'));
    }

    public function show(Contact $contact)
    {
        return view('portal.admin.contacts.show', compact('contact'));
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,replied,closed'
        ]);

        $contact->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Trạng thái liên hệ đã được cập nhật.');
    }
}
