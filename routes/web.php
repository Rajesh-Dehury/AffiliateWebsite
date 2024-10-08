<?php

use App\Http\Controllers\RedirectController;
use App\Livewire\AboutUs;
use App\Livewire\Admin\AllPosts;
use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\GetAmazonProductDetails;
use App\Livewire\Admin\PostDetails as AdminPostDetails;
use App\Livewire\Admin\SettingUpdate;
use App\Livewire\Admin\UrlGenerate;
use App\Livewire\ContactUs;
use App\Livewire\Disclaimer;
use App\Livewire\Home;
use App\Livewire\PostDetails;
use App\Livewire\Privacy;
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
Route::get('/', Home::class)->name('home');
Route::get('/about', AboutUs::class)->name('about');
Route::get('/privacy', Privacy::class)->name('privacy');
Route::get('/disclaimer', Disclaimer::class)->name('disclaimer');
Route::get('/contact', ContactUs::class)->name('contact');
Route::get('details/{prod_id}', PostDetails::class)->name('details');
Route::get('/open/{product_asin}', [RedirectController::class, 'redirectToAnotherUrl'])->name('open.az.prod');

Route::middleware(['guest:admin'])->group(function () {
    Route::get('admin/login', Login::class)->name('admin.login');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('admin/url-generate', UrlGenerate::class)->name('admin.url-generate');
    Route::get('admin/get-prod/az', GetAmazonProductDetails::class)->name('admin.get-prod.az');
    Route::get('admin/all/posts', AllPosts::class)->name('admin.all.posts');
    Route::get('admin/{id}/post', AdminPostDetails::class)->name('admin.post');
    Route::get('admin/settings', SettingUpdate::class)->name('admin.settings');
    Route::get('admin/logout', function () {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');
});
