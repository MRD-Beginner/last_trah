<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\LogicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SilsilahController;
use App\Http\Controllers\UserController;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('login');
// })->middleware('guest');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user.keluarga');
    });

    Route::get('user/data/keluarga', [UserController::class, 'keluarga'])->name('user.keluarga');
    
    Route::get('user/profile', [ProfileController::class, 'edit'])->name('profile.edit');


    Route::post('user/data/keluarga/add', [KeluargaController::class, 'store_user'])->name('user.keluarga.store');

    Route::post('user/data/keluarga/edit/{id}', [KeluargaController::class, 'edit_user'])->name('user.keluarga.edit');

    Route::put('user/data/keluarga/update/{id}', [KeluargaController::class, 'update_user'])->name('user.keluarga.update');

    Route::delete('user/data/keluarga/delete/{id}', [KeluargaController::class, 'delete_user'])->name('user.keluarga.delete');

    Route::get('user/keluarga/detail/private/{id}', [KeluargaController::class, 'detail_private_user'])->name('user.keluarga.detail.private');

    Route::post('user/keluarga/verify-pass/{id}', [KeluargaController::class, 'checkPassword2'])
        ->name('user.keluarga.check.pass');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'route'])->name('auth.verification.admin');

    Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('admin/data/keluarga', [AdminController::class, 'keluarga'])->name('admin.keluarga');

    Route::post('admin/data/keluarga/add', [KeluargaController::class, 'store'])->name('keluarga.store');

    Route::post('admin/data/keluarga/edit/{id}', [KeluargaController::class, 'edit'])->name('keluarga.edit');

    Route::put('admin/data/keluarga/update/{id}', [KeluargaController::class, 'update'])->name('keluarga.update');

    Route::delete('admin/data/keluarga/delete/{id}', [KeluargaController::class, 'delete'])->name('keluarga.delete');

    Route::get('admin/data/user', [UserController::class, 'index'])->name('admin.user.management');

    Route::get('keluarga/detail/private/{id}', [KeluargaController::class, 'detail_private'])->name('keluarga.detail.private');

});
Route::post('/keluarga/{id}/verify-password', [KeluargaController::class, 'verifyPassword'])
 ->name('keluarga.verify.password');

// Route::get('detail/public/data/keluarga', function () {
//         return view('detail.detaill');
//     });

Route::get('detail/public/data/keluarga/{id}', [KeluargaController::class, 'showPublic'])->name('keluarga.public');

Route::get('keluarga/detail/public/{id}', [KeluargaController::class, 'detail_public'])->name('keluarga.detail.public');

Route::get('keluarga/detail/private/{id}', [KeluargaController::class, 'detail_private'])->name('keluarga.detail.private');

Route::post('keluarga/detail/public/add', [SilsilahController::class, 'create_anggota_keluarga'])->name('anggota.keluarga.store');

Route::get('keluarga/detail/public/edit/{id}', [SilsilahController::class, 'edit_anggota_keluarga'])->name('anggota.keluarga.edit');

Route::put('keluarga/detail/public/update/{id}', [SilsilahController::class, 'update_anggota_keluarga'])->name('anggota.keluarga.update');

Route::delete('keluarga/detail/public/delete/{id}', [SilsilahController::class, 'delete_anggota_keluarga'])->name('anggota.keluarga.delete');

Route::post('keluarga/detail/public/add/pasangan', [SilsilahController::class, 'create_pasangan_anggota_keluarga'])->name('pasangan.anggota.keluarga.store');

Route::get('keluarga/detail/public/edit/pasangan/{id}', [SilsilahController::class, 'edit_pasangan_anggota_keluarga'])->name('pasangan.anggota.keluarga.edit');

Route::put('keluarga/detail/public/update/pasangan/{id}', [SilsilahController::class, 'update_pasangan_anggota_keluarga'])->name('pasangan.anggota.keluarga.update');

Route::delete('keluarga/detail/public/delete/pasangan/{id}', [SilsilahController::class, 'delete_pasangan_anggota_keluarga'])->name('pasangan.anggota.keluarga.delete');

Route::post('keluarga/compare', [LogicController::class, 'compare'])->name('pasangan.anggota.keluarga.compare');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'route'])->name('dashboard');
});

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/user/{id}/profile', [ProfileController::class, 'edit'])->name('user.profile');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // <-- Ini yang diperlukan
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::fallback(function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return auth()->user()->hasRole('admin')
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.keluarga');
});

require __DIR__ . '/auth.php';
