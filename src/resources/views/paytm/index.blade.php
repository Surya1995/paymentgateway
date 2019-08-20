<html>
	<head>
		<title>Merchant Checkout Page</title>
	</head>
	<body>

		<center><h1>Please do not refresh this page...</h1></center>

		<form method='post' action='{{ $url }}' name='paytm_form'>

			@foreach($paramList as $name => $value)
				<input type="hidden" name="{{ $name }}" value="{{ $value }}">
			@endforeach	
				<input type="hidden" name="CHECKSUMHASH" value="{{ $checksum }}">

		</form>

		<script type="text/javascript">
			document.paytm_form.submit();
		</script>

	</body>
</html>