<?php

Route::get('test', 'surya95\paymentgateway\PaymentController@index');
Route::post('callback', 'surya95\paymentgateway\PaymentController@callBack');