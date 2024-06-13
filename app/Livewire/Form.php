<?php


namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;

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
            if(!empty($this->password))
            {
                $this->validate();
                $form = User::findOrFail($this->formId);
                $form->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'singer' => $this->singer,
                    'hobby' => $this->hobby,
                    'password' => Hash::make($this->password), 
                ]);
            }
            else{
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

    public function render()
    {
        return view('livewire.form')->layout('components.layouts.app');
    }
}
