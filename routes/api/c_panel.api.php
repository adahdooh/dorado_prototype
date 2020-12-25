<?php

use App\Models\Meal\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group(['middleware' => ['cors']], function () {
	
//     Route::get('xx', function (){

//         $meal_id = explode('-', request('id'))[0];
//         $today = Meal::find($meal_id);

//         $data = $today->meals_today;

//         $indx = 0;
//         foreach($data as $meal){
//             if ($meal["id"] == request('id')){
//            //     $data[$indx]["rate"] = $request->get('rate');
//            //     $data[$indx]["feedback"] = $request->get('rate');
//             }
//             $indx++;
//         }

//         $today->meals_today = json_encode($data);
//         $today->update();

//     });

//     // public routes
//     Route::get('/not_authenticated', 'Api\AuthController@not_authenticated')->name('not_authenticated');
//     Route::post('/login', 'Api\AuthController@login_web')->name('login.web');

//     // private routes
//     Route::middleware('auth:api')->group(function () {
//         Route::get('/logout', 'Api\AuthController@logout')->name('logout.api');

//         // users
//         Route::get('/users/all', 'Api\UserController@getUsersAll')->name('all.users');
//         Route::get('/users/patient/all', 'Api\UserController@getPatientAll')->name('all.patients');
//         Route::get('/users/dietitian/all', 'Api\UserController@getDietitianAll')->name('all.dietitians');
//         Route::get('/users/patient/of/dietitian/all', 'Api\UserController@getPatientForDietitianAll')->name('all.patients.dietitians');
//         Route::get('/user/profile', 'Api\UserController@getUserProfile')->name('user.profile');
//         Route::post('/user/subscription', 'Api\UserController@setSubscriptionDates')->name('user.profile');
//         Route::post('/user/completion/rate', 'Api\UserController@setCompletionRate')->name('user.profile');
//         Route::post('/user/assign/patient', 'Api\UserController@setPatientDietitian')->name('user.profile');
//         Route::post('/user/change/password', 'Api\UserController@changePasswordByUser')->name('user.profile');
//         Route::post('/user/change/block/unblock', 'Api\UserController@ChangeUserBlockUnblock')->name('user.profile');
//         Route::post('/user/admin', 'Api\UserController@createUserAsAdmin')->name('user.store.admins');
        
//         Route::post('/user/{id}/education_material', 'Api\EducationMaterialController@uploadEducationalMaterialFile')->name('user.education.upload');
//         Route::get('/user/{id}/education_material/all', 'Api\EducationMaterialController@index_user')->name('user.education.all.user');

// 		Route::get('/appointment/{patient_id}', 'Api\AppointmentController@getAppointmentsByPatientId')->name('appointment.patient.all');

//         Route::post('/appointment/{patient_id}', 'Api\AppointmentController@patientReserveAppointmentById')->name('appointment1111.store');
		
// 		Route::get('/patient/{patient_id}/medicine/items/list', 'Api\PatientMedicineController@getMedicinesListById')->name('get.li11st.medicine2');


//         // notification
//         Route::post('/notification', 'Api\NotificationController@store')->name('notification.store');
//         Route::post('/notification/by/patient', 'Api\NotificationController@storeNotificationDietitian')->name('notification.store');
// 		Route::post('/notification/patient/{id}', 'Api\NotificationController@pushNotification')->name('notification.push');

//         // report
//         Route::post('/report', 'Api\ReportController@store')->name('report.store');
//         Route::get('/report/categories/all', 'Api\ReportController@getReportCategories')->name('get.all.categories');
//         Route::get('/report/by/user', 'Api\ReportController@getReportAllByUser')->name('report.all.user');
//         Route::get('/report/by/user/last', 'Api\ReportController@getReportLastThreeByUser')->name('report.all.user');
//         Route::get('/report/{id}', 'Api\ReportController@show')->name('report.all.user');
//         Route::delete('/report/{id}', 'Api\ReportController@destroy')->name('report.destroy');

//         // goals
//         Route::post('/goal', 'Api\GoalController@store')->name('goal.store');
//         Route::get('/goal/by/user', 'Api\GoalController@getReportAllByUser')->name('goal.all.user');
//         Route::get('/goal/by/user/last', 'Api\GoalController@getReportLastThreeByUser')->name('goal.all.user');
//         Route::get('/goal/{id}', 'Api\GoalController@show')->name('goal.all.user');
//         Route::delete('/goal/{id}', 'Api\GoalController@destroy')->name('goal.destroy');

// 		// call
//         Route::post('/call', 'Api\CallController@store')->name('call.store');
//         Route::get('/call/{id}', 'Api\CallController@show')->name('call.all.user');
		
// 		Route::get('/user/{id}/start_call', 'Api\CallController@start_call')->name('start_call.user');

//         // Route::delete('/call/{id}', 'Api\CallController@destroy')->name('call.destroy');

		
//         // appointment
//         Route::post('/appointment', 'Api\AppointmentController@store')->name('appointment.store');
//         Route::get('/appointment', 'Api\AppointmentController@getAppointments')->name('appointment.all');
//         Route::delete('/appointment/{id}', 'Api\AppointmentController@destroy')->name('appointment.destroy');
//         Route::get('/myAppointment', 'Api\AppointmentController@getMyAppointments')->name('appointment.my.all');

		
// 		//analyze
//         Route::get('/user/{user_id}/analyze', 'Api\AnalyzeController@getPatientAnalyze')->name('get.patient.analyze');
// 		Route::get('/user/{id}/medicine', 'Api\PatientMedicineController@getPatientMedicineByUserId')->name('get.list.medicine');
//         Route::get('/user/{id}/waist_hip/chart/year', 'Api\PatientBodyController@getPatientWaistHipChartByUserId')->name('year.waist_hip');

				
//         // advice
//         Route::post('/advice', 'Api\AdviceController@store')->name('advice.store');
//         Route::post('/advice/{id}', 'Api\AdviceController@update')->name('advice.store');
//         Route::get('/advice/all', 'Api\AdviceController@getAdviceAll')->name('advice.all');
//         Route::delete('/advice/{id}', 'Api\AdviceController@destroy')->name('advice.destroy');
//         Route::get('/advice/{id}', 'Api\AdviceController@show')->name('advice.destroy');

//         Route::post('/user', 'Api\UserController@store')->name('store.dietitian');
//         Route::put('/user', 'Api\UserController@update')->name('update.user');
//         Route::delete('/user/{id}', 'Api\UserController@destroy')->name('destroy.user');
//         Route::patch('/user', 'Api\UserController@userNotificationChange')->name('notification.update.user');
//         Route::patch('/user/password', 'Api\UserController@userPasswordChange')->name('password.update.user');
//         Route::post('/user/image', 'Api\UserController@userImageChange')->name('image.update.user');
//         Route::post('/user/completion_rate/change', 'Api\UserController@ChangeUserCompletionRate')->name('completion_rate.change.user');

//         // tickets
//         Route::get('/ticket/all', 'Api\TicketController@getTicketsAll')->name('get.all.ticket');
//         Route::get('/ticket/dietitian/all', 'Api\TicketController@getTicketsAllByDietitian')->name('get.all.ticket');
//         Route::get('/ticket/replays', 'Api\TicketController@TicketReplays')->name('get.replays.ticket');
//         Route::post('/ticket/replay', 'Api\TicketController@TicketNewReplay')->name('store.replay.ticket');
//         Route::post('/ticket/status/update', 'Api\TicketController@TicketStatusChange')->name('status.update.ticket');


//         // meals
//         Route::post('/meal/{id}/rate', 'Api\MealController@rate')->name('store.rate');

//         Route::post('/meal', 'Api\MealController@store')->name('store.meal');
//         Route::post('/meal/properties', 'Api\MealController@updateMealProperties')->name('store.meal');
//         Route::post('/meal/notes/update', 'Api\MealController@updateMealNotes')->name('store.meal');
//         Route::get('/meal/daily', 'Api\MealController@MealsAllDaily')->name('get.all.meal');
//         Route::get('/meal/user/all', 'Api\MealController@getMealsByUser')->name('get.user.meal');
//         Route::get('/meal/user/get/all', 'Api\MealController@getMealsByUser')->name('get.user.meal');
//         Route::post('/meal/user/today/all', 'Api\MealController@getMealsByUserToday')->name('get.user.meal');
//         Route::get('/meal/info', 'Api\MealController@getMealInfoById')->name('get.info.meal');

//         // activities
//         Route::post('/activity', 'Api\ActivityController@store')->name('store.activity');
//         Route::get('/activity/daily', 'Api\ActivityController@ActivitiesAllDaily')->name('get.all.activity');

//         // waters
//         Route::post('/water', 'Api\WaterController@store')->name('store.water');
//         Route::get('/water/daily', 'Api\WaterController@WaterAllDaily')->name('get.all.water');

//         // medicine
//         Route::post('/medicine', 'Api\PatientMedicineController@store')->name('store.medicine');
//         Route::get('/medicine/all', 'Api\PatientMedicineController@getMedicinesAll')->name('get.all.medicine');

//         // educational material
//         Route::post('/materials', 'Api\EducationMaterialController@store')->name('add.materials');
//         Route::get('/materials', 'Api\EducationMaterialController@index')->name('get.all.materials');
//         Route::delete('/materials/{id}', 'Api\EducationMaterialController@destroy')->name('materials.destroy');

//         // diabetes
//         Route::post('/diabetes/upload/cgm', 'Api\DiabetesController@uploadCgmFile')->name('store.diabetes');
//         Route::get('/diabetes/user/all', 'Api\DiabetesController@getDiabetesAllByUser')->name('get.all.diabetes');
//         Route::get('/diabetes/user/today/all', 'Api\DiabetesController@getDiabetesAllByUserToday')->name('get.all.diabetes');
        
// 		Route::get('/diabetes/user/chart/today', 'Api\DiabetesController@getDiabetesChartTodayByUser')->name('week.diabetes');
//         Route::get('/diabetes/user/chart/week', 'Api\DiabetesController@getDiabetesChartWeekByUser')->name('week.diabetes');
//         Route::get('/diabetes/user/chart/month', 'Api\DiabetesController@getDiabetesChartMonthByUser')->name('month.diabetes');
        
		
// 		Route::get('/diabetes/user/chart/year', 'Api\DiabetesController@getDiabetesChartYearByUser')->name('year.diabetes');
//         Route::get('/diabetes/user/chart/all', 'Api\DiabetesController@getDiabetesChartAllUser')->name('all.diabetes');

//         // body
//         Route::post('/body/update', 'Api\PatientBodyController@update')->name('update.body.patient');
//         Route::get('/body/user/all', 'Api\PatientBodyController@getPatientBodyAllByUser')->name('all.body');
        
// 		Route::get('/body/user/chart/week', 'Api\PatientBodyController@getPatientBodyChartWeekByUser')->name('week.body');
//         Route::get('/body/user/chart/month', 'Api\PatientBodyController@getPatientBodyChartMonthByUser')->name('month.body');
//         Route::get('/body/user/chart/year', 'Api\PatientBodyController@getPatientBodyChartYearByUser')->name('year.body');
        
// 		Route::get('/body/user/chart/yearly', 'Api\PatientBodyController@getPatientBodyYearlyByUser')->name('year.body');
//         Route::get('/body/user/last', 'Api\PatientBodyController@getPatientBodyLastAddedByUser')->name('year.body');
//         Route::get('/body/user/chart', 'Api\PatientBodyController@getPatientBodyChart')->name('year.body');

//         //search
//         Route::post('/user/public/search', 'Api\DiabetesController@getDiabetesMealsMedicineByDate')->name('year.body');
			
//         // medicine
//         Route::get('/medicine/{id}', 'Api\PatientMedicineController@getPatientMedicineById')->name('get.list.medicine');
//         Route::get('/medicine/item/{id}', 'Api\PatientMedicineController@getMedicineItemById')->name('get.list.medicine');
      
// 		 // analyze
//         Route::get('/analyze/{user_id}', 'Api\AnalyzeController@getPatientAnalyze')->name('get.patient.analyze');
		
// 	//	Route::post('/user/{id}/searchAll', 'Api\DiabetesController@getDiabetesMealsMedicine')->name('year.body.all');
		
//      });

// });