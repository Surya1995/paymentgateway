<?php

namespace surya95\paymentgateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
	public $configPaytm;
	public $encdecPaytm;

	public function __construct()
	{
		require_once(__DIR__.'/paytm/lib/config_paytm.php');
		require_once(__DIR__.'/paytm/lib/encdec_paytm.php');
	}

    public function index()
    {
    	dd(encrypt_e('ASC', 'DESC'));
    	//dd($this->configPaytm);
    }
}
