<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Message;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Inbox extends Component
{
    public $sentMessages = [];
    public $receivedMessages = [];
    public $pendingRequests = [];
    public $replyToMessageId = null;
    public $replyMessage = '';

    public function mount()
    {
        $this->loadMessages();
        $this->loadPendingRequests();
    }

    public function loadMessages()
    {
        $user = Auth::user();
        

        // Upload received messages and their senders
        $this->receivedMessages = Message::where('recipient_id', $user->id)
                                ->with('sender')
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Upload sent messages and their recipients
        $this->sentMessages = Message::where('sender_id', $user->id)
                                ->with('recipient')
                                ->orderBy('created_at', 'desc')
                                ->get();

        //Upload pending frienship requests                       
        $this->pendingRequests = User::whereHas('friends', function ($query) use ($user) {
                                $query->where('status', 'pending')
                                ->where('friend_id', $user->id);
                                })
                                ->get();
    }

    public function toggleReplyForm($messageId)
    {
        $this->replyToMessageId = $this->replyToMessageId === $messageId ? null : $messageId;
    }

    public function replyToMessage($messageId)
    {
        $message = Message::find($messageId);
        $reply = new Message();
        $reply->sender_id = Auth::id();
        $reply->recipient_id = $message->sender_id;
        $reply->message = $this->replyMessage;
        $reply->save();

        $this->replyMessage = '';
        $this->replyToMessageId = null;
        $this->loadMessages();
    }

    public function loadPendingRequests()
    {
        $userId = Auth::id();

        //Query to upload pending friend requests from the authenticated user
        $this->pendingRequests = Friend::where('friend_id', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
    }

    public function acceptFriendRequest($requestId)
    {
        
        $friendRequest = Friend::findOrFail($requestId);
        $friendRequest->status = 'accepted';
        $friendRequest->save();

    
        $this->loadPendingRequests();
    }

    public function declineFriendRequest($requestId)
    {
        $request = Friend::find($requestId);
        $request->status = 'declined';
        $request->save();
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.inbox', [
            'sentMessages' => $this->sentMessages,
            'receivedMessages' => $this->receivedMessages,
            'pendingRequests' => $this->pendingRequests,
        ]);
    }
}

