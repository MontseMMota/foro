<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
class UsersEdit extends Component


{
    public $forms;
    public $formId;
    public $name;
    public $email;
    public $password;
    public $singer;
    public $hobby;



    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'singer' => 'required|string|max:255',
        'hobby' => 'required|string|max:255',
    ];


    public function mount($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
            $this->formId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->singer = $user->singer;
            $this->hobby = $user->hobby;
        } else {
            $this->resetForm();
        }

    }


    public function save()
    {
        $this->validate();


        if ($this->formId) {
            $form = User::findOrFail($this->formId);
            $form->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'singer' => $this->singer,
                'hobby' => $this->hobby,
            ]);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'singer' => $this->singer,
                'hobby' => $this->hobby,
            ]);
        }


        $this->resetForm();
        $this->forms = User::all();
        session()->flash('message', 'Datos guardados exitosamente.');
        return redirect()->route('form');
    }


    public function resetForm()
    {
        $this->formId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->singer = '';
        $this->hobby = '';
    }
    
    public function render()
    {
        return view('livewire.users-edit');
    }
}




