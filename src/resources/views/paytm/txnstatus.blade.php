<html>
	<head>
		<title>Merchant Txn-Status Page</title>
	</head>
	<body>

		<center><h1>Check Your Trasaction Status</h1></center>

		<h2>Transaction status query</h2>

		<form method="post" id="txnStatusForm" >

			<table border="1">
				<tbody>
					<tr>
						<td><label>ORDER_ID::*</label></td>
						<td><input id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="">
						</td>
					</tr>
					<tr>
						<td></td>
						<td><button type="button" id="txn-button" >Status Query</button></td>
					</tr>
				</tbody>
			</table>

			<br/></br/>

		</form>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			
			$(document).ready(function($) {

				$('#txn-button').click(function(event) 
				{
					var data = $('#txnStatusForm').serialize();

					$.ajax({
						url: '/txn-status-response',
						type: 'GET',
						data: data,
						success:function(data)
						{
							console.log(data);
						}
					});
					
				});

			});

		</script>

	</body>
</html>