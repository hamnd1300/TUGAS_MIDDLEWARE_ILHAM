<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PesananController;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
})->name('login.process');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/', [PesananController::class, 'store'])->name('pesanan.store');
    Route::get('/edit/{id}', [PesananController::class, 'edit'])->name('pesanan.edit');
    Route::post('/edit/{id}', [PesananController::class, 'update'])->name('pesanan.update');
    Route::get('/hapus/{id}', [PesananController::class, 'destroy'])->name('pesanan.destroy');
});