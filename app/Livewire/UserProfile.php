<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Interest;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

Carbon::setLocale('es');

class UserProfile extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    public $search = '';
    public $perPage = 14;
    public $user;

    protected $queryString = ['search'];
    public $selectedInterests = [];
    public $interests = [];
    public $visibleInterests = [];
    public $text = '';
    public $page = 1;
    public $maxInterests = 6;

    public $birth_date;
    public $editingBirthDate = false;
    public $formatted_birth_date;

    public $description;
    public $editingDescription = false;
    public $emojiList = ['😀', '😃', '😄', '😁', '😆', '😅', '🤣', '😂', '🙂', '🙃', '😉', '😊', '😇', '🥰', '😍', '🤩', '😘', '😗', '😚', '😙', '😋', '😛', '😜', '🤪', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓', '🧐', '😕', '😟', '🙁', '☹️', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾'];
    public $showEmojiList = false;
    public $avatar;
    public $showAvatarUploader = false;

    public function mount()
    {
        $this->user = auth()->user();
        if ($this->user) {
            $this->loadUserData();
        }
        $this->loadInterests();
        $this->description = $this->user->description ?? '';
        $this->formatted_birth_date = $this->user->birth_date ? Carbon::parse($this->user->birth_date)->isoFormat('DD [de] MMMM [de] YYYY') : '';
    }

    public function verifyAccount()
    {
        $this->user->update(['is_verified' => true]);
        session()->flash('message', 'Tu cuenta ha sido verificada correctamente.');
    }

    
    public function loadUserData()
    {
        $this->birth_date = $this->user->birth_date;
        $this->description = $this->user->description;
    }

    public function toggleEditingBirthDate()
    {
        $this->editingBirthDate = !$this->editingBirthDate;
    }

    public function saveBirthDate()
    {
        $this->validate([
            'birth_date' => ['required', 'date'],
        ]);

        $this->user->birth_date = $this->birth_date;
        $this->user->save();
        $this->formatted_birth_date = Carbon::parse($this->birth_date)->isoFormat('DD [de] MMMM [de] YYYY');
        $this->editingBirthDate = false;
    }

    public function saveDescription()
    {
        $this->validate([
            'description' => ['required', 'string', 'max:255'],
        ]);

        $this->user->description = $this->description;
        $this->user->save();
        $this->editingDescription = false;
    }

    public function toggleEditingDescription()
    {
        $this->editingDescription = !$this->editingDescription;
    }

    public function selectEmoji($emoji)
    {
        $this->description .= $emoji;
        $this->showEmojiList = false;
    }

    public function toggleEmojiList()
    {
        $this->showEmojiList = !$this->showEmojiList;
    }

    public function updatedAvatar()
    {
        $this->showAvatarUploader = true;
    }

    public function saveAvatar()
    {
        $this->validate([
            'avatar' => 'image|max:1024', // 1MB Max
        ]);

        $path = $this->avatar->store('avatars', 'public');
        $this->user->update(['avatar' => $path]);
        $this->user->save();

        $this->showAvatarUploader = false;

        session()->flash('message', 'Avatar updated successfully.');
    }

    public function addInterest($interestId)
    {
        if ($this->user->interests->count() >= $this->maxInterests) {
            session()->flash('error', 'No puedes añadir más de ' . $this->maxInterests . ' intereses.');
            return;
        }

        if ($this->user->interests->contains($interestId)) {
            session()->flash('error', 'Ya has añadido este interés.');
            return;
        }

        $interest = Interest::find($interestId);

        if ($interest) {
            $this->user->interests()->attach($interest);
            $this->user->refresh();
        }
    }

    public function removeInterest($interestId)
    {
        $interest = Interest::find($interestId);

        if ($interest) {
            $this->user->interests()->detach($interest);
            $this->user->refresh();
        }
    }

    public function updatingSearch()
    {
        $this->page = 1;
        $this->loadInterests();
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadInterests();
    }

    public function loadLess()
    {
        if ($this->page > 1) {
            $this->page--;
        }
        $this->loadInterests();
    }

    public function loadInterests()
    {
        $this->interests = Interest::where('name', 'like', '%' . $this->search . '%')->get();
        $this->visibleInterests = $this->interests->slice(0, $this->page * $this->perPage);
    }

    public function render()
    {
        $interests = Interest::where('name', 'like', '%' . $this->search . '%')->paginate($this->perPage);

        return view('livewire.user-profile', [
            'user' => $this->user,
            'interests' => $interests
        ]);
    }
}