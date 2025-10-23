<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingChat;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class GuestChatController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    /**
     * Send a chat message as guest
     */
    public function send(Request $request, Booking $booking): RedirectResponse
    {
        // Verify booking status
        if (!in_array($booking->status, ['assigned', 'in_progress'])) {
            return back()->with('error', 'Chatt är endast tillgänglig för aktiva bokningar.');
        }

        // Verify company is assigned
        if (!$booking->company) {
            return back()->with('error', 'Ingen företag tilldelad än.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'message.required' => 'Meddelande kan inte vara tomt.',
            'message.max' => 'Meddelandet får inte vara längre än 1000 tecken.',
        ]);

        $chat = BookingChat::create([
            'booking_id' => $booking->id,
            'sender_type' => 'guest',
            'sender_name' => $booking->customer_name,
            'message' => $validated['message'],
        ]);

        // Send notification to company
        $this->notificationService->notifyNewChatMessage($chat, $booking);

        return back()->with('success', 'Meddelande skickat!')->with('scroll_to', 'chat');
    }
}
