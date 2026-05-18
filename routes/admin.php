<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\ModulesDataController;
use App\Http\Controllers\Admin\WidgetPagesController;
use App\Http\Controllers\Admin\WidgetsController;
use App\Http\Controllers\Admin\WidgetDataController;
use App\Http\Controllers\Admin\ContactusController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\ImportController;
use App\Models\Hotels;
use App\Models\Flights;
use App\Models\User;
use App\Models\ModulesData;
use App\Mail\BookingStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AirportsController;
use App\Http\Controllers\Admin\AdminFlightBookingController;
use App\Http\Controllers\Admin\AdminFlightOrderController;
use App\Http\Controllers\Admin\CountriesController;
use App\Http\Controllers\Admin\StatesController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminTourBookingController;
use App\Http\Controllers\Admin\ContentTranslationController;
use App\Http\Controllers\Admin\WidgetTranslationController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        // Get the latest 5 hotel bookings
        $hotel_bookings = Hotels::latest()->take(5)->get();
        
        // Get the latest 5 flight orders
        $flight_orders = \App\Models\FlightBooking::latest()->take(5)->get();
        
        // Get the latest 5 tour bookings
        $tour_bookings = \App\Models\TourBooking::with(['tour', 'user'])->latest()->take(5)->get();
        
        // Fetch the total count of hotels, flights, tours, and users
        $total_hotels = ModulesData::where('module_id', 1)->where('status', 'active')->count();
        $total_flights = ModulesData::where('module_id', 3)->where('status', 'active')->count();
        $total_tours = ModulesData::where('module_id', 34)->where('status', 'active')->count();
        $total_users = User::count();

        // Get currency symbol for views
        $currencySymbol = \App\Helpers\CurrencyHelper::getSymbol();

        // Chart Data - Multiple time periods
        $chartData = [];
        
        // Generate data for different time periods (1, 3, 6, 12 months)
        $periods = [1, 3, 6, 12];
        
        foreach ($periods as $months) {
            $periodMonths = [];
            $periodHotelRevenue = [];
            $periodFlightRevenue = [];
            $periodTourRevenue = [];
            $periodTotalRevenue = [];
            
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $periodMonths[] = $date->format('M Y');
                
                // Hotel revenue for this month
                $hotelRev = Hotels::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('price');
                $periodHotelRevenue[] = $hotelRev;
                
                // Flight revenue for this month
                $flightRev = \App\Models\FlightBooking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount');
                $periodFlightRevenue[] = $flightRev;
                
                // Tour revenue for this month
                $tourRev = \App\Models\TourBooking::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount');
                $periodTourRevenue[] = $tourRev;
                
                $periodTotalRevenue[] = $hotelRev + $flightRev + $tourRev;
            }
            
            $chartData[$months] = [
                'months' => $periodMonths,
                'hotelRevenue' => $periodHotelRevenue,
                'flightRevenue' => $periodFlightRevenue,
                'tourRevenue' => $periodTourRevenue,
                'totalRevenue' => $periodTotalRevenue
            ];
        }
        
        // Default to 12 months for backward compatibility
        $months = $chartData[12]['months'];
        $hotelRevenue = $chartData[12]['hotelRevenue'];
        $flightRevenue = $chartData[12]['flightRevenue'];
        $tourRevenue = $chartData[12]['tourRevenue'];
        $totalRevenue = $chartData[12]['totalRevenue'];

        // Recent 7 days data for daily chart
        $dailyDates = [];
        $dailyHotelBookings = [];
        $dailyFlightBookings = [];
        $dailyTourBookings = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyDates[] = $date->format('M d');
            
            $dailyHotelBookings[] = Hotels::whereDate('created_at', $date->toDateString())->count();
            $dailyFlightBookings[] = \App\Models\FlightBooking::whereDate('created_at', $date->toDateString())->count();
            $dailyTourBookings[] = \App\Models\TourBooking::whereDate('created_at', $date->toDateString())->count();
        }

        // Top performing data
        $topHotels = \App\Models\ModulesData::where('module_id', 1)
            ->where('status', 'active')
            ->take(5)
            ->get();

        $topTours = \App\Models\ModulesData::where('module_id', 34)
            ->where('status', 'active')
            ->take(5)
            ->get();

        // Pass the data to the view
        return view('admin.dashboard', compact(
            'hotel_bookings', 'flight_orders', 'tour_bookings', 
            'total_hotels', 'total_flights', 'total_tours', 'total_users', 
            'currencySymbol', 'months', 'hotelRevenue', 'flightRevenue', 
            'tourRevenue', 'totalRevenue', 'dailyDates', 'dailyHotelBookings', 
            'dailyFlightBookings', 'dailyTourBookings', 'topHotels', 'topTours', 'chartData'
        ));
    })->name('dashboard');

    // Airport Management Routes
    Route::resource('airports', AirportsController::class);

    // Countries, States, Cities Management Routes
    Route::resource('countries', CountriesController::class);
    Route::resource('states', StatesController::class);
    Route::resource('cities', CitiesController::class);
    
    // AJAX routes for dependent dropdowns
    Route::get('/states/by-country/{countryId}', [StatesController::class, 'getStatesByCountry'])->name('states.by.country');
    Route::get('/cities/by-state/{stateId}', [CitiesController::class, 'getCitiesByState'])->name('cities.by.state');

    // Currency Settings Routes
    Route::get('/currency-settings', [SettingsController::class, 'index'])->name('currency-settings.index');
    Route::put('/currency-settings', [SettingsController::class, 'update'])->name('currency-settings.update');

    // Flight Order Routes
    Route::get('/flight-orders', function () {
        $flight_orders = \App\Models\FlightBooking::latest()->paginate(10);
        return view('admin.flight-orders.list', compact('flight_orders'));
    })->name('flight-orders');

    Route::get('/flight-order/{id}', [\App\Http\Controllers\Admin\AdminFlightOrderController::class, 'show'])
        ->name('flight-order');

    // Route::get('/flight-order/{id}/pdf', [\App\Http\Controllers\Admin\AdminFlightOrderController::class, 'downloadPdf'])
    //     ->name('flight-order.pdf');


    Route::get('/flight-order/{id}/pdf', [\App\Http\Controllers\Admin\AdminFlightOrderController::class, 'downloadPdf'])->name('flight-order.pdf');


    Route::get('/flight-order-change-status/{id}', function ($id) {
        $booking = \App\Models\FlightBooking::where('id', $id)->first();
        $booking->booking_status = request()->status_title;
        $booking->update();
        return response()->json(['message' => 'Booking status updated']);
    })->name('flight-order-change-status');

    // Tour Booking Management Routes
    Route::get('/tour-bookings', [AdminTourBookingController::class, 'index'])->name('tour-bookings.index');
    Route::get('/tour-bookings/export', [AdminTourBookingController::class, 'export'])->name('tour-bookings.export');
    Route::get('/tour-bookings/stats', [AdminTourBookingController::class, 'getStats'])->name('tour-bookings.stats');
    Route::get('/tour-bookings/{id}', [AdminTourBookingController::class, 'show'])->name('tour-bookings.show');
    Route::put('/tour-bookings/{id}/status', [AdminTourBookingController::class, 'updateStatus'])->name('tour-bookings.update-status');
});

Route::get('/hotel-bookings', function () {
    return view('admin.hotels.index');
})->name('hotel-bookings');

Route::get('/hotel-booking/{id}', function ($id) {
    $hotel = Hotels::where('id', $id)->first();
    $hotelM = App\Models\ModulesData::where('id', $hotel->hotel_id)->first();
    $user = App\Models\User::where('id', $hotel->user_id)->first();
    return view('admin.hotels.detail')->with('hotel',$hotel)->with('hotelM',$hotelM)->with('user',$user);
})->name('hotel-booking');

Route::get('/hotel-booking-change-status/{id}', function ($id, Request $request) {
    $hotel = Hotels::where('id', $id)->first();

    if (!$hotel) {
        return response()->json(['message' => 'Booking not found'], 404);
    }

    $hotel->booking_status = $request->status_title;
    $hotel->update();

    // Get user info
    $user = $hotel->user;

    $hotl = App\Models\ModulesData::where('id',$hotel->hotel_id)->first();

   // dd($user);

    // Email Send Karein
    Mail::to($user->email)->send(new BookingStatusMail($hotl, $user));

    return response()->json(['message' => 'Booking status updated and email sent']);
})->name('hotel-booking-change-status');

Route::get('/hotel-booking-change-status/{id}', function ($id) {
    $hotel = Hotels::where('id', $id)->first();
    $hotel->booking_status = request()->status_title;
    $hotel->update();
    // Get user info
    $user = $hotel->user;

    $hotl = App\Models\ModulesData::where('id',$hotel->hotel_id)->first();

   // dd($user);

    // Email Send Karein
    Mail::to($user->email)->send(new BookingStatusMail($hotl, $user, $hotel));

    return response()->json(['message' => 'Booking status updated and email sent']);
})->name('hotel-booking-change-status');

Route::resource('permissions', PermissionsController::class);

Route::resource('roles', RolesController::class);
Route::get('/delete-role/{id}', [RolesController::class,'destroy'])->name('roles.destroy');

Route::resource('users', CampusController::class);
Route::get('/delete-user/{id}', [CampusController::class,'destroy'])->name('users.destroy');

Route::get('/add-columns', [ModulesController::class,'add_columns'])->name('add_columns');
Route::get('/add-module-data-columns', [ModulesController::class,'add_module_data_columns'])->name('add_module_data_columns');


Route::get('/contact-us-messages', [ContactusController::class,'index'])->name('contact-us-messages');

Route::get('/contact-us-detail/{id}', [ContactusController::class,'detail'])->name('contact-us-detail');

Route::get('/filter-parties/{id}', [ModulesDataController::class,'filterParties'])->name('filter-parties');


Route::get('/menus', [MenusController::class,'index'])->name('menus');

Route::get('/add-menu', [MenusController::class,'add'])->name('menus.add');

Route::post('/store-menu', [MenusController::class,'store'])->name('menus.store');

Route::post('/update-menu', [MenusController::class,'update'])->name('menus.update');

Route::get('/edit-menu/{menu}', [MenusController::class,'edit'])->name('menus.edit');

Route::get('/delete-menu/{menu}', [MenusController::class,'destroy'])->name('menus.delete');

Route::post('/menus/post_index', [MenusController::class,'post_index'])->name('menus.post_index');

// Menu Translation Routes
Route::get('/menu-translations', [MenusController::class,'translations'])->name('menus.translations');
Route::post('/menu-translations/store', [MenusController::class,'storeTranslation'])->name('menus.translations.store');
Route::post('/menu-translations/update', [MenusController::class,'updateTranslation'])->name('menus.translations.update');
Route::get('/menu-translations/delete/{id}', [MenusController::class,'deleteTranslation'])->name('menus.translations.delete');

// Content Translation Routes
Route::get('/content-translations', [ContentTranslationController::class,'index'])->name('content.translations.index');
Route::get('/content-translations/{id}', [ContentTranslationController::class,'show'])->name('content.translations.show');
Route::post('/content-translations/store', [ContentTranslationController::class,'store'])->name('content.translations.store');
Route::post('/content-translations/update', [ContentTranslationController::class,'update'])->name('content.translations.update');
Route::get('/content-translations/delete/{id}', [ContentTranslationController::class,'destroy'])->name('content.translations.delete');
Route::post('/content-translations/bulk-translate', [ContentTranslationController::class,'bulkTranslate'])->name('content.translations.bulk');

// Widget Translation Routes
Route::get('/widget-translations', [WidgetTranslationController::class,'index'])->name('widget.translations.index');
Route::get('/widget-translations/{id}', [WidgetTranslationController::class,'show'])->name('widget.translations.show');
Route::post('/widget-translations/store', [WidgetTranslationController::class,'store'])->name('widget.translations.store');
Route::post('/widget-translations/update', [WidgetTranslationController::class,'update'])->name('widget.translations.update');
Route::get('/widget-translations/delete/{id}', [WidgetTranslationController::class,'destroy'])->name('widget.translations.delete');
Route::post('/widget-translations/bulk-translate', [WidgetTranslationController::class,'bulkTranslate'])->name('widget.translations.bulk');

// Widget Data Translation Routes
Route::post('/widget-data-translations/store', [WidgetTranslationController::class,'storeDataTranslation'])->name('widget.data.translations.store');
Route::post('/widget-data-translations/update', [WidgetTranslationController::class,'updateDataTranslation'])->name('widget.data.translations.update');
Route::get('/widget-data-translations/delete/{id}', [WidgetTranslationController::class,'destroyDataTranslation'])->name('widget.data.translations.delete');


Route::get('/widget-pages', [WidgetPagesController::class,'index'])->name('widget_pages');
Route::get('/add-widget-page', [WidgetPagesController::class,'add'])->name('widget_pages.add');
Route::post('/store-widget-page', [WidgetPagesController::class,'store'])->name('widget_pages.store');
Route::post('/update-widget-page', [WidgetPagesController::class,'update'])->name('widget_pages.update');
Route::get('/edit-widget-page/{widget_page}', [WidgetPagesController::class,'edit'])->name('widget_pages.edit');
Route::get('/delete-widget-page/{widget_page}', [WidgetPagesController::class,'destroy'])->name('widget_pages.delete');
Route::post('/import-contacts', [ImportController::class,'store'])->name('import.contacts');
Route::get('/widgets', [WidgetsController::class,'index'])->name('widgets');
Route::get('/add-widget', [WidgetsController::class,'add'])->name('widgets.add');
Route::post('/store-widget', [WidgetsController::class,'store'])->name('widgets.store');
Route::post('/update-widget', [WidgetsController::class,'update'])->name('widgets.update');
Route::get('/edit-widget/{widget}', [WidgetsController::class,'edit'])->name('widgets.edit');
Route::get('/delete-widget/{widget}', [WidgetsController::class,'destroy'])->name('widgets.delete');

Route::get('/widget-page/{page}', [WidgetDataController::class,'index'])->name('widgets_data');
Route::post('/store-widget-data/{id}', [WidgetDataController::class,'store'])->name('widget_data.store');
Route::post('/update-widget-page', [WidgetDataController::class,'update'])->name('widget_pages.update');
Route::get('/delete-widget-page/{widget_page}', [WidgetPagesController::class,'destroy'])->name('widget_pages.delete');



Route::get('/modules', [ModulesController::class,'index'])->name('modules');
Route::get('/add-module', [ModulesController::class,'add'])->name('modules.add');
Route::post('/store-module', [ModulesController::class,'store'])->name('modules.store');
Route::post('/update-module', [ModulesController::class,'update'])->name('modules.update');
Route::get('/edit-module/{module}', [ModulesController::class,'edit'])->name('modules.edit');
Route::get('/delete-module/{module}', [ModulesController::class,'destroy'])->name('modules.delete');

Route::post('/assign-contacts', [ModulesDataController::class,'assignContacts'])->name('assign-contacts');
Route::get('/delete-contact/{id}/{user}', [ModulesDataController::class,'deleteContacts'])->name('delete-contact');
Route::get('/{module}/delete/{id}', [ModulesDataController::class,'destroy'])->name('modules.data.delete');
Route::get('/delete-file/{id}/{field}', [ModulesDataController::class,'destroyFile'])->name('modules.data.delete.file');
Route::get('/download-files/{id}/{module}', [ModulesDataController::class,'downloadFiles'])->name('modules.data.download.files');
Route::get('/share-files/{id}/{module}', [ModulesDataController::class,'shareFiles'])->name('modules.data.share.files');
Route::get('/data-status/{module}/{status}', [ModulesDataController::class,'update_status']);
Route::get('/module-data', [ModulesDataController::class,'fetchModulesData'])->name('modules.data.fetch');
Route::get('/{module}', [ModulesDataController::class,'index'])->name('modules.data');
Route::get('/{module}/add', [ModulesDataController::class,'add'])->name('modules.data.add');
Route::post('/{module}/store', [ModulesDataController::class,'store'])->name('modules.data.store');
Route::post('/{module}/update', [ModulesDataController::class,'update'])->name('modules.data.update');
Route::get('/{module}/edit/{id}', [ModulesDataController::class,'edit'])->name('modules.data.edit');
    // Language Management Routes
    Route::group(['prefix' => 'languages'], function () {
        Route::get('/all', [App\Http\Controllers\Admin\LanguageController::class, 'index'])->name('languages.index');
        Route::get('/create', [App\Http\Controllers\Admin\LanguageController::class, 'create'])->name('languages.create');
        Route::post('/store', [App\Http\Controllers\Admin\LanguageController::class, 'store'])->name('languages.store');
        Route::get('/{language}/edit', [App\Http\Controllers\Admin\LanguageController::class, 'edit'])->name('languages.edit');
        Route::put('/{language}', [App\Http\Controllers\Admin\LanguageController::class, 'update'])->name('languages.update');
        Route::delete('/{language}', [App\Http\Controllers\Admin\LanguageController::class, 'destroy'])->name('languages.destroy');
        Route::get('/{language}/translations', [App\Http\Controllers\Admin\LanguageController::class, 'translations'])->name('languages.translations');
        Route::put('/{language}/translations', [App\Http\Controllers\Admin\LanguageController::class, 'updateTranslations'])->name('languages.updateTranslations');
        Route::post('/import', [App\Http\Controllers\Admin\LanguageController::class, 'import'])->name('languages.import');
        Route::get('/{language}/export/{group?}', [App\Http\Controllers\Admin\LanguageController::class, 'export'])->name('languages.export');

    });

Route::get('/{module}/preview/{id}', [ModulesDataController::class,'preview'])->name('modules.data.preview');

Route::get('/{module}', [ModulesDataController::class,'index'])->name('modules.data');




