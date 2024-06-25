<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use App\Models\Post;

class Blog extends Component
{
    use WithFileUploads;

    public $posts;
    public $content;
    public $title;
    public $image; 
    public $editPostId;
    public $editingPostId;
    public $editedTitle;
    public $editedContent;

    public function mount()
    {
        $this->posts = Post::with('user')->get();
    }

    public function resetPostForm()
    {
        $this->title = '';
        $this->content = '';
        $this->image = null; 
    }

    public function createPost()
    {
        $this->validate([
            'title' => 'required|string|max:50',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024', 
        ]);

        $imageName = null;
        if ($this->image) {
            $imageName = $this->image->store('public/posts'); 
        }

        
        $post = new Post;
        $post->title = $this->title;
        $post->content = $this->content;
        $post->image = $imageName; 
        $post->user_id = Auth::id();
        $post->save();

        $this->resetPostForm(); 

        $this->posts = Post::with('user')->get();
        session()->flash('message', 'Post guardado exitosamente.');
    }

    public function render()
    {
        return view('livewire.blog');
    }
}