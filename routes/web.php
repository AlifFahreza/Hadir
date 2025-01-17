<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SekretarisController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\Anggota;
use App\Http\Controllers\DashboardAdminController;
use Illuminate\Support\Facades\Password;

Route::get('/', [PresensiController::class, 'getdata'])->name('admin.dashboard');
Route::get('/register', function () {
    return view('register');
});
Route::get('/registeradmin', function () {
    return view('registeradmin');
});
Route::get('/admin', [PresensiController::class, 'getdata'])->name('admin.dashboard');
Route::get('/organisasi', [PresensiController::class, 'getorganisasi'])->name('admin.dashboard');
Route::get('/riwayatjadwal', [PresensiController::class, 'getdatahistory'])->name('riwayatjadwal');
Route::post('/riwayatjadwal/submit', [PresensiController::class, 'cetak'])->name('absen-anggota.show');
Route::get('/registeradmin', [AdminController::class, 'getData'])->name('registeradmin');
Route::get('/dashboardAdmin', [DashboardAdminController::class, 'dashboardAdmin'])->name('dashboard-admin');
Route::post('/dashboardAdmin/submit', [DashboardAdminController::class, 'showSelectedData'])->name('dashboard-admin.show');

// Route::get('/history', function () {
//     return view('riwayat');
// });
Route::get('/user', [SekretarisController::class, 'getAllDetailPresensi'])->name('detail-presensi.index');
Route::get('/detail-presensi/{idPresensi}', [SekretarisController::class, 'getDetailPresensi'])->name('detail.presensi');

Route::get('/absensi/{kode_acak}', [SekretarisController::class, 'store'])->name('absensi.submit');

Route::post('/update-presensi/{id}', [PresensiController::class, 'updatePresensi'])->name('update.presensi');
Route::get('/delete-detail/{id}', [SekretarisController::class, 'deleteDetailPresensi'])->name('delete.detail.presensi');
Route::get('/redirect', [AnggotaController::class, 'redirectToProfile'])->name('redirect.to.profile');
Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');
Route::post('/update-password', [AdminController::class, 'updatepass'])->name('update.password');
Route::post('/user/update', [AnggotaController::class, 'update'])->name('user.update');
Route::post('/user-password', [AnggotaController::class, 'updatepass'])->name('user.password');
Route::get('/delete-presensi/{id}', [PresensiController::class, 'deletePresensi'])->name('delete.presensi');

Route::get('/logout', [AnggotaController::class, 'logout'])->name('logout');

Route::get('/userdata', [AnggotaController::class, 'getdata'])->name('userdata');
Route::post('/registers', [AnggotaController::class, 'register'])
    ->middleware('guest')
    ->name('registers');

    Route::get('sign-in', [AnggotaController::class, 'create'])->middleware('guest')->name('login');
    Route::post('sign-in', [AnggotaController::class, 'store'])->middleware('guest');

Route::post('/insert-presensi', [PresensiController::class, 'insertPresensi'])->name('insert.presensi');

Route::post('/registera', [AdminController::class, 'register'])
    ->middleware('guest')
    ->name('admin.register');


Route::post('/organisasistore', [OrganisasiController::class, 'store'])->name('organisasi.store');
Route::post('/organisasi/update/{id}', [OrganisasiController::class, 'update'])->name('update.organisasi');
Route::delete('/organisasi/{id}', [OrganisasiController::class, 'destroy'])->name('delete.organisasi');
    // Rute untuk menampilkan form registrasi admin
        Route::post('/user/profile', [AnggotaController::class, 'updateProfile'])->name('user.update.profile');
        Route::get('/delete-user/{id}', [AnggotaController::class, 'deleteProfile'])->name('delete.user');



// Rute untuk menampilkan form forgot password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('forgot-password');

// Rute untuk mengirim link reset password
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Rute untuk menampilkan form reset password
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Rute untuk mengatur ulang password
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (Anggota $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');


// Route::get('download-pdf', [PostController::class, 'downloadPDF']);