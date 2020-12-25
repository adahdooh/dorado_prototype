<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', 'Front\HomeController@index')->name('front.home');
// Route::get('/submit', 'Front\HomeController@submit_form')->name('front.submit');
// Route::get('/submit/dietitian', 'Front\HomeController@submit_form_dietitian')->name('front.submit.dietitian');
// //Route::get('/submit/patient', 'Front\HomeController@submit_form_patient')->name('front.submit.patient');
// Route::post('/submit', 'Front\HomeController@register_store')->name('register.store');
// Route::post('/submit/dietitian', 'Front\HomeController@register_dietitian_store')->name('register.dietitian.store');
// //Route::post('/submit/patient', 'Front\HomeController@register_patient_store')->name('register.patient.store');

// Route::get('/login/patient', 'Auth\LoginController@showPatientLoginForm')->name('login.patient');
// Route::post('/login/patient', 'Auth\LoginController@PatientLogin')->name('login.patient.show');
// Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin.show');
// Route::post('/login/admin', 'Auth\LoginController@adminLogin')->name('login.admin.post');

// Route::get('/conditions/dietitian', 'Front\HomeController@our_conditions')->name('our.conditions.dietitian');
// Route::get('/conditions/patient', 'Front\HomeController@our_conditions')->name('our.conditions.patient');

// Auth::routes();

// // private routes
// Route::middleware('auth:patient')->group(function () {
//     Route::prefix('patient')->group(function () {
//         Route::get('/home', 'Front\HomeController@patient')->name('patient.show');
//         Route::post('/pay', 'Front\HomeController@sendPayment')->name('patient.pay');
//         Route::post('/pay/response', 'Front\HomeController@sendPaymentResponse')->name('patient.pay.response');
//     });
// });

// // private routes
// Route::middleware('auth:admin')->group(function () {
//     Route::prefix('admin')->group(function () {
//         //profile
//         Route::get('/profile', 'Front\HomeController@profile')->name('profile.show');
//         Route::post('/profile', 'Front\HomeController@update_profile')->name('profile.update');

//         //home
//         Route::get('/home', 'Front\HomeController@dashboard')->name('home');

//         //users
//         Route::get('/user/manage', 'Front\HomeController@all_applications')->name('user.manage');
//         Route::get('/user/manage/dietitians', 'Front\HomeController@manage_all_dietitians')->name('user.manage.dietitians');
//         Route::post('/user/update', 'Front\HomeController@update_application')->name('user.update');
//         Route::get('/user/delete/{id}', 'Front\HomeController@delete_application')->name('user.delete');
//         Route::get('/user/accept/{id}', 'Front\HomeController@accept_user')->name('user.accept');
//         Route::get('/user/reject/{id}', 'Front\HomeController@reject_user')->name('user.reject');
//         Route::get('/user/accept/joining/{id}', 'Front\HomeController@accept_joining_user')->name('user.accept.joining');
//         Route::get('/user/reject/joining/{id}', 'Front\HomeController@reject_joining_user')->name('user.reject.joining');

//         //contact us
//         Route::get('/contact', 'Front\ContactController@index')->name('contact');
//         Route::post('/contact', 'Front\ContactController@update')->name('contact.update');

//         //partner
//         Route::get('/partner', 'Front\PartnerController@index')->name('partner.manage');
//         Route::get('/partner/create', 'Front\PartnerController@create')->name('partner.create');
//         Route::post('/partner', 'Front\PartnerController@store')->name('partner');
//         Route::post('/partner/{id}', 'Front\PartnerController@update')->name('partner.update');
//         Route::get('/partner/{id}/delete', 'Front\PartnerController@destroy')->name('partner.delete');

//         //social media
//         Route::get('/social_media', 'Front\SocialMediaController@index')->name('social_media.manage');
//         Route::get('/social_media/create', 'Front\SocialMediaController@create')->name('social_media.create');
//         Route::post('/social_media', 'Front\SocialMediaController@store')->name('social_media');
//         Route::post('/social_media/{id}', 'Front\SocialMediaController@update')->name('social_media.update');
//         Route::get('/social_media/{id}/delete', 'Front\SocialMediaController@destroy')->name('social_media.delete');
//     });
// });

