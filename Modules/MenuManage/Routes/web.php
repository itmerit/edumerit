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

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('menumanage')->group(function () {
        Route::name('menumanage.')->middleware('auth')->group(function () {

            Route::get('/', 'MenuManageController@index')->name('index')->middleware('userRolePermission:menumanage.index');
            Route::post('menu-store', 'MenuManageController@store')->name('store.menu');
            Route::get('reset-with-section', 'SidebarManagerController@resetMenu')->name('reset-with-section');
            Route::get('/reset-default', 'SidebarManagerController@resetWithDefault')->name('reset-with-default');

        });
    });
});
Route::prefix('sidebar-manager')->middleware(['auth', 'subdomain'])->group(function () {
    Route::get('/', 'SidebarManagerController@index')->name('sidebar-manager.index');
    //section store
    Route::post('/section/store', 'SidebarManagerController@sectionStore')->name('sidebar-manager.section.store');
    Route::get('/section-edit-form/{id}', 'SidebarManagerController@sectionEditForm')->name('sidebar-manager.section-edit-form');
    Route::post('/section-update', 'SidebarManagerController@sectionUpdate')->name('sidebar-manager.section-update');

    Route::post('/section/menu-store', 'SidebarManagerController@menuStore')->name('sidebar-manager.menu-store');
    Route::post('/section/menu-update', 'SidebarManagerController@menuUpdate')->name('sidebar-manager.menu-update');
    Route::post('/section/menu-edit', 'SidebarManagerController@menuEdit')->name('sidebar-manager.menu-edit');


    Route::post('/section/sort-section', 'SidebarManagerController@sortSection')->name('sidebar-manager.sort-section');
    Route::post('/section/delete-section', 'SidebarManagerController@deleteSection')->name('sidebar-manager.delete-section');
    Route::post('/section/remove-menu', 'SidebarManagerController@removeMenu')->name('sidebar-manager.menu-remove');



});
