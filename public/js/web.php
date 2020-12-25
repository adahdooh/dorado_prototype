<?php

 Route::get('/route-cache', function() {
     $exitCode = Artisan::call('route:cache');
     return 'Routes cache cleared';
 });

 Route::get('/config-cache', function() {
     $exitCode = Artisan::call('config:cache');
     return 'Config cache cleared';
 }); 

 Route::get('/clear-cache', function() {
     $exitCode = Artisan::call('cache:clear');
     return 'Application cache cleared';
 });
 

 Route::get('/view-clear', function() {
     $exitCode = Artisan::call('view:clear');
     return 'View cache cleared';
 });


Route::get('/', function () {
    $requests = DB::table('requests')
        ->where('is_confirmed','==',0)
        ->orderBy('id', 'desc')
        ->get();

    return view('home',['requests'=>$requests]);
})->middleware('auth');
Route::get('/clear-cache', function() {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('view:clear', ['--option' => 'foo']);
    // return what you want
});

Route::get('/home', function () {
    $requests = DB::table('requests')
        ->where('is_confirmed','==',0)
        ->orderBy('id', 'desc')
        ->get();
    return view('home',['requests'=>$requests]);
})->middleware('auth');
Route::get('/req', function () {
    $request_id= DB::table('requests')->max('request_id');

    return $request_id;
})->middleware('auth');

Auth::routes();


// users routes
Route::prefix('tasawaq/public')->group(function () {
    Route::get('/users','UserController@manage')->middleware('auth');
    Route::post('/users','UserController@save')->middleware('auth');
    Route::post('/users/update','UserController@update')->middleware('auth');
    Route::get('/users/{id}/delete','UserController@delete')->middleware('auth');
    Route::get('/users/{id}/edit','UserController@edit')->middleware('auth');

});


//students route
Route::get('/products','ProductsController@index')->middleware('auth');
Route::post('/products/add','ProductsController@save')->middleware('auth');
Route::get('/products/manage','ProductsController@manage')->middleware('auth');
Route::post('/products/{id}/update','ProductsController@update')->middleware('auth');
Route::get('/products/{id}/delete','ProductsController@recycle_bin')->middleware('auth');
Route::get('/getSubMain/{id}','ProductsController@getSubSections')->middleware('auth');
Route::get('/getBranches/{id}','ProductsController@getBranches')->middleware('auth');


Route::get('/restaurant','ProductsController@restaurants')->middleware('auth');
Route::get('/restaurant/manage','ProductsController@manage_restaurants')->middleware('auth');
Route::post('/restaurant/add','ProductsController@restaurantSave')->middleware('auth');
Route::post('/restaurant/{id}/update','ProductsController@restaurantUpdate')->middleware('auth');
Route::get('/restaurant/{id}/delete','ProductsController@restaurantRecycle_bin')->middleware('auth');

Route::get('/branches','ProductsController@branches')->middleware('auth');
Route::post('/branches/add','ProductsController@branchSave')->middleware('auth');
Route::get('/branches/manage','ProductsController@manage_branches')->middleware('auth');
Route::post('/branches/{id}/update','ProductsController@branchesUpdate')->middleware('auth');
Route::get('/branches/{id}/delete','ProductsController@branchesRecycle_bin')->middleware('auth');

Route::get('/app_users','ProductsController@app_users')->middleware('auth');

Route::get('/requests/{id}/certify','ProductsController@certifyRequests')->middleware('auth');
Route::get('/sse_notifications','ProductsController@sse_all_notifications')->middleware('auth');
Route::get('/allRequests','ProductsController@getAllConfirmedRequests')->middleware('auth');

Route::get('/add_region','ProductsController@addRegion')->middleware('auth');
Route::post('/region/add','ProductsController@saveRegion')->middleware('auth');
Route::get('/region/manage','ProductsController@manage_regions')->middleware('auth');
Route::post('/region/{id}/update','ProductsController@regionUpdate')->middleware('auth');
Route::get('/region/{id}/delete','ProductsController@regionRecycle_bin')->middleware('auth');

Route::post('/timesWork/update','ProductsController@timesWorkUpdate')->middleware('auth');
Route::get('/times_of_work','ProductsController@times_of_work')->middleware('auth');

//teachers route
Route::get('/teachers','TeacherController@index')->middleware('auth');
Route::get('/teachers/manage','TeacherController@manage')->middleware('auth');
Route::post('/teachers/add','TeacherController@save')->middleware('auth');
Route::post('/teachers/{id}/update','TeacherController@update')->middleware('auth');


//groups route
Route::get('/groups','GroupController@index')->middleware('auth');
Route::get('/groups/manage','GroupController@manage')->middleware('auth');
Route::get('/groups/{id}/schedules','GroupController@teacher_schedule')->middleware('auth');
Route::get('/groups/{id}/update','GroupController@update_group')->middleware('auth');
Route::get('/groups/{id}/students','GroupController@groups_students')->middleware('auth');
Route::post('/groups/add','GroupController@save')->middleware('auth');
Route::post('/groups/{id}/edit','GroupController@edit')->middleware('auth');
Route::post('/groups/payment/update','GroupController@update_payment')->middleware('auth');
Route::get('/groups/student/{student_id}/{group_id}/delete','GroupController@remove_student_group')->middleware('auth');
Route::get('/groups/{group_id}/delete','GroupController@delete_group')->middleware('auth');
Route::get('/groups/students/{id}/add','GroupController@add_group_students')->middleware('auth');
Route::post('/groups/students/add','GroupController@add_students_group')->middleware('auth');

//financial route
Route::get('/salary','FinancialController@index')->middleware('auth');
Route::get('/salary/{id}/statement','FinancialController@salary_statement')->middleware('auth');












