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

/*// http://{ホスト名}/admin/form に GET でアクセスすると、AdminBlogController の form メソッドを実行するという意味
// name メソッドでエイリアスをつけることができる
Route::get('admin/form', 'AdminBlogController@form')->name('admin_form');

// http://{ホスト名}/admin/post に POST でアクセスすると、AdminBlogController の post メソッドを実行する
Route::post('admin/post', 'AdminBlogController@post')->name('admin_post');

// URL中の値を取り出したいときは、ルートパラメータを利用する。{}で囲んだ部分を取り出すことができる
// パラメータ名末尾の `?` は、任意パラメータを表すもので、このパラメータはあっても無くても良い、ということになる
Route::get('admin/form/{article_id?}', 'AdminBlogController@form')->name('admin_form');
Route::post('admin/post', 'AdminBlogController@post')->name('admin_post');

// http://{ホスト名}/admin/post に POST でアクセスすると、AdminBlogController の delete メソッドを実行する
Route::post('admin/delete', 'AdminBlogController@delete')->name('admin_delete');

// http://{ホスト名}/admin/post に list でアクセスすると、AdminBlogController の list　メソッドを実行する
Route::get('admin/list', 'AdminBlogController@list')->name('admin_list');*/

// 下記のようにルーティングをグループ化することもできる
// prefix メソッドを使って、グループ内の全てのルートの URI に admin を付ける
Route::prefix('admin')->group(function() {
    Route::get('form/{article_id?}', 'AdminBlogController@form')->name('admin_form');
    Route::post('post', 'AdminBlogController@post')->name('admin_post');
    Route::post('delete', 'AdminBlogController@delete')->name('admin_delete');
    Route::get('list', 'AdminBlogController@list')->name('admin_list');
    Route::get('category', 'AdminBlogController@category')->name('admin_category');
    Route::post('category/edit', 'AdminBlogController@editCategory')->name('admin_category_edit');
    Route::post('category/delete', 'AdminBlogController@deleteCategory')->name('admin_category_delete');
});

//  http://{ホスト名}/ と入力すれば、FrontBlogController の index メソッドを実行する
Route::get('/', 'FrontBlogController@index')->name('front_index');