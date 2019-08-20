<?php

namespace surya95\paymentgateway;

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

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
    	$validator = Validator::make(request()->all(), [
  	    	'ORDER_ID' => 'required|integer',
        	'CUST_ID' => 'required|integer',
        	'TXN_AMOUNT' => 'required|numeric'
      	]);

	    if($validator->fails())
	    {
	        $messages = [
	        	'status' => 200,
	            'response' => 'ok',
	            'message' => $validator->messages()->first(),   
	        ];

	        return response()->json($messages);
	    }
	    else
	    {
	    	$checkSum = "";
			$paramList = array();

	    	$paramList["MID"] = PAYTM_MERCHANT_MID;
			$paramList["ORDER_ID"] = request()->ORDER_ID;
			$paramList["CUST_ID"] = request()->CUST_ID;
			$paramList["TXN_AMOUNT"] = request()->TXN_AMOUNT;
			$paramList["INDUSTRY_TYPE_ID"] = PAYTM_INDUSTRY_TYPE_ID;
			$paramList["CHANNEL_ID"] = PAYTM_CHANNEL_ID;
			$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
			$paramList["CALLBACK_URL"] = PAYTM_CALLBACK_URL;

			$checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);

			$url = PAYTM_TXN_URL;

			/*$client = new Client();
			$response = $client->request('POST', PAYTM_TXN_URL, [
						    'form_params' => [
						        'MID' => $paramList["MID"],
						        'ORDER_ID' => $paramList["ORDER_ID"],
						        'CUST_ID' => $paramList["CUST_ID"],
						        'TXN_AMOUNT' => $paramList["TXN_AMOUNT"],
						        'INDUSTRY_TYPE_ID' => $paramList["INDUSTRY_TYPE_ID"],
						        'CHANNEL_ID' => $paramList["CHANNEL_ID"],
						        'WEBSITE' => $paramList["WEBSITE"],
						        'CALLBACK_URL' => $paramList["CALLBACK_URL"],
						        'CHECKSUMHASH' => $checkSum
						    ]
						]);*/

			return view('paytm.index', compact('paramList', 'checkSum', 'url'));

			//echo $response->getStatusCode(); # 200
			//echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
			//echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'
		}
    }
}
