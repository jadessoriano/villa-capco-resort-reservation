<?php

use App\Http\Controllers;
use App\Http\Controllers\Auth\UserController;
use App\Models\Accommodation;
use App\Models\Addon;
use App\Models\Catering;
use App\Models\Faq;
use App\Models\Rating;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// TODO: Convert these to Controller.
Route::get('/home', function () {
    $ratings = Rating::where('is_featured', true)->get();
    return view('app.home', compact("ratings"));
})->name('home');

Route::get('/accommodations', function () {
    $accommodations = Accommodation::all();
    return view('app.accommodations', compact("accommodations"));
})->name('accommodations');

Route::get('/facilities', function () {
    $addons = Addon::all();
    return view('app.facilities', compact("addons"));
})->name('facilities');

Route::get('/caterings', function () {
    $caterings = Catering::all();
    return view('app.catering', compact("caterings"));
})->name('caterings');

Route::get('/faqs', function () {
    $faqs = Faq::all();
    return view('app.faqs', compact("faqs"));
})->name('faqs');

Route::get('/reservations/{accommodation_id?}/{package_id?}', function ($accommodation_id = null, $package_id = null) {
    $current_reservation = auth()->user() == null ?
        null :
        \App\Models\Reservation::where([
            ['user_id', auth()->user()->id],
            ['reserved_date', '>=', Carbon::now()]
        ])->first();
    return view('app.reservations', compact('accommodation_id', 'package_id', 'current_reservation'));
})->name('reservations');

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/admin', function () {
    return redirect('/admin/accommodations');
});

Route::post('/extension/request', [ Controllers\ExtensionController::class, 'test'])
    ->name('guest.extension.request');

Route::post('newsletter/subscribe', [ Controllers\NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::middleware('auth')->group(function () {
    /*
     * Guest (customer) routes.
     */

    Route::resource('user', UserController::class)
        ->only(['show', 'edit', 'update']);
});

require __DIR__.'/auth.php';
