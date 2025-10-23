<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingChat;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ChatController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    public function show(Booking $booking): View
    {
        // Verify this booking belongs to the company
        if ($booking->company_id !== auth()->user()->company?->id) {
            abort(403, 'Du har inte tillgång till denna bokning');
        }
        
        // Check if chat is allowed
        if (!in_array($booking->status, ['assigned', 'in_progress', 'completed'])) {
            abort(403, 'Chat är inte tillgänglig för denna bokning');
        }
        
        $chats = $booking->chats()->orderBy('created_at', 'asc')->get();
        $canSendMessages = in_array($booking->status, ['assigned', 'in_progress']);
        
        // Mark messages as read
        $booking->chats()
            ->where('sender_type', '!=', 'company')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return view('company.bookings.chat', compact('booking', 'chats', 'canSendMessages'));
    }
    
    public function send(Request $request, Booking $booking): RedirectResponse
    {
        // Verify this booking belongs to the company
        if ($booking->company_id !== auth()->user()->company?->id) {
            abort(403, 'Unauthorized');
        }
        
        // Check if chat is allowed
        if (!in_array($booking->status, ['assigned', 'in_progress'])) {
            return back()->with('error', 'Chatt är inte tillgänglig för denna bokning.');
        }
        
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ], [
            'message.required' => 'Meddelande kan inte vara tomt.',
            'message.max' => 'Meddelandet får inte vara längre än 1000 tecken.',
        ]);
        
        $chat = BookingChat::create([
            'booking_id' => $booking->id,
            'sender_type' => 'company',
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        
        // Send notification to user
        $this->notificationService->notifyNewChatMessage($chat, $booking);
        
        return back()->with('success', 'Meddelande skickat!')->with('scroll_to', 'chat');
    }
    
    public function store(Request $request, Booking $booking): JsonResponse
    {
        // Verify this booking belongs to the company
        if ($booking->company_id !== auth()->user()->company?->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Check if chat is allowed
        if (!in_array($booking->status, ['assigned', 'in_progress'])) {
            return response()->json(['error' => 'Chat är inte tillgänglig'], 403);
        }
        
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $chat = BookingChat::create([
            'booking_id' => $booking->id,
            'sender_type' => 'company',
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);
        
        // Send notification to user
        $this->notificationService->notifyNewChatMessage($chat, $booking);
        
        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'sender_type' => $chat->sender_type,
                'sender_name' => auth()->user()->name,
                'message' => $chat->message,
                'created_at' => $chat->created_at->format('Y-m-d H:i'),
                'time_ago' => $chat->created_at->diffForHumans(),
            ],
        ]);
    }
    
    public function fetch(Booking $booking): JsonResponse
    {
        // Verify this booking belongs to the company
        if ($booking->company_id !== auth()->user()->company?->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $chats = $booking->chats()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'sender_type' => $chat->sender_type,
                    'sender_name' => $chat->sender_name ?? ($chat->sender?->name ?? 'Kund'),
                    'message' => $chat->message,
                    'created_at' => $chat->created_at->format('Y-m-d H:i'),
                    'time_ago' => $chat->created_at->diffForHumans(),
                    'is_read' => $chat->is_read,
                ];
            });
        
        // Mark unread messages as read
        $booking->chats()
            ->where('sender_type', '!=', 'company')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['chats' => $chats]);
    }
}
