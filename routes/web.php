<?php

use App\Http\Controllers\LanguageController;
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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', 'AuthController@logout')->name('logout');
    Route::get('/active', 'HomeController@active')->name('active')->middleware('check_user_already_active');
    Route::post('/active', 'HomeController@activePost')->name('active_post');
    Route::group(['prefix' => 'dashboard', 'middleware' => ['check_active_user', 'check_active_company']], function () {
        Route::group(['prefix' => 'admin', 'middleware' => ['check_user_admin']], function () {
            Route::get('/', 'HomeController@index')->name('home');

            Route::group(['prefix' => 'card'], function () {
                Route::post('/', 'CreditCardController@save')->name('save_credit_card');
                Route::post('/update', 'CreditCardController@update')->name('update_credit_card');
                Route::post('/cancel', 'CreditCardController@delete')->name('delete_plan');
                Route::get('/updatePlanStatus/{companyId}', 'CreditCardController@updatePlanStatus');
            });

            Route::group(['prefix' => 'company'], function () {
                Route::post('/', 'CompanyController@update')->name('update_company');
            });

            Route::delete('/logo', 'CompanyController@deleteLogo')->name('delete_logo');
            Route::delete('/background', 'CompanyController@deleteBackground')->name('delete_background');

            Route::group(['prefix' => 'users'], function () {
                Route::get('/', 'UserController@index')->name('users');
                Route::get('/search', 'UserController@index')->name('search_users');
                Route::post('/search', 'UserController@search');
                Route::post('/', 'UserController@create')->name('make_user');
                Route::post('/account', 'UserController@updateAccount')->name('update_my_user');
                Route::delete('/', 'UserController@delete')->name('delete_user');
                Route::delete('/delete_photo', 'UserController@deletePhoto')->name('delete_user_photo');
            });

            Route::group(['prefix' => 'campaigns'], function () {
                Route::get('/', 'CampaignController@index')->name('campaigns');
                Route::get('/search', 'CampaignController@index')->name('search_campaigns');
                Route::post('/search', 'CampaignController@search');
                Route::post('/', 'CampaignController@create')->name('make_campaign');
                Route::delete('/', 'CampaignController@delete')->name('delete_campaign');
                Route::delete('/delete_background', 'CampaignController@deletePhoto')->name('delete_background_campaign');
                Route::post('/users', 'CampaignController@addUsers')->name('add_users_campaign');
            });

            Route::group(['prefix' => 'albums'], function () {
                Route::get('/', 'AlbumController@index')->name('albums');
                Route::get('/search', 'AlbumController@index')->name('search_albums');
                Route::post('/search', 'AlbumController@search');
                Route::post('/', 'AlbumController@create')->name('make_album');
                Route::delete('/', 'AlbumController@delete')->name('delete_album');
                Route::delete('/delete_background', 'AlbumController@deletePhoto')->name('delete_background_album');
                Route::post('/users', 'AlbumController@addPhotos')->name('add_photo_album');
                Route::delete('/delete_album_photo', 'AlbumController@deletePhotoAlbum')->name('delete_album_photo');
            });
        });

        Route::get('/', 'HomeController@indexUser')->name('home_user');

        Route::group(['prefix' => 'account'], function () {
            Route::get('/', 'HomeController@myAccount')->name('my_account');
        });

        Route::group(['prefix' => 'scraps'], function () {
            Route::get('/', 'ScrapController@index')->name('messages');
            Route::delete('/', 'ScrapController@delete')->name('delete_message');
        });

        // Route::group(['prefix' => 'albums_videos'], function () {
        //     Route::get('/', 'AlbumVideoController@index')->name('album_videos');
        //     Route::get('/search', 'AlbumVideoController@index')->name('search_album_video_name');
        //     Route::post('/search', 'AlbumVideoController@search');
        //     Route::post('/', 'AlbumVideoController@create')->name('make_album_videos');
        //     Route::delete('/', 'AlbumVideoController@delete')->name('delete_album_videos');
        //     Route::delete('/delete_background', 'AlbumVideoController@deletePhoto')->name('delete_background_album_videos');
        //     Route::post('/videos', 'AlbumVideoController@addVideos')->name('add_video_album');
        // });
    });
});

Route::group(['prefix' => 'scraps'], function () {
    Route::post('/create', 'ScrapController@create')->name('make_message');
    Route::get('/{company}', 'ScrapController@indexAnon')->name('messages_anonymous');
});

Route::group(['prefix' => 'messages'], function () {
    Route::get('/', 'ScrapController@list')->name('list_messages');
});

Route::get('/', 'PageController@index');
Route::post('payment', 'PaymentController@index');
Route::get('screen/{name}', 'ScreenController@page')->name('screen');
Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Auth::routes();
