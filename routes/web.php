<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Form;
use App\Livewire\UsersEdit;
use App\Http\Controllers\Controller;
use App\Livewire\Blog;
use App\Livewire\BlogPosts;
use App\Models\User;
use App\Models\Post;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






Route::get('/form', Form::class)->name('form');
Route::get('/edit/{id}', UsersEdit::class,)->name('users-edit');
Route::get('/create', UsersEdit::class)->name('users-create');
Route::get('/regenerate-ids', [Controller::class, 'regenerate'])->name('regenerate.ids');
Route::get('/user/{userId}/posts', function ($userId) {
    $user = User::find($userId);
    $posts = $user->posts;
    
    return view('blog', compact('user', 'posts'));
});

Route::get('/blog', Blog::class)->name('blog');
Route::get('/blog-posts', BlogPosts::class)->name('blog-posts');



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', function () {
        return view('user.dashboard');
    });
});

// Route::get('users-create', UsersEdit::class)->name('users-create')->withoutMiddleware('auth');


require __DIR__.'/auth.php';


