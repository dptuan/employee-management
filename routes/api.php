<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'v1/employees',
    'as' => 'employees.',
], function () {
    Route::get('/', 'EmployeeController@index')->name('employees.index');
    Route::get('/{employee}', 'EmployeeController@show')->name('employees.show');
    Route::put('/{employee}', 'EmployeeController@update')->name('employees.update');
    Route::post('/', 'EmployeeController@store')->name('employees.store');
});
