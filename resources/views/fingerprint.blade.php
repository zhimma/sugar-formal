<!DOCTYPE html>
<html>
<head>
	<title>Fingerprint</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/0.7.20/ua-parser.js"></script>
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" ></script>
	<script src="/new/js/fingerprint.js"></script>
</head>
<body>
	<div id="result"></div>
	<input type="button" onclick="submit()"/>
	<script>

		
		var submit = function(){

			if (window.requestIdleCallback) {
				requestIdleCallback(function () {
					identifyResult('{{ csrf_token() }}', function(result){
						console.log(result)
					})
				})
			} 
			else {
				setTimeout(function () {
					identifyResult('{{ csrf_token() }}', function(result){
						console.log(result)
					})
				}, 500)
			}
		}

	</script>
</body>
</html>