<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\config\ListController;
use App\Http\Controllers\config\VariableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\master\CompanyController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\master\PersonController;
use App\Http\Controllers\security\MenuController;
use App\Http\Controllers\security\RoleController;
use App\Http\Controllers\security\UserController;
use App\Http\Controllers\Ticket\ManageTicketController;
use App\Http\Controllers\Ticket\Resolve2TicketController;
use App\Http\Controllers\Ticket\Resolve3TicketController;
use App\Http\Controllers\Ticket\ResolveTicketController;
use App\Http\Controllers\Ticket\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {    return redirect()->route('login');})->middleware('guest');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified',])->group(function () { Route::get('/home', [HomeController::class, 'index'])->name('home');});

//Attachment
Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

//Security
Route::resource('users', UserController::class)->middleware(['auth', 'can:view-menu,"user"'])->except(['show'])->names('user');
Route::get('/users/{user}/edit-password', [UserController::class, 'editPassword'])->name('user.editPassword');
Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.updatePassword');
Route::get('/users/{user}/roles', [UserController::class, 'indexRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.index');
Route::get('/users/{user}/roles/create', [UserController::class, 'createRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.create');
Route::post('/users/{user}/roles', [UserController::class, 'storeRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.store');
Route::delete('/users/{user}/roles/{role}/{company}', [UserController::class, 'destroyRole'])->middleware(['auth', 'can:view-menu,"user"'])->name('user.role.destroy');

Route::resource('roles', RoleController::class)->middleware(['auth', 'can:view-menu,"role"'])->except(['show'])->names('role');
Route::resource('menus', MenuController::class)->middleware(['auth', 'can:view-menu,"menu"'])->except(['show'])->names('menu');
Route::get('/permissions', [RoleController::class, 'indexPermission'])->middleware(['auth', 'can:view-menu,"permission"'])->name('permission.index');
Route::get('/permissions/create', [RoleController::class, 'createPermission'])->middleware(['auth', 'can:view-menu,"permission"'])->name('permission.create');
Route::post('/permissions', [RoleController::class, 'storePermission'])->middleware(['auth', 'can:view-menu,"permission"'])->name('permission.store');
Route::delete('/permissions/{role}/{menu}/{permission}', [RoleController::class, 'destroyPermission'])->middleware(['auth', 'can:view-menu,"permission"'])->name('permission.destroy');

Route::get('/shortcuts', [RoleController::class, 'indexShortcut'])->middleware(['auth', 'can:view-menu,"shortcut"'])->name('shortcut.index');
Route::get('/shortcuts/create', [RoleController::class, 'createShortcut'])->middleware(['auth', 'can:view-menu,"shortcut"'])->name('shortcut.create');
Route::post('/shortcuts', [RoleController::class, 'storeShortcut'])->middleware(['auth', 'can:view-menu,"shortcut"'])->name('shortcut.store');
Route::delete('/shortcuts/{role}/{menu}', [RoleController::class, 'destroyShortcut'])->middleware(['auth', 'can:view-menu,"shortcut"'])->name('shortcut.destroy');

//Master
Route::resource('companies', CompanyController::class)->middleware(['auth', 'can:view-menu,"company"'])->except(['show'])->names('company');
Route::resource('people', PersonController::class)->middleware(['auth', 'can:view-menu,"person"'])->except(['show'])->names('person');
Route::resource('locations', LocationController::class)->middleware(['auth', 'can:view-menu,"location"'])->except(['show'])->names('location');
Route::resource('categories', CategoryController::class)->middleware(['auth', 'can:view-menu,"category"'])->except(['show'])->names('category');

//Ticket
Route::resource('tickets', TicketController::class)->middleware(['auth', 'can:view-menu,"ticket"'])->except(['show'])->names('ticket');
Route::get('/tickets/show/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/tickets/{ticket}/attachments', [TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"ticket"'])->name('ticket.attachment.index');
Route::resource('manage-tickets', ManageTicketController::class)->middleware(['auth', 'can:view-menu,"manage-ticket"'])->except(['show'])->names('manage-ticket');
Route::get('/resolve-tickets', [ResolveTicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->name('resolve-ticket.index');
Route::resource('/{ticket}/resolve-tickets', ResolveTicketController::class)->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->except(['index', 'show'])->names('resolve-ticket');
Route::get('/{ticket}/resolve-tickets/{resolve_ticket}/attachments', [ResolveTicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->name('resolve-ticket.attachment.index');
Route::get('/resolve-2tickets', [Resolve2TicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->name('resolve-2ticket.index');
Route::resource('/{ticket}/resolve-2tickets', Resolve2TicketController::class)->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->except(['index', 'show'])->names('resolve-2ticket');
Route::get('/{ticket}/resolve-2tickets/{resolve_2ticket}/attachments', [Resolve2TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->name('resolve-2ticket.attachment.index');
Route::get('/resolve-3tickets', [Resolve3TicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->name('resolve-3ticket.index');
Route::resource('/{ticket}/resolve-3tickets', Resolve3TicketController::class)->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->except(['index', 'show'])->names('resolve-3ticket');
Route::get('/{ticket}/resolve-3tickets/{resolve_3ticket}/attachments', [Resolve3TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->name('resolve-3ticket.attachment.index');

//Config
Route::resource('lists', ListController::class)->middleware(['auth', 'can:view-menu,"list"'])->except(['show'])->names('list');
Route::resource('variables', VariableController::class)->middleware(['auth', 'can:view-menu,"variable"'])->except(['show'])->names('variable');