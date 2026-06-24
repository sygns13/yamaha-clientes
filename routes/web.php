<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormulariosVinController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VinController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ── Rutas públicas VIN (sin autenticación) ─────────────────────────────────
Route::get('/vin1',  [VinController::class, 'vin1'])->name('vin1');
Route::post('/vin1', [VinController::class, 'storeVin1'])->middleware('throttle:10,1')->name('vin1.store');
Route::get('/clientes/buscar', [VinController::class, 'buscarCliente'])->middleware('throttle:30,1')->name('clientes.buscar');
Route::get('/vin',  [VinController::class, 'vin2'])->name('vin');
Route::post('/vin', [VinController::class, 'storeVin2'])->middleware('throttle:10,1')->name('vin.store');

// ── Welcome ────────────────────────────────────────────────────────────────
/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});*/
Route::get('/', function () {
    return redirect()->route('vin');
});

// ── Panel Admin (requiere autenticación) ───────────────────────────────────
Route::middleware(['auth', 'verified', 'activo'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil Breeze
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Formularios VIN (acceso: admin + trabajador)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/formularios-vin',                          [FormulariosVinController::class, 'index'])->name('formularios-vin.index');
        Route::delete('/formularios-vin/{motocicleta}',         [FormulariosVinController::class, 'destroy'])->name('formularios-vin.destroy');
        Route::get('/formularios-vin/export-excel',             [FormulariosVinController::class, 'exportExcel'])->name('formularios-vin.export-excel');

        // Gestión de Usuarios (solo admin)
        Route::middleware('admin')->group(function () {
            Route::get('/usuarios',                             [UsuariosController::class, 'index'])->name('usuarios.index');
            Route::post('/usuarios',                            [UsuariosController::class, 'store'])->name('usuarios.store');
            Route::put('/usuarios/{usuario}',                   [UsuariosController::class, 'update'])->name('usuarios.update');
            Route::patch('/usuarios/{usuario}/toggle-activo',   [UsuariosController::class, 'toggleActivo'])->name('usuarios.toggle-activo');
            Route::delete('/usuarios/{usuario}',                [UsuariosController::class, 'destroy'])->name('usuarios.destroy');
        });
    });
});

require __DIR__.'/auth.php';
