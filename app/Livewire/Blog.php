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
    public $image; // Propiedad para manejar la imagen
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
        $this->image = null; // Restablecer la imagen
    }

    public function createPost()
    {
        $this->validate([
            'title' => 'required|string|max:50',
            'content' => 'required|string',
            'image' => 'nullable|image|max:1024', // Validación para la imagen (opcional)
        ]);

        $imageName = null;
        if ($this->image) {
            $imageName = $this->image->store('public/posts'); // Guardar la imagen en storage
        }

        // Crear nuevo post
        $post = new Post;
        $post->title = $this->title;
        $post->content = $this->content;
        $post->image = $imageName; // Asignar el nombre de la imagen al campo correspondiente en la base de datos
        $post->user_id = Auth::id();
        $post->save();

        $this->resetPostForm(); // Restablecer los campos del formulario después de crear el post

        $this->posts = Post::with('user')->get();
        session()->flash('message', 'Post guardado exitosamente.');
    }

    public function render()
    {
        return view('livewire.blog');
    }
}