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
	        	'status' => false,
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

			return view('surya95::index', compact('paramList', 'checkSum', 'url'));
		}
    }

    public function callBack(Request $request)
    {
    	$paytmChecksum = "";
		$paramList = array();
		$isValidChecksum = "FALSE";

		$paramList = $_POST;
		$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

		//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
		$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

		return view('surya95::callback', compact('paytmChecksum', 'paramList', 'isValidChecksum'));
    }

    public function txnStatus(Request $request)
    {
    	header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

    	return view('surya95::txnstatus');
    }

    public function txnStatusResponse(Request $request)
    {
    	$validator = Validator::make(request()->all(), [
  	    	'ORDER_ID' => 'required|integer',
      	]);

	    if($validator->fails())
	    {
	        $messages = [
	        	'status' => false,
	            'message' => $validator->messages()->first(),   
	        ];
	    }
	    else
	    {
	    	$ORDER_ID = "";
			$requestParamList = array();
			$responseParamList = array();

			if(isset($request->ORDER_ID) && $request->ORDER_ID != "") 
			{
				// In Test Page, we are taking parameters from POST request. In actual implementation these can be collected from session or DB. 
				$ORDER_ID = $request->ORDER_ID;

				// Create an array having all required parameters for status query.
				$requestParamList = array("MID" => PAYTM_MERCHANT_MID , "ORDERID" => $ORDER_ID);  
				
				$StatusCheckSum = getChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY);
				
				$requestParamList['CHECKSUMHASH'] = $StatusCheckSum;

				// Call the PG's getTxnStatusNew() function for verifying the transaction status.
				$responseParamList = getTxnStatusNew($requestParamList);

				if(isset($responseParamList) && count($responseParamList) > 0)
				{
					$messages = [
			        	'status' => true,
			            'message' => 'Successfully Found',
			            'data' => $responseParamList
			        ];
				}
				else
				{
					$messages = [
			        	'status' => false,
			            'message' => 'responseParamList Not Found'
			        ];
				}
			}
			else
			{
				$messages = [
		        	'status' => false,
		            'message' => 'ORDER ID Not Found' 
		        ];
			}
	    }

	    return response()->json($messages);
    }
}
