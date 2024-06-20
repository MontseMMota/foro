<?php


namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use App\Models\Message;
use App\Models\Friend;

class Form extends Component
{
    public $forms;
    public $formId;
    public $name;
    public $email;
    public $singer;
    public $hobby;
    public $password;
    public $password_confirmation;
    public $user;
    
    public $recipientId;
    public $recipientName;
    public $message;
    public $showMessageFormFor = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'singer' => 'required|string|max:255',
        'hobby' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function mount()
    {
        $this->forms = User::all();
        $this->user = auth()->user();
    }

    public function edit($id)
    {
        $form = User::findOrFail($id);

        if (auth()->user()->role == 'admin' || auth()->user()->id == $form->id) {
            $this->formId = $form->id;
            $this->name = $form->name;
            $this->email = $form->email;
            $this->singer = $form->singer;
            $this->hobby = $form->hobby;
        }
    }

    public function save()
    {
        if ($this->formId) {
            if (!empty($this->password)) {
                $this->validate();
                $form = User::findOrFail($this->formId);
                $form->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'singer' => $this->singer,
                    'hobby' => $this->hobby,
                    'password' => Hash::make($this->password),
                ]);
            } else {
                $this->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email',
                    'singer' => 'required|string|max:255',
                    'hobby' => 'required|string|max:255',
                ]);
                $form = User::findOrFail($this->formId);
                $form->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'singer' => $this->singer,
                    'hobby' => $this->hobby,
                ]);
            }
        } else {
            $this->validate();
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'singer' => $this->singer,
                'hobby' => $this->hobby,
                'password' => Hash::make($this->password),
            ]);
        }

        $this->resetForm();
        $this->forms = User::all();
        session()->flash('message', 'Datos guardados exitosamente.');
    }

    public function resetForm()
    {
        $this->formId = null;
        $this->name = '';
        $this->email = '';
        $this->singer = '';
        $this->hobby = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function deleteUser($userId)
    {
        $userToDelete = User::find($userId);

        if ($userToDelete && (auth()->user()->role == 'admin' || auth()->user()->id == $userToDelete->id)) {
            $userToDelete->delete();
            $this->forms = User::all();
        }
    }

    // Métodos para el envío de mensajes
    public function startSendingMessage($recipientId)
    {
        $recipient = User::find($recipientId);

        // Verificar si el usuario actual está verificado
        if (auth()->user()->is_verified == true) {
            $this->recipientId = $recipientId;
            $this->showMessageFormFor = true;
        } else {
            // Mostrar mensaje de error o realizar alguna acción
            session()->flash('error', 'Solo los usuarios verificados pueden enviar mensajes.');
        }
    }

    public function cancelSendingMessage()
    {
        $this->reset(['recipientId', 'message', 'showMessageFormFor']);
    }

    public function sendMessage()
    {
        if ($this->user->is_verified) {
            // Verificar que el destinatario también esté verificado
            $recipient = User::find($this->recipientId);
            if ($recipient) {
                Message::create([
                    'sender_id' => $this->user->id,
                    'recipient_id' => $this->recipientId,
                    'message' => $this->message,
                ]);
                $this->reset(['recipientId', 'message']);
                session()->flash('message', 'Mensaje enviado correctamente.');
            } else {
                session()->flash('error', 'El destinatario no está verificado y no se puede enviar el mensaje.');
            }
        } else {
            session()->flash('error', 'Tu cuenta no está verificada. No puedes enviar mensajes.');
        }
    }





    public function addFriend($userId)
    {
        $existingFriend = Friend::where('user_id', auth()->user()->id)
                                ->where('friend_id', $userId)
                                ->first();
    
        if ($existingFriend) {
            // Verificar el estado actual de la amistad o solicitud de amistad
            if ($existingFriend->status == 'pending') {
                session()->flash('message', 'Ya has enviado una solicitud de amistad a este usuario.');
            } elseif ($existingFriend->status == 'accepted') {
                session()->flash('message', 'Ya eres amigo de este usuario.');
            } else {
                // Manejar otros estados si es necesario
            }
        } else {
            Friend::create([
                'user_id' => auth()->user()->id,
                'friend_id' => $userId,
                'status' => 'pending',
            ]);
    
            session()->flash('message', 'Solicitud de amistad enviada.');
        }
        
        // Recargar la lista de formularios para actualizar la vista
        $this->forms = User::all();
    }

    public function render()
    {
        $pendingRequests = Friend::where('friend_id', auth()->user()->id)
                                 ->where('status', 'pending')
                                 ->get();
    
        return view('livewire.form', [
            'pendingRequests' => $pendingRequests,
        ])->layout('components.layouts.app');
    }
}