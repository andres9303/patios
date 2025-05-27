<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\config\ListController;
use App\Http\Controllers\config\VariableController;
use App\Http\Controllers\cost\AdjustmentController;
use App\Http\Controllers\cost\AssignController;
use App\Http\Controllers\cost\BillController;
use App\Http\Controllers\cost\CountController;
use App\Http\Controllers\cost\DirectPurchaseController;
use App\Http\Controllers\cost\OutputController;
use App\Http\Controllers\cost\ReturnController;
use App\Http\Controllers\Event\CategoryActivityController;
use App\Http\Controllers\Event\MeetingController;
use App\Http\Controllers\Event\TimetableController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\master\AreaController;
use App\Http\Controllers\master\CategoryController;
use App\Http\Controllers\master\CategoryProductController;
use App\Http\Controllers\master\CompanyController;
use App\Http\Controllers\master\EventTypeController;
use App\Http\Controllers\master\LocationController;
use App\Http\Controllers\master\PersonController;
use App\Http\Controllers\master\ProductController;
use App\Http\Controllers\master\SpaceController;
use App\Http\Controllers\master\UnitController;
use App\Http\Controllers\notification\TelegramController;
use App\Http\Controllers\notification\TelegramWebhookController;
use App\Http\Controllers\project\ActivityController;
use App\Http\Controllers\project\AdvanceProjectController;
use App\Http\Controllers\project\ProjectController;
use App\Http\Controllers\project\ScheduleController;
use App\Http\Controllers\report\ReportController;
use App\Http\Controllers\security\MenuController;
use App\Http\Controllers\security\RoleController;
use App\Http\Controllers\security\UserController;
use App\Http\Controllers\space\ChecklistController;
use App\Http\Controllers\space\EventController;
use App\Http\Controllers\space\FieldController;
use App\Http\Controllers\space\InputController;
use App\Http\Controllers\space\TemplateController;
use App\Http\Controllers\Ticket\ManageTicketController;
use App\Http\Controllers\Ticket\MeTicketController;
use App\Http\Controllers\Ticket\Resolve2TicketController;
use App\Http\Controllers\Ticket\Resolve3TicketController;
use App\Http\Controllers\Ticket\ResolveTicketController;
use App\Http\Controllers\Ticket\TicketController;
use Illuminate\Http\Request;
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
Route::resource('units', UnitController::class)->middleware(['auth', 'can:view-menu,"unit"'])->except(['show'])->names('unit');
Route::resource('categories-products', CategoryProductController::class)->middleware(['auth', 'can:view-menu,"category-product"'])->except(['show'])->names('category-product');
Route::resource('products', ProductController::class)->middleware(['auth', 'can:view-menu,"product"'])->except(['show'])->names('product');
Route::get('products/{product}/edit/image', [ProductController::class, 'editImage'])->name('product.edit.image');
Route::put('products/{product}/update/image', [ProductController::class, 'updateImage'])->name('product.update.image');
Route::resource('spaces', SpaceController::class)->middleware(['auth', 'can:view-menu,"space"'])->except(['show'])->names('space');
Route::resource('areas', AreaController::class)->middleware(['auth', 'can:view-menu,"area"'])->except(['show'])->names('area');
Route::resource('event-types', EventTypeController::class)->middleware(['auth', 'can:view-menu,"event-type"'])->except(['show'])->names('event-type');

//Ticket
Route::resource('tickets', TicketController::class)->middleware(['auth', 'can:view-menu,"ticket"'])->except(['show'])->names('ticket');
Route::get('/tickets/show/{ticket}', [TicketController::class, 'show'])->middleware(['auth'])->name('ticket.show');
Route::get('/tickets/{ticket}/attachments', [TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"ticket"'])->name('ticket.attachment.index');
Route::resource('manage-tickets', ManageTicketController::class)->middleware(['auth', 'can:view-menu,"manage-ticket"'])->except(['show'])->names('manage-ticket');
Route::get('/manage-tickets/{manage_ticket}/attachments', [ManageTicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"manage-ticket"'])->name('manage-ticket.attachment.index');
Route::get('/resolve-tickets', [ResolveTicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->name('resolve-ticket.index');
Route::resource('/{ticket}/resolve-tickets', ResolveTicketController::class)->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->except(['index', 'show'])->names('resolve-ticket');
Route::get('/{ticket}/resolve-tickets/{resolve_ticket}/attachments', [ResolveTicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-ticket"'])->name('resolve-ticket.attachment.index');
Route::get('/resolve-2tickets', [Resolve2TicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->name('resolve-2ticket.index');
Route::resource('/{ticket}/resolve-2tickets', Resolve2TicketController::class)->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->except(['index', 'show'])->names('resolve-2ticket');
Route::get('/{ticket}/resolve-2tickets/{resolve_2ticket}/attachments', [Resolve2TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-2ticket"'])->name('resolve-2ticket.attachment.index');
Route::get('/resolve-3tickets', [Resolve3TicketController::class, 'index'])->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->name('resolve-3ticket.index');
Route::resource('/{ticket}/resolve-3tickets', Resolve3TicketController::class)->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->except(['index', 'show'])->names('resolve-3ticket');
Route::get('/{ticket}/resolve-3tickets/{resolve_3ticket}/attachments', [Resolve3TicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"resolve-3ticket"'])->name('resolve-3ticket.attachment.index');
Route::resource('me-ticket', MeTicketController::class)->middleware(['auth', 'can:view-menu,"me-ticket"'])->except(['show'])->names('me-ticket');
Route::get('/me-ticket/{me_ticket}/attachments', [MeTicketController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"me-ticket"'])->name('me-ticket.attachment.index');

//Projects
Route::resource('projects', ProjectController::class)->middleware(['auth', 'can:view-menu,"project"'])->except(['show'])->names('project');
Route::resource('/projects/{project}/activities', ActivityController::class)->middleware(['auth', 'can:view-menu,"project"'])->except(['show'])->names('activity');
Route::get('/projects/{project}/complete', [ProjectController::class, 'complete'])->middleware(['auth', 'can:view-menu,"project"'])->name('project.complete');
Route::get('/projects/{project}/open', [ProjectController::class, 'open'])->middleware(['auth', 'can:view-menu,"project"'])->name('project.open');
Route::resource('advance-projects', AdvanceProjectController::class)->middleware(['auth', 'can:view-menu,"advance-project"'])->except(['show', 'update'])->names('advance-project');
Route::get('/advance-projects/{advance_project}/attachments', [AdvanceProjectController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"advance-project"'])->name('advance-project.attachment.index');
Route::resource('schedules', ScheduleController::class)->middleware(['auth', 'can:view-menu,"schedule"'])->except(['show'])->names('schedule');
Route::get('/schedules/{schedule}', [ScheduleController::class, 'schedule'])->middleware(['auth', 'can:view-menu,"schedule"'])->name('schedule.schedule');

//Costs
Route::resource('bills', BillController::class)->middleware(['auth', 'can:view-menu,"bill"'])->except(['show', 'update'])->names('bill');
Route::get('/bills/{bill}/attachments', [BillController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"bill"'])->name('bill.attachment.index');
Route::resource('direct-purchases', DirectPurchaseController::class)->middleware(['auth', 'can:view-menu,"direct-purchase"'])->except(['show', 'update'])->names('direct-purchase');
Route::get('/direct-purchases/{direct_purchase}/attachments', [DirectPurchaseController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"direct-purchase"'])->name('direct-purchase.attachment.index');
Route::resource('outputs', OutputController::class)->middleware(['auth', 'can:view-menu,"output"'])->except(['show', 'update'])->names('output');
Route::get('/outputs/{output}/attachments', [OutputController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"output"'])->name('output.attachment.index');
Route::resource('adjustments', AdjustmentController::class)->middleware(['auth', 'can:view-menu,"adjustment"'])->except(['show', 'update'])->names('adjustment');
Route::get('/adjustments/{adjustment}/attachments', [AdjustmentController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"adjustment"'])->name('adjustment.attachment.index');
Route::resource('assign', AssignController::class)->middleware(['auth', 'can:view-menu,"assign"'])->except(['show', 'update'])->names('assign');
Route::get('/assign/{assign}/attachments', [AssignController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"assign"'])->name('assign.attachment.index');
Route::resource('return', ReturnController::class)->middleware(['auth', 'can:view-menu,"return"'])->except(['show', 'update'])->names('return');
Route::get('/return/{return}/attachments', [ReturnController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"return"'])->name('return.attachment.index');
Route::resource('count', CountController::class)->middleware(['auth', 'can:view-menu,"count"'])->except(['show', 'update'])->names('count');
Route::get('/count/{count}/attachments', [CountController::class, 'attachment'])->middleware(['auth', 'can:view-menu,"count"'])->name('count.attachment.index');

//Spaces
Route::resource('event', EventController::class)->middleware(['auth', 'can:view-menu,"event"'])->except(['show'])->names('event');
Route::resource('input', InputController::class)->middleware(['auth', 'can:view-menu,"input"'])->except(['show'])->names('input');
Route::resource('template', TemplateController::class)->middleware(['auth', 'can:view-menu,"template"'])->names('template');
Route::resource('template/{template}/fields', FieldController::class)->middleware(['auth', 'can:view-menu,"template"'])->except(['show'])->names('field');
Route::get('checklist', [ChecklistController::class, 'index'])->middleware(['auth', 'can:view-menu,"checklist"'])->name('checklist.index');
Route::resource('template/{template}/checklist', ChecklistController::class)->middleware(['auth', 'can:view-menu,"checklist"'])->except(['index', 'show'])->names('checklist');

//Events
Route::resource('category-activity', CategoryActivityController::class)->middleware(['auth', 'can:view-menu,"category-activity"'])->except(['show'])->names('category-activity');
Route::resource('meeting', MeetingController::class)->middleware(['auth', 'can:view-menu,"meeting"'])->except(['show'])->names('meeting');
Route::resource('timetable', TimetableController::class)->middleware(['auth', 'can:view-menu,"timetable"'])->except(['show'])->names('timetable');

//Reports
Route::get('reports/inventory/balance', [ReportController::class, 'balance'])->middleware(['auth', 'can:view-menu,"report-balance-inv"'])->name('report-balance-inv.index');
Route::get('reports/inventory/movement', [ReportController::class, 'movementInv'])->middleware(['auth', 'can:view-menu,"report-movement-inv"'])->name('report-movement-inv.index');
Route::get('reports/inventory/kardex', [ReportController::class, 'kardex'])->middleware(['auth', 'can:view-menu,"report-kardex"'])->name('report-kardex.index');
Route::get('reports/cost/activity', [ReportController::class, 'costActivity'])->middleware(['auth', 'can:view-menu,"report-cost-activity"'])->name('report-cost-activity.index');
Route::get('reports/ticket-history', [ReportController::class, 'ticketHistory'])->middleware(['auth', 'can:view-menu,"report-ticket-history"'])->name('report-ticket-history.index');
Route::get('reports/monthly-cost', [ReportController::class, 'monthlyCost'])->middleware(['auth', 'can:view-menu,"report-monthly-cost"'])->name('report-monthly-cost.index');
Route::get('reports/borrow', [ReportController::class, 'borrow'])->middleware(['auth', 'can:view-menu,"report-borrow"'])->name('report-borrow.index');
Route::get('reports/balance-project', [ReportController::class, 'balanceProject'])->middleware(['auth', 'can:view-menu,"report-balance-project"'])->name('report-balance-project.index');
Route::get('reports/cost-detail', [ReportController::class, 'costDetail'])->middleware(['auth', 'can:view-menu,"report-cost-detail"'])->name('report-cost-detail.index');
Route::get('reports/balance-space', [ReportController::class, 'balanceSpace'])->middleware(['auth', 'can:view-menu,"report-balance-space"'])->name('report-balance-space.index');
Route::get('reports/space-detail', [ReportController::class, 'spaceDetail'])->middleware(['auth', 'can:view-menu,"report-space-detail"'])->name('report-space-detail.index');
Route::get('report-pending-activity', [ReportController::class, 'pendingActivity'])->middleware(['auth', 'can:view-menu,"report-pending-activity"'])->name('report-pending-activity.index');
Route::get('report-responsible-activity', [ReportController::class, 'responsibleActivity'])->middleware(['auth', 'can:view-menu,"report-responsible-activity"'])->name('report-responsible-activity.index');
Route::get('report-category-event', [ReportController::class, 'categoryEvent'])->middleware(['auth', 'can:view-menu,"report-category-event"'])->name('report-category-event.index');
Route::get('report-timetable-event', [ReportController::class, 'timetableEvent'])->middleware(['auth', 'can:view-menu,"report-timetable-event"'])->name('report-timetable-event.index');

//Config
Route::resource('lists', ListController::class)->middleware(['auth', 'can:view-menu,"list"'])->except(['show'])->names('list');
Route::resource('variables', VariableController::class)->middleware(['auth', 'can:view-menu,"variable"'])->except(['show'])->names('variable');

//Notifications
Route::middleware(['auth', 'can:view-menu,"notification"'])->group(function () {
    Route::get('/notification/index', [TelegramController::class, 'index'])
         ->name('notification.index');
});
Route::middleware(['auth', 'can:view-menu,"notification"'])->group(function () {
    Route::get('/notification/telegram/unlink', [TelegramController::class, 'telegramUnlink'])
         ->name('notification.telegram.unlink');
});
Route::post('/telegram/webhook/{secret}', function (Request $request, $secret) {
    if ($secret !== env('TELEGRAM_WEBHOOK_SECRET')) {
        abort(403, 'Acceso no autorizado');
    }
    
    return app(TelegramWebhookController::class)->handleWebhook($request);
});