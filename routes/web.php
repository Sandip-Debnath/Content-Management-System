<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\ArticlePublicController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =============================
// 🔹 HOME / LOGIN REDIRECT
// =============================
Route::redirect('/', '/login');

Route::get('/home', function () {
    return session('status')
    ? redirect()->route('admin.home')->with('status', session('status'))
    : redirect()->route('admin.home');
});

// =============================
// 🔹 AUTH ROUTES
// =============================
Auth::routes(['register' => false]);

// =============================
// 📢 PUBLIC CMS ROUTES (Frontend)
// =============================
// Article listing
Route::get('/articles', [ArticlePublicController::class, 'index'])->name('frontend.articles.index');
Route::get('/articles/{article}', [ArticlePublicController::class, 'show'])->name('frontend.articles.show');

// Comment posting
Route::post('/articles/{article}/comments', [ArticlePublicController::class, 'storeComment'])
    ->name('frontend.articles.comments.store')
    ->middleware('auth'); // guest@example.com will still pass if authenticated

// =============================
// 🛠 ADMIN PANEL ROUTES
// =============================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    Route::post('users/approve-verification', [UsersController::class, 'approveVerification'])->name('users.approveVerification');

    // Audit Logs (read-only)
    Route::resource('audit-logs', AuditLogsController::class)
        ->except(['create', 'store', 'edit', 'update', 'destroy']);

    // Settings
    Route::delete('settings/destroy', [SettingsController::class, 'massDestroy'])->name('settings.massDestroy');
    Route::post('settings/parse-csv-import', [SettingsController::class, 'parseCsvImport'])->name('settings.parseCsvImport');
    Route::post('settings/process-csv-import', [SettingsController::class, 'processCsvImport'])->name('settings.processCsvImport');
    Route::resource('settings', SettingsController::class);

    // Cities
    Route::delete('cities/destroy', [CityController::class, 'massDestroy'])->name('cities.massDestroy');
    Route::post('cities/parse-csv-import', [CityController::class, 'parseCsvImport'])->name('cities.parseCsvImport');
    Route::post('cities/process-csv-import', [CityController::class, 'processCsvImport'])->name('cities.processCsvImport');
    Route::resource('cities', CityController::class);

    // States
    Route::delete('states/destroy', [StateController::class, 'massDestroy'])->name('states.massDestroy');
    Route::post('states/parse-csv-import', [StateController::class, 'parseCsvImport'])->name('states.parseCsvImport');
    Route::post('states/process-csv-import', [StateController::class, 'processCsvImport'])->name('states.processCsvImport');
    Route::resource('states', StateController::class);

    // Countries
    Route::delete('countries/destroy', [CountriesController::class, 'massDestroy'])->name('countries.massDestroy');
    Route::post('countries/parse-csv-import', [CountriesController::class, 'parseCsvImport'])->name('countries.parseCsvImport');
    Route::post('countries/process-csv-import', [CountriesController::class, 'processCsvImport'])->name('countries.processCsvImport');
    Route::resource('countries', CountriesController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Articles
    Route::resource('articles', ArticleController::class);

    // Comments (moderation)
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('comments/{comment}', [CommentController::class, 'show'])->name('comments.show');
    Route::post('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// =============================
// 🔹 PROFILE ROUTES
// =============================
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});
