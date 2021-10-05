<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('blog');
});

use App\Http\Controllers\BlogController;
//Route::get('/blogs/{id}', 'BlogController@index');
Route::get('/blogs/{id}',[BlogController::class, 'getBlog']);

Route::post('/blogs/update/{id}', 'BlogController@updateBlog');
Route::delete('/blogs/delete/{id}', 'BlogController@deleteBlog');