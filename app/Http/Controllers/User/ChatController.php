<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingChat;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ChatController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    /**
     * Send a chat message
     */
    public function send(Request $request, Booking $booking): RedirectResponse
    {
        // Verify booking belongs to user or is assigned to user
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Verify booking is in correct status
        if (!in_array($booking->status, ['assigned', 'in_progress'])) {
            return back()->with('error', 'Chatt är endast tillgänglig för aktiva bokningar.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'message.required' => 'Meddelande kan inte vara tomt.',
            'message.max' => 'Meddelandet får inte vara längre än 1000 tecken.',
        ]);

        $chat = BookingChat::create([
            'booking_id' => $booking->id,
            'sender_type' => 'user',
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        // Send notification to company
        if ($booking->company) {
            $this->notificationService->notifyNewChatMessage($chat, $booking);
        }

        return back()->with('success', 'Meddelande skickat!')->with('scroll_to', 'chat');
    }
}
