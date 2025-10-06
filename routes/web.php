<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\PublicBookingController;


Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});


Route::controller('VehicleController')->prefix('vehicles')->name('vehicles.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('details/{id}/{slug}', 'details')->name('details');
    Route::get('filter', 'filter')->name('filter');
    Route::get('/vehicles/available', 'available')->name('vehicles.available');

    Route::get('search/brand/{brand_id}/{slug}', 'brand')->name('brand');
    Route::get('search/{seat_id}/seater', 'seater')->name('seater');
});


Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::post('subscribe', 'subscribe')->name('subscribe');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('plans', 'plans')->name('plans');
    Route::post('plan/{id}', 'planBooking')->name('plan.booking');

    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');


    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

//Route::any('payment/{alias}/return', [PaymentController::class, 'callback'])->name('payment.return');
//Route::any('payment/{alias}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
//

Route::middleware(['web'])->group(function () {
    Route::any('payment/{alias}/return', [PaymentController::class, 'callback'])->name('payment.return');
    Route::any('payment/{alias}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('user/deposit/failed', function () {
        return view('template.user.payment.failed'); // Or any view you want to show
    })->name('user.deposit.failed');

});

Route::prefix('public-booking')->name('booking.')->group(function () {
    Route::get('/book/{id}', [PublicBookingController::class, 'showIndex'])->name('guest.book');
    Route::post('/check/{id}', [PublicBookingController::class, 'checkAvailability'])->name('guest.book.check');
//    Route::get('/dossier/{id}/{slug}', [PublicBookingController::class, 'guestVehicleBooking'])->name('guest.dossier');
    Route::post('/dossier/{id}', [PublicBookingController::class, 'guestSubmit'])->name('guest.submit');
    Route::get('/dossier/info/{id}', [PublicBookingController::class, 'showGuestForm'])->name('guest.info');

});

