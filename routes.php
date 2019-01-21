<?php
/**
 * Created by PhpStorm.
 * User: ernes
 * Date: 20/1/2019
 * Time: 23:36
 */
Route::get('extends-login-elv', 'Eliberio\LoginCentral\Controllers\ProcessLoginController@login');
Route::post('extends-logout-elv', 'Eliberio\LoginCentral\Controllers\ProcessLoginController@logout');