<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DossierController;
use App\Http\Controllers\Admin\FactureController;


Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function () {
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('chart/deposit-withdraw', 'depositAndWithdrawReport')->name('chart.deposit.withdraw');
        Route::get('chart/transaction', 'transactionReport')->name('chart.transaction');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

    });

    // Brand Manager
    Route::controller('BrandController')->name('brand.')->prefix('brand')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });
    // User Manager
    Route::controller('ModeratorController')->name('moderator.')->prefix('moderator')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('store/{id?}', 'store')->name('store');
            Route::post('status/{id}', 'status')->name('status');
        });

    // Location Manager
    Route::controller('LocationController')->name('location.')->prefix('location')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });
    // Seater Manager
    Route::controller('SeaterController')->name('seater.')->prefix('seater')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });

    Route::controller('BookVehiculeController')->group(function () {
        Route::get('/book-vehicule', 'vehicule')->name('vehicule.book');
        Route::get('/book-vehicule/resources', 'agendaResources')->name('vehicule.agenda.resources');
        Route::get('/book-vehicule/events', 'agendaEvents')->name('vehicule.agenda.events');
        Route::post('/book-vehicule/book', 'book')->name('booking.book');

        Route::post('/admin/guest/check', 'check')->name('guest.check');

        Route::get('booking/{id}/edit', 'edit')->name('booking.edit');
        Route::post('booking/{id}/update', 'update')->name('booking.update');
        Route::post('booking/{id}/delete', 'delete')->name('booking.delete');

    });


    // Vehicles
    Route::controller('ManageVehicleController')->name('vehicles.')->prefix('vehicles')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('image/remove/{id}/{image}', 'removeImage')->name('image.remove');

        //Vehicle Booking Log
        Route::get('booking/log', 'bookingLog')->name('booking.log');
        Route::get('booking/log/upcoming', 'upcomingBookingLog')->name('upcoming.booking.log');
        Route::get('booking/log/running', 'runningBookingLog')->name('running.booking.log');
        Route::get('booking/log/completed', 'completedBookingLog')->name('completed.booking.log');
        //User Vehicle Booking Log
        Route::get('booking/log/{id}', 'userBookingLog')->name('user.booking.log');
        Route::get('booking/log/{id}/upcoming', 'userUpcomingBookingLog')->name('user.booking.log.upcoming');
        Route::get('booking/log/{id}/running', 'userRunningBookingLog')->name('user.booking.log.running');
        Route::get('booking/log/{id}/completed', 'userCompletedBookingLog')->name('user.booking.log.completed');
    });

    Route::controller('DossierController')->name('dossiers.')->prefix('dossiers')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('delete/{id}', 'destroy')->name('delete');

        Route::get('{id}/details', [DossierController::class, 'details'])->name('details');

        // 🔹 Facture CRUD inside Dossier
        Route::prefix('{dossier}/factures')->name('factures.')->group(function () {
            Route::get('/', [FactureController::class, 'index'])->name('index');
            Route::get('create', [FactureController::class, 'create'])->name('create');
            Route::post('store', [FactureController::class, 'store'])->name('store');
            Route::get('{facture}/edit', [FactureController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [FactureController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [FactureController::class, 'destroy'])->name('destroy');
        });

        // New approval workflow routes
        Route::post('{dossier}/approve', 'approve')->name('approve');
        Route::post('{dossier}/reject', 'reject')->name('reject');
        Route::post('{dossier}/resubmit', 'resubmit')->name('resubmit');

        Route::post('dossiers/attachments/{id}', 'deleteAttachment')
            ->name('delete-attachment');

        Route::get('dossiers/attachments/{id}/download', 'downloadAttachment')
            ->name('download');

    });

    Route::controller(\App\Http\Controllers\Admin\FournisseurController::class)
        ->name('supplier.')
        ->prefix('supplier')
        ->group(function () {
            Route::get('index', 'index')->name('index');
            Route::post('store/{id?}', 'store')->name('store');
            Route::delete('{fournisseur}', 'destroy')->name('destroy');

        });

    Route::controller('DirectionController')
        ->name('direction.')
        ->prefix('direction')
        ->group(function () {
            Route::get('',  'index')->name('index');
            Route::post('/store/{id?}', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/delete/{id}', 'destroy')->name('destroy');

        });

    Route::controller('CompteController')
        ->name('compte.')
        ->prefix('compte')
        ->group(function () {
            Route::get('',  'index')->name('index');
            Route::post('/store/{id?}', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/delete/{id}', 'destroy')->name('destroy');

        });




    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->middleware(['admin','role:admin'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->middleware(['admin','role:admin'])->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });


    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('deposit')->name('deposit.')->group(function () {
        Route::get('all/{user_id?}', 'deposit')->name('list');
        Route::get('pending/{user_id?}', 'pending')->name('pending');
        Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
        Route::get('approved/{user_id?}', 'approved')->name('approved');
        Route::get('successful/{user_id?}', 'successful')->name('successful');
        Route::get('initiated/{user_id?}', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });

    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->middleware(['admin','role:admin'])->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update', 'systemUpdate')->name('update');
        Route::post('system-update', 'systemUpdateProcess')->name('update.process');
        Route::get('system-update/log', 'systemUpdateLog')->name('update.log');
    });

    Route::controller('GeneralSettingController')->middleware(['admin','role:admin'])->group(function () {

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');


        Route::get('sitemap', 'sitemap')->name('setting.sitemap');
        Route::post('sitemap', 'sitemapSubmit');


        Route::get('robot', 'robot')->name('setting.robot');
        Route::post('robot', 'robotSubmit');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit');

    });

    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->middleware(['admin','role:admin'])->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->middleware(['admin','role:admin'])->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

            Route::get('manage-seo/{id}', 'manageSeo')->name('manage.pages.seo');
            Route::post('manage-seo/{id}', 'manageSeoStore');
        });
    });

    Route::get('invoice/{trx}/view', [\App\Http\Controllers\Admin\InvoiceController::class, 'view'])
        ->name('admin.invoice.view')
        ->middleware('admin');

    Route::get('contract/{trx}/view', [\App\Http\Controllers\Admin\InvoiceController::class, 'viewContract'])
        ->name('admin.contract.view')
        ->middleware('admin');
});
