<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;


class Blog extends Component
{
    public $posts;
    public $content;
    public $title;
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
    }

    public function createPost()
    {
        $this->validate([
            'title' => 'required|string|max:50',
            'content' => 'required|string',
        ]);
    
        if ($this->editPostId) {
            $post = Post::find($this->editPostId);
            if ($post && ($post->user_id == Auth::id() || Auth::user()->role == 'admin')) {
                $post->title = $this->title;
                $post->content = $this->content;
                $post->save();
            }
        } else {
            // Crear nuevo post
            $post = new Post;
            $post->title = $this->title;
            $post->content = $this->content;
            $post->user_id = Auth::id();
            $post->save();
        }
    
        $this->resetPostForm(); // Restablecer los campos del formulario después de crear el post
    
        $this->posts = Post::with('user')->get();
        session()->flash('message', 'Post guardado exitosamente.');
        return redirect()->route('blog-posts');
    }



    public function startEditing($postId)
    {
        $this->editingPostId = $postId;
        $post = Post::find($postId);
        $this->editedTitle = $post->title;
        $this->editedContent = $post->content;
    }

    public function cancelEditing()
    {
        $this->editingPostId = null;
        $this->editedTitle = '';
        $this->editedContent = '';
    }

    public function updatePost()
    {
        $post = Post::find($this->editingPostId);
        if ($post && ($post->user_id == Auth::id() || Auth::user()->role == 'admin')) {
            $post->title = $this->editedTitle;
            $post->content = $this->editedContent;
            $post->save();
            $this->editingPostId = null;
            $this->editedTitle = '';
            $this->editedContent = '';
            session()->flash('message', 'Post actualizado exitosamente.');
        }
    }

    public function deletePost($postId)
    {
        $post = Post::find($postId);
        if ($post && ($post->user_id == Auth::id() || Auth::user()->role == 'admin')) {
            $post->delete();
            $this->posts = Post::with('user')->get();
            session()->flash('message', 'Post eliminado exitosamente.');
        }
    }

    public function render()
    {
        return view('livewire.blog');
    }
}