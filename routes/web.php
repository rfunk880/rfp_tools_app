<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResizeController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\DownloadMediaController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/login');
});
Route::get('media/{id}/download', [DownloadMediaController::class, 'show'])->name('media.download');
Route::get('/img/{path}', [ResizeController::class, 'image'])->where('path', '.*')->name('dynamic.image');

Route::group(
    [
        'prefix' => 'oauth',
        'as' => 'oauth.',
        'namespace' => '\\App\\Http\\Controllers',
        'middleware' => ['guest', 'throttle']
    ],
    function () {
        Route::get('/{provider}', 'Auth\SocialiteController@redirectToProvider')->name('social.login')->where('provider', 'google|github|facebook|linkedin');
        Route::get('/{provider}/callback', 'Auth\SocialiteController@handleProviderCallback')->name('social.login.callback')->where('provider', 'google|github|facebook|linkedin');
    }
);

Route::group([
    'prefix' => 'webpanel',

    'middleware' => [
        'auth',
        config('jetstream.auth_session'),
        'verified'
    ]
], function () {

    Route::group([
        'namespace' => '\\App\\Http\\Controllers',
    ], function () {
        Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
        Route::get('users/delete/{id}', 'Admin\UsersController@destroy');
        Route::get('users/loginas/{id}', 'Admin\UsersController@loginAs');
        Route::get('reset-password/{id}', 'Admin\UsersController@getResetPassword');
        Route::post('users/clear-login', 'Admin\UsersController@clearLogin')->name('clear.logout');
        Route::get('my/profile', array('uses' => 'Admin\UsersController@getProfile'));
        Route::post('my/profile', array('as' => 'admin.profile.update', 'uses' => 'Admin\UsersController@postProfile'));
        Route::get('my/password', array('uses' => 'Admin\UsersController@getChangePassword'));
        Route::post('my/password', array('uses' => 'Admin\UsersController@postChangePassword'));
        Route::post('users/switch-role', 'Admin\UsersController@switchRole')->name('webpanel.users.switch-role');

        Route::resource('users', 'Admin\UsersController', [
            'as' => 'webpanel'
        ]);

        Route::post('ajax/update-tag/{id}', 'Admin\AjaxController@updateTag');
        Route::post('ajax/contacts-by-companies', 'Admin\AjaxController@contactsByCompanies');

        Route::get('ajax/tags/delete/{id}', 'Admin\AjaxController@deleteTag');


        Route::get('roles/{id}/delete', 'Admin\RolesController@destroy')->name('webpanel.roles.delete');
        Route::post('roles/bulk-update', 'Admin\RolesController@bulkUpdate')->name('webpanel.roles.bulk-update');
        Route::resource('roles', 'Admin\RolesController', [
            'as' => 'webpanel'
        ]);

        Route::get('companies/export', 'Admin\CompaniesController@export')->name('webpanel.companies.export');
        Route::post('companies/bulk-action', 'Admin\CompaniesController@bulkAction')->name('webpanel.companies.bulk-action');
        Route::post('companies/import', 'Admin\CompaniesController@import')->name('webpanel.companies.import');
        Route::get('companies/delete/{id}', 'Admin\CompaniesController@destroy');
        Route::resource('companies', 'Admin\CompaniesController', [
            'as' => 'webpanel'
        ]);
        Route::resource('contacts', 'Admin\ContactsController', [
            'as' => 'webpanel'
        ]);


        Route::post('projects/buik-update', 'Admin\ProjectsController@bulkUpdate')->name('webpanel.projects.bulk-update');
        Route::get('projects/export', 'Admin\ProjectsController@export')->name('webpanel.projects.export');
        Route::post('projects/bulk-action', 'Admin\ProjectsController@bulkAction')->name('webpanel.projects.bulk-action');
        Route::post('projects/import', 'Admin\ProjectsController@import')->name('webpanel.projects.import');
        Route::get('projects/delete/{id}', 'Admin\ProjectsController@destroy');
        Route::resource('projects', 'Admin\ProjectsController', [
            'as' => 'webpanel'
        ]);


        Route::post('messages/bulk-action', 'Admin\MessagesController@bulkAction')->name('webpanel.messages.bulk-action');
        Route::get('messages/{projectId}/create', 'Admin\MessagesController@createForProject')->name('webpanel.project.message');
        Route::get('messages/delete/{id}', 'Admin\MessagesController@destroy');
        Route::resource('messages', 'Admin\MessagesController', [
            'as' => 'webpanel'
        ]);

        Route::post('calllogs/bulk-action', 'Admin\CallLogsController@bulkAction')->name('webpanel.calllogs.bulk-action');
        Route::get('calllogs/{projectId}/create', 'Admin\CallLogsController@createForProject')->name('webpanel.project.call');
        Route::get('calllogs/delete/{id}', 'Admin\CallLogsController@destroy');
        Route::resource('calllogs', 'Admin\CallLogsController', [
            'as' => 'webpanel'
        ]);


        Route::get('reports/meetings', 'Admin\ReportsController@meetingsReport')->name('webpanel.reports.meetings');
        Route::get('reports/sales', 'Admin\ReportsController@salesReport')->name('webpanel.reports.sales');
        Route::get('reports/bid', 'Admin\ReportsController@bidReport')->name('webpanel.reports.bid');
        Route::get('reports/facility', 'Admin\ReportsController@facilityReport')->name('webpanel.reports.facility');
        Route::get('reports/coverage', 'Admin\ReportsController@coverageReport')->name('webpanel.reports.coverage');


        Route::get('facilities/delete-tags', 'Admin\FacilitiesController@deleteTags');
        Route::post('facilities/bulk-action', 'Admin\FacilitiesController@bulkAction')->name('webpanel.facilities.bulk-action');
        Route::post('facilities/import', 'Admin\FacilitiesController@import')->name('webpanel.facilities.import');
        Route::get('facilities/delete/{id}', 'Admin\FacilitiesController@destroy');
        Route::resource('facilities', 'Admin\FacilitiesController', [
            'as' => 'webpanel'
        ]);
    });
    Route::get('projects/{id}/live-edit', \App\Livewire\Projects\Edit::class)->name('webpanel.projects.live-edit');
});
