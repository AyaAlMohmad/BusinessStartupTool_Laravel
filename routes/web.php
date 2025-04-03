<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\ProgressAnalyticsController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', function () {
    return url('/index.html');
});
// Route::get('/storage', function () {
//      $target = storage_path('app/public');
//     $link = public_path('storage');

//     if (!File::exists($link)) {
//         File::copyDirectory($target, $link); // نسخ الملفات بدلاً من الرابط الرمزي
//         return "Storage directory copied successfully!";
//     }

//     return "Storage link already exists!";
// });
Route::get('/das', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']);





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::prefix('admin/')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
Route::resource('videos', VideoController::class);
Route::get('/Progress', [ProgressAnalyticsController::class, 'index'])->name('ProgressAnalytics');
    // Route::get('/', [UserController::class, 'index'])->name('index'); 
    // Route::get('/{id}', [UserController::class, 'show'])->name('show');
    Route::patch('/{id}/status', [UserController::class, 'changeStatus'])->name('changeStatus'); 
    // Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy'); 
    Route::resource('users', UserController::class);

});

require __DIR__.'/auth.php';
