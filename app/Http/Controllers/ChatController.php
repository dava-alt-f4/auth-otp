<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getUserChat(Request $request)
    {
        $token = $request->header('X-Guest-Token');
        $user = Auth::guard('sanctum')->user();

        $conversation = null;

        if ($user) {
            $conversation = Conversation::where('user_id', $user->id)->first();
        } elseif ($token) {
            $conversation = Conversation::where('guest_token', $token)->first();
        }

        if (! $conversation) {
            return response()->json(['messages' => []], 200);
        }

        // baca pesan
        $conversation->update(['unread_count_user' => 0]);

        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get(),
        ], 200);
    }

    public function notification(Request $request) {
        $token = $request->header('X-Guest-Token');
        $user = Auth::guard('sanctum')->user();

        $conversation = null;

        if ($user) {
            $conversation = Conversation::where('user_id', $user->id)->first();
        } else if ($token) {
            $conversation = Conversation::where('guest_token', $token)->first();
        }

        return response()->json([
            'unread' => $conversation ? $conversation->unread_count_user : 0
        ], 200);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $token = $request->header('X-Guest-Token');
        $user = Auth::guard('sanctum')->user();

        if (! $user && ! $token) {
            return response()->json(['message' => 'Akses ditolak. Token tidak ditemukan.'], 401);
        }

        // buat chat kalau ga ada
        $conversation = Conversation::firstOrCreate(
            $user ? ['user_id' => $user->id] : ['guest_token' => $token],
            [
                // Generate nama guest
                'guest_name' => $user ? $user->name : 'Guest-'.strtoupper(substr(uniqid(), -4)),
            ]
        );

        $message = $conversation->messages()->create([
            'message' => $request->message,
            'is_admin' => false,
        ]);

        $conversation->update([
            'last_message' => $request->message,
            'unread_count_admin' => $conversation->unread_count_admin + 1,
        ]);

        return response()->json($message, 201);
    }

    // Admin ambil inbox chat
    public function getInbox()
    {
        $conversations = Conversation::orderBy('updated_at', 'desc')->get();

        return response()->json($conversations, 200);
    }

    // Admin ambil riwayat chat
    public function getAdminMessages($id)
    {
        $conversation = Conversation::findOrFail($id);

        // Admin membaca pesan, reset bulatan merah notifikasi di dashboard admin
        $conversation->update(['unread_count_admin' => 0]);

        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages()->orderBy('created_at', 'asc')->get(),
        ], 200);
    }

    // Admin balas pesan
    public function replyMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $conversation = Conversation::findOrFail($id);

        $message = $conversation->messages()->create([
            'message' => $request->message,
            'is_admin' => true,
        ]);

        $conversation->update([
            'last_message' => $request->message,
            'unread_count_user' => $conversation->unread_count_user + 1,
        ]);

        return response()->json($message, 201);
    }
}
