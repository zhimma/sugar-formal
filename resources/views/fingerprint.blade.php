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
					analysisFingerpirnt(function(data){
						// !important
						// data  include "_token" beacause IE and Edge is not support add 
						data['_token'] = '{{ csrf_token() }}'
						console.log(data)
						$.post("{{ route('saveFingerprint') }}", data, function(result, textStatus, xhr) {
							console.log(result)
						})
						.fail(function(result, textStatus, xhr){
							//console.log(result)
						})
					
					})
				})
			} 
			else {
				setTimeout(function () {
					analysisFingerpirnt(function(data){
						data['_token'] = '{{ csrf_token() }}'
						console.log(data)
						$.post("{{ route('saveFingerprint') }}", data, function(result, textStatus, xhr) {
							console.log(result)
						})
						.fail(function(result, textStatus, xhr){
							//console.log(result)
						})
					
					})
				}, 500)
			}
		}

	</script>
</body>
</html>