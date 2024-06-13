<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Response;
use App\Models\Like;

class BlogPosts extends Component
{
    public $posts;
    public $content;
    public $title;
    public $editPostId;
    public $editingPostId;
    public $editedTitle;
    public $editedContent;
    public $responseContent;
    public $postIdBeingResponded;
    public $like;
    public $likeCounter;

    public function mount()
    {
        $this->posts = Post::with(['user', 'responses.user','likes'])->get();
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
            $post = new Post;
            $post->title = $this->title;
            $post->content = $this->content;
            $post->user_id = Auth::id();
            $post->save();
        }
    
        $this->resetPostForm();
    
        $this->posts = Post::with(['user', 'responses.user'])->get();
        session()->flash('message', 'Post guardado exitosamente.');
        return redirect()->route('/blog-posts');
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
            $this->posts = Post::with(['user', 'responses.user'])->get();
            session()->flash('message', 'Post eliminado exitosamente.');
        }
    }

    public function counterLike(){
        $post = Post::find($this->counterLikePostId);
        if ($post && ($post->user_id == Auth::id() || Auth::user()->role == 'admin')) {

        }

    }

    public function startResponding($postId)
    {
        $this->postIdBeingResponded = $postId;
        $this->responseContent = '';
    }

    public function submitResponse()
    {
        $this->validate([
            'responseContent' => 'required|string',
        ]);

        Response::create([
            'post_id' => $this->postIdBeingResponded,
            'user_id' => Auth::id(),
            'content' => $this->responseContent,
        ]);

        $this->responseContent = '';
        $this->postIdBeingResponded = null;

        $this->posts = Post::with(['user', 'responses.user'])->get();
    }

    public function toggleLike($postId)
    {
        $post = Post::find($postId);

        if ($post) {
            $like = Like::where('post_id', $postId)
                ->where('user_id', Auth::id())
                ->first();

            if ($like) {
                $like->delete();
            } else {
                Like::create([
                    'post_id' => $postId,
                    'user_id' => Auth::id(),
                ]);
            }

            $this->posts = Post::with(['user', 'responses.user', 'likes'])->get();
        }
    }

    public function isLiked($postId)
{
    $post = Post::findOrFail($postId);
    return $post->likes()->where('user_id', auth()->id())->exists();
}

    public function render()
    {
        return view('livewire.blog-posts');
    }
}