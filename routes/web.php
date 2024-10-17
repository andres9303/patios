<?php

use App\Http\Controllers\security\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {    return redirect()->route('login');})->middleware('guest');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified',])->group(function () { Route::get('/home', function () {return view('dashboard'); })->name('home');});

//Security
Route::resource('users', UserController::class)->middleware(['auth', 'can:view-menu,"user"'])->except(['show'])->names('user');
Route::get('/users/{user}/edit-password', [UserController::class, 'editPassword'])->name('user.editPassword');
Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
Route::get('/users/{user}/roles', [UserController::class, 'indexRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.index');
Route::get('/users/{user}/roles/create', [UserController::class, 'createRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.create');
Route::post('/users/{user}/roles', [UserController::class, 'storeRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.store');
Route::delete('/users/{user}/roles/{role}/{company}', [UserController::class, 'destroyRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.destroy');

