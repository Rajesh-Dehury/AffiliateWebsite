<?php

use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', Login::class)->name('admin.login');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('admin/logout', function () {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');
});
