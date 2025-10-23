<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingChat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class BookingChatController extends Controller
{
    public function show(string $bookingNumber): View
    {
        $booking = Booking::where('booking_number', $bookingNumber)->firstOrFail();
        
        // Check if chat is allowed
        if (!in_array($booking->status, ['assigned', 'in_progress', 'completed'])) {
            abort(403, 'Chat är inte tillgänglig för denna bokning');
        }
        
        $chats = $booking->chats()->orderBy('created_at', 'asc')->get();
        $canSendMessages = in_array($booking->status, ['assigned', 'in_progress']);
        
        // Mark company messages as read
        $booking->chats()
            ->where('sender_type', 'company')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return view('public.booking-chat', compact('booking', 'chats', 'canSendMessages'));
    }
    
    public function store(Request $request, string $bookingNumber): JsonResponse
    {
        $booking = Booking::where('booking_number', $bookingNumber)->firstOrFail();
        
        // Check if chat is allowed
        if (!in_array($booking->status, ['assigned', 'in_progress'])) {
            return response()->json(['error' => 'Chat är inte tillgänglig'], 403);
        }
        
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'sender_name' => 'nullable|string|max:255', // For guests
        ]);
        
        $isGuest = !auth()->check();
        
        $chat = BookingChat::create([
            'booking_id' => $booking->id,
            'sender_type' => $isGuest ? 'guest' : 'user',
            'sender_id' => auth()->id(),
            'sender_name' => $isGuest ? ($validated['sender_name'] ?? $booking->customer_name) : null,
            'message' => $validated['message'],
        ]);
        
        $senderName = $isGuest 
            ? ($chat->sender_name ?? 'Gäst') 
            : auth()->user()->name;
        
        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'sender_type' => $chat->sender_type,
                'sender_name' => $senderName,
                'message' => $chat->message,
                'created_at' => $chat->created_at->format('Y-m-d H:i'),
                'time_ago' => $chat->created_at->diffForHumans(),
            ],
        ]);
    }
    
    public function fetch(string $bookingNumber): JsonResponse
    {
        $booking = Booking::where('booking_number', $bookingNumber)->firstOrFail();
        
        $chats = $booking->chats()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                $senderName = 'Kund';
                if ($chat->sender_type === 'company') {
                    $senderName = $chat->sender?->company?->company_name ?? 'Företag';
                } elseif ($chat->sender_type === 'guest') {
                    $senderName = $chat->sender_name ?? 'Gäst';
                } elseif ($chat->sender) {
                    $senderName = $chat->sender->name;
                }
                
                return [
                    'id' => $chat->id,
                    'sender_type' => $chat->sender_type,
                    'sender_name' => $senderName,
                    'message' => $chat->message,
                    'created_at' => $chat->created_at->format('Y-m-d H:i'),
                    'time_ago' => $chat->created_at->diffForHumans(),
                    'is_read' => $chat->is_read,
                ];
            });
        
        // Mark company messages as read
        $booking->chats()
            ->where('sender_type', 'company')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['chats' => $chats]);
    }
}

