<?php

Route::view('/', 'welcome');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', '2fa', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Services
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::post('services/media', 'ServicesController@storeMedia')->name('services.storeMedia');
    Route::post('services/ckmedia', 'ServicesController@storeCKEditorImages')->name('services.storeCKEditorImages');
    Route::resource('services', 'ServicesController');

    // Animal
    Route::delete('animals/destroy', 'AnimalController@massDestroy')->name('animals.massDestroy');
    Route::resource('animals', 'AnimalController');

    // Pets
    Route::delete('pets/destroy', 'PetsController@massDestroy')->name('pets.massDestroy');
    Route::post('pets/media', 'PetsController@storeMedia')->name('pets.storeMedia');
    Route::post('pets/ckmedia', 'PetsController@storeCKEditorImages')->name('pets.storeCKEditorImages');
    Route::resource('pets', 'PetsController');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::get('user-alerts/read', 'UserAlertsController@read');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    
    // Pet Reviews
    Route::delete('pet-reviews/destroy', 'PetReviewsController@massDestroy')->name('pet-reviews.massDestroy');
    Route::resource('pet-reviews', 'PetReviewsController');

    // Credits
    Route::delete('credits/destroy', 'CreditsController@massDestroy')->name('credits.massDestroy');
    Route::resource('credits', 'CreditsController');

    // Service Requests
    Route::delete('service-requests/destroy', 'ServiceRequestsController@massDestroy')->name('service-requests.massDestroy');
    Route::post('service-requests/media', 'ServiceRequestsController@storeMedia')->name('service-requests.storeMedia');
    Route::post('service-requests/ckmedia', 'ServiceRequestsController@storeCKEditorImages')->name('service-requests.storeCKEditorImages');
    Route::resource('service-requests', 'ServiceRequestsController');

    // Bookings
    Route::delete('bookings/destroy', 'BookingsController@massDestroy')->name('bookings.massDestroy');
    Route::resource('bookings', 'BookingsController');

    // Reviews
    Route::delete('reviews/destroy', 'ReviewsController@massDestroy')->name('reviews.massDestroy');
    Route::resource('reviews', 'ReviewsController');

    // Availability
    Route::delete('availabilities/destroy', 'AvailabilityController@massDestroy')->name('availabilities.massDestroy');
    Route::post('availabilities/media', 'AvailabilityController@storeMedia')->name('availabilities.storeMedia');
    Route::post('availabilities/ckmedia', 'AvailabilityController@storeCKEditorImages')->name('availabilities.storeCKEditorImages');
    Route::resource('availabilities', 'AvailabilityController');

    // Photo Updates
    Route::delete('photo-updates/destroy', 'PhotoUpdatesController@massDestroy')->name('photo-updates.massDestroy');
    Route::post('photo-updates/media', 'PhotoUpdatesController@storeMedia')->name('photo-updates.storeMedia');
    Route::post('photo-updates/ckmedia', 'PhotoUpdatesController@storeCKEditorImages')->name('photo-updates.storeCKEditorImages');
    Route::resource('photo-updates', 'PhotoUpdatesController');

    // Cashout
    Route::delete('cashouts/destroy', 'CashoutController@massDestroy')->name('cashouts.massDestroy');
    Route::resource('cashouts', 'CashoutController');

    Route::get('messenger', 'MessengerController@index')->name('messenger.index');
    Route::get('messenger/create', 'MessengerController@createTopic')->name('messenger.createTopic');
    Route::post('messenger', 'MessengerController@storeTopic')->name('messenger.storeTopic');
    Route::get('messenger/inbox', 'MessengerController@showInbox')->name('messenger.showInbox');
    Route::get('messenger/outbox', 'MessengerController@showOutbox')->name('messenger.showOutbox');
    Route::get('messenger/{topic}', 'MessengerController@showMessages')->name('messenger.showMessages');
    Route::delete('messenger/{topic}', 'MessengerController@destroyTopic')->name('messenger.destroyTopic');
    Route::post('messenger/{topic}/reply', 'MessengerController@replyToTopic')->name('messenger.reply');
    Route::get('messenger/{topic}/reply', 'MessengerController@showReply')->name('messenger.showReply');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
        Route::post('profile/two-factor', 'ChangePasswordController@toggleTwoFactor')->name('password.toggleTwoFactor');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth', '2fa']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Services
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::post('services/media', 'ServicesController@storeMedia')->name('services.storeMedia');
    Route::post('services/ckmedia', 'ServicesController@storeCKEditorImages')->name('services.storeCKEditorImages');
    Route::resource('services', 'ServicesController');

    // Animal
    Route::delete('animals/destroy', 'AnimalController@massDestroy')->name('animals.massDestroy');
    Route::resource('animals', 'AnimalController');

    // Pets
    Route::delete('pets/destroy', 'PetsController@massDestroy')->name('pets.massDestroy');
    Route::post('pets/media', 'PetsController@storeMedia')->name('pets.storeMedia');
    Route::post('pets/ckmedia', 'PetsController@storeCKEditorImages')->name('pets.storeCKEditorImages');
    Route::resource('pets', 'PetsController')->except('show');

    // User Alerts
    Route::delete('user-alerts/destroy', 'UserAlertsController@massDestroy')->name('user-alerts.massDestroy');
    Route::resource('user-alerts', 'UserAlertsController', ['except' => ['edit', 'update']]);

    // Credits
    Route::delete('credits/destroy', 'CreditsController@massDestroy')->name('credits.massDestroy');
    Route::resource('credits', 'CreditsController');

    // Service Requests
    Route::delete('service-requests/destroy', 'ServiceRequestsController@massDestroy')->name('service-requests.massDestroy');
    Route::post('service-requests/media', 'ServiceRequestsController@storeMedia')->name('service-requests.storeMedia');
    Route::post('service-requests/ckmedia', 'ServiceRequestsController@storeCKEditorImages')->name('service-requests.storeCKEditorImages');
    Route::resource('service-requests', 'ServiceRequestsController');


    // Bookings
    Route::delete('bookings/destroy', 'BookingsController@massDestroy')->name('bookings.massDestroy');
    Route::resource('bookings', 'BookingsController');
    Route::post('bookings/decline', 'BookingsController@decline')->name('bookings.decline');
    Route::post('bookings/completed', 'BookingsController@completed')->name('bookings.completed');


    // Pet Reviews
    Route::delete('pet-reviews/destroy', 'PetReviewsController@massDestroy')->name('pet-reviews.massDestroy');
    Route::resource('pet-reviews', 'PetReviewsController');


    // Reviews
    Route::delete('reviews/destroy', 'ReviewsController@massDestroy')->name('reviews.massDestroy');
    Route::resource('reviews', 'ReviewsController');

    // Availability
    Route::delete('availabilities/destroy', 'AvailabilityController@massDestroy')->name('availabilities.massDestroy');
    Route::post('availabilities/media', 'AvailabilityController@storeMedia')->name('availabilities.storeMedia');
    Route::post('availabilities/ckmedia', 'AvailabilityController@storeCKEditorImages')->name('availabilities.storeCKEditorImages');
    Route::resource('availabilities', 'AvailabilityController');

    // Photo Updates
    Route::delete('photo-updates/destroy', 'PhotoUpdatesController@massDestroy')->name('photo-updates.massDestroy');
    Route::post('photo-updates/media', 'PhotoUpdatesController@storeMedia')->name('photo-updates.storeMedia');
    Route::post('photo-updates/ckmedia', 'PhotoUpdatesController@storeCKEditorImages')->name('photo-updates.storeCKEditorImages');
    Route::resource('photo-updates', 'PhotoUpdatesController');

    // Cashout
    Route::delete('cashouts/destroy', 'CashoutController@massDestroy')->name('cashouts.massDestroy');
    Route::resource('cashouts', 'CashoutController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
    Route::post('profile/toggle-two-factor', 'ProfileController@toggleTwoFactor')->name('profile.toggle-two-factor');
});
Route::group(['namespace' => 'Auth', 'middleware' => ['auth', '2fa']], function () {
    // Two Factor Authentication
    if (file_exists(app_path('Http/Controllers/Auth/TwoFactorController.php'))) {
        Route::get('two-factor', 'TwoFactorController@show')->name('twoFactor.show');
        Route::post('two-factor', 'TwoFactorController@check')->name('twoFactor.check');
        Route::get('two-factor/resend', 'TwoFactorController@resend')->name('twoFactor.resend');
    }
});
