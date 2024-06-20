<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

class DashboardPosts extends Component
{
    public $posts;

    public function mount()
    {
        // Obtener los posts de los amigos del usuario actual
        $this->posts = Auth::user()->friendsPosts;
    }

    public function render()
    {
        return view('livewire.dashboard-posts', [
            'posts' => $this->posts,
        ]);
    }
}