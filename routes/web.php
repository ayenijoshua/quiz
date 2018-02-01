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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

session_start();
Route::get('/', function(Request $request){
   return view('index');
});
 

Route::get('auth', [
    'as'=>'auth', 
    'uses'=>'Auth\LoginController@redirectToProvider'
 ]);
Route::get('/auth/facebook/callback', [
    'as'=>'auth/facebook/callback', 
    'uses'=>'Auth\LoginController@handleProviderCallback'
 ]);

Route::get('panel', [
    'as'=>'panel', 
    'uses'=>'UserController@UserPanel'
 ]);


Route::post('manage-user', function(Request $request){
   
        $action = $request->post('action');
   $controller = new \App\Http\Controllers\UserController();
  
   if($action=='signup'){
       $controller->processForm('signup');
   }
   
   if($action=='img-upload'){
       $controller->processForm('img-upload');
   }
   
   if($action=='signin'){
       //print 'ki';
      $controller->processUserLogin(); 
   }
   
   if($action=='logout'){
    $controller->processUserLogout();    
   }
  
   
   if($action=='change-pass'){
       
   }
       
});


