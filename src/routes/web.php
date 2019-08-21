<?php

Route::get('test', 'surya95\paymentgateway\PaymentController@index');
Route::post('callback', 'surya95\paymentgateway\PaymentController@callBack');
Route::get('txn-status', 'surya95\paymentgateway\PaymentController@txnStatus');
Route::get('txn-status-response', 'surya95\paymentgateway\PaymentController@txnStatusResponse');