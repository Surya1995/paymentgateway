<html>
	<head>
		<title>Merchant Callback Page</title>
	</head>
	<body>

		<center><h1>This Is Your Callback Response</h1></center>

		@if($isValidChecksum == "TRUE")

			<b>Checksum matched and following are the transaction details:</b> . <br/>

			@if($_POST["STATUS"] == "TXN_SUCCESS")
				<b>Transaction status is success</b> . <br/>
			@else
				<b>Transaction status is failure</b> . <br/>
			@endif

			@if(isset($_POST) && count($_POST) > 0)

				@foreach($_POST as $paramName => $paramValue)
					<br/> {{ $paramName }} =  {{ $paramValue }}
				@endforeach

			@endif

		@else
			<b>Checksum mismatched.</b>
		@endif

	</body>
</html>