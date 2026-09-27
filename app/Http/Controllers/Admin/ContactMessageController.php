<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * List all contact messages / inquiries.
     */
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Update inquiry status.
     */
    public function updateStatus(Request $request, ContactMessage $message): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,resolved'],
        ]);

        $message->update(['status' => $data['status']]);

        return back()->with('success', 'Inquiry status updated to ' . ucfirst($data['status']));
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();
        return back()->with('success', 'Inquiry deleted successfully.');
    }
}
