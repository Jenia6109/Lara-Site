<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::post('contacts-form', 'App\Http\Controllers\ContactController@store');
Route::get('pogoda', 'App\Http\Controllers\PogodaController@pogoda')->name('pogoda');
Route::get('zabor', 'App\Http\Controllers\PostController@list')->name('posts');
Route::get('zabor_add', 'App\Http\Controllers\PostController@add')->name('posts_add');
Route::post('zabor_add/submit', 'App\Http\Controllers\PostController@submit')->name('posts_add_submit');

Route::get('catalog', 'App\Http\Controllers\CatalogController@catalog')->name('catalog');
Route::get('catalog/rubric-{id}', 'App\Http\Controllers\CatalogController@catalog_rubric')->name('catalog_rubric');
Route::get('catalog/item-{id}', 'App\Http\Controllers\CatalogController@catalog_item')->name('catalog_item');

Route::get('cart', 'App\Http\Controllers\CartController@cart')->name('cart');
Route::post('cart-add-item', 'App\Http\Controllers\CartController@cart_add_item')->name('cart_add_item');
Route::post('cart-delete-cart-item', 'App\Http\Controllers\CartController@cart_delete_cart_item')->name('cart_delete_cart_item');
Route::post('cart-send-order', 'App\Http\Controllers\CartController@cart_send_order')->name('cart_send_order');

Route::get('admin', 'App\Http\Controllers\AdminController@admin')->middleware(['is_admin'])->name('admin');
Route::get('admin/zabor', 'App\Http\Controllers\AdminController@admin_posts')->middleware(['is_admin'])->name('admin_posts');
Route::get('admin/zabor/{id}', 'App\Http\Controllers\AdminController@admin_posts_edit')->middleware(['is_admin'])->name('admin_posts_edit');
Route::post('admin/zabor/{id}/update', 'App\Http\Controllers\AdminController@admin_posts_update')->middleware(['is_admin'])->name('admin_posts_update');
Route::post('admin/zabor/{id}/delete', 'App\Http\Controllers\AdminController@admin_posts_delete')->middleware(['is_admin'])->name('admin_posts_delete');

Route::get('admin/catalog', 'App\Http\Controllers\AdminController@admin_catalog')->middleware(['is_admin'])->name('admin_catalog');
Route::get('admin/catalog/rubric-add', 'App\Http\Controllers\AdminController@admin_catalog_rubric_add')->middleware(['is_admin'])->name('admin_catalog_rubric_add');
Route::post('admin/catalog/rubric-add/submit', 'App\Http\Controllers\AdminController@admin_catalog_rubric_add_submit')->middleware(['is_admin'])->name('admin_catalog_rubric_add_submit');
Route::get('admin/catalog/rubric-{id}', 'App\Http\Controllers\AdminController@admin_catalog_rubric_edit')->middleware(['is_admin'])->name('admin_catalog_rubric_edit');
Route::post('admin/catalog/rubric-{id}/update', 'App\Http\Controllers\AdminController@admin_catalog_rubric_update')->middleware(['is_admin'])->name('admin_catalog_rubric_update');
Route::post('admin/catalog/rubric-{id}/delete', 'App\Http\Controllers\AdminController@admin_catalog_rubric_delete')->middleware(['is_admin'])->name('admin_catalog_rubric_delete');
Route::get('admin/catalog/item-add', 'App\Http\Controllers\AdminController@admin_catalog_item_add')->middleware(['is_admin'])->name('admin_catalog_item_add');
Route::post('admin/catalog/item-add/submit', 'App\Http\Controllers\AdminController@admin_catalog_item_add_submit')->middleware(['is_admin'])->name('admin_catalog_item_add_submit');
Route::get('admin/catalog/item-{id}', 'App\Http\Controllers\AdminController@admin_catalog_item_edit')->middleware(['is_admin'])->name('admin_catalog_item_edit');
Route::post('admin/catalog/item-{id}/update', 'App\Http\Controllers\AdminController@admin_catalog_item_update')->middleware(['is_admin'])->name('admin_catalog_item_update');
Route::post('admin/catalog/item-{id}/delete', 'App\Http\Controllers\AdminController@admin_catalog_item_delete')->middleware(['is_admin'])->name('admin_catalog_item_delete');

Route::get('admin/orders', 'App\Http\Controllers\AdminController@admin_orders')->middleware(['is_admin'])->name('admin_orders');
Route::get('admin/orders/{id}', 'App\Http\Controllers\AdminController@admin_order_edit')->middleware(['is_admin'])->name('admin_order_edit');

Route::get('protected', ['middleware' => ['auth'], function() {
    return "this page requires that you be logged in and an Admin";
}]);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/social_auth/{provider}', 'App\Http\Controllers\Auth\SocialController@redirectToProvider')->name('auth.social');
Route::get('/social_auth/{provider}/callback', 'App\Http\Controllers\Auth\SocialController@handleProviderCallback')->name('auth.social.callback');
