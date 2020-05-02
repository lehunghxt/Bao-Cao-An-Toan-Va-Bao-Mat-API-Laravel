<?php

use Illuminate\Http\Request;

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


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'UserController@login');
Route::post('signup', 'UserController@signup');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'UserController@logout');
    Route::get('user', 'UserController@user');

    Route::post('changePass', 'AdminController@changePassword');

    Route::post('add-cate', 'CategoryController@addCategory');
	Route::post('edit-cate/{id}', 'CategoryController@editCategory');
	Route::get('view-cate', 'CategoryController@viewCategory');
	Route::get('view-cateID/{id}', 'CategoryController@viewCategoryID');
	Route::delete('delete-cate/{id}', 'CategoryController@deleteCategory');

	Route::post('add-user', 'AdminController@addUser');
	Route::get('view-user', 'AdminController@viewUser');
	Route::get('view-userID/{id}', 'AdminController@viewUserID');
	Route::post('edit-user/{id}', 'AdminController@editUser');
	Route::delete('delete-user/{id}', 'AdminController@deleteUser');

	Route::post('add-post', 'PostController@addPost');
	Route::get('view-post', 'PostController@viewPost');
	Route::get('view-postID/{id}', 'PostController@viewPostID');
	Route::post('edit-post/{id}', 'PostController@editPost');
	Route::delete('delete-post/{id}', 'PostController@deletePost');
});

