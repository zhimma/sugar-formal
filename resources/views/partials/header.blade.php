<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>甜心花園網，台灣人數最多的甜心與糖爹的約會包養網站。注重隱私，絕對安全。快速、安全、高品質。</title>
        <meta name="Keywords" content="甜心花園|車馬費|包養金額|包養管到|包養行情|包養故事|包養|包養網|甜心|甜心寶貝">
        <meta name="Description" content="甜心花園網是台灣最完善的媒合網站，有人數最多的甜心與最優質的糖爹，最優秀男女約會的交友包養網站。">		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">                
          		
        <script src="/js/search.js"></script>
		<script src="/js/dashboard.js"></script>
		<script>
       /*   WebFont.load({
            google: {"families":["Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          }); */
		</script>
		<script
			src="https://browser.sentry-cdn.com/7.50.0/bundle.tracing.replay.min.js"
			integrity="sha384-b1ZNC0hsmhMGyUFlY9EOVntMNq5+xxvNljWXW89CrfzVZmJliFL784aDRFgHl6G4"
			crossorigin="anonymous"
		></script>
		<script
				src="https://browser.sentry-cdn.com/7.50.0/captureconsole.min.js"
				integrity="sha384-MLpMOdzpoBBVNrHDJSgNvhIy88xHmDN1WlDfXA7eQmiuBAzf0xKhBeZ9WSImjVgk"
				crossorigin="anonymous"
		></script>
		<script>
			Sentry.init({
				dsn: "https://91838140cc964d4994202d4d3994654a@o1051989.ingest.sentry.io/6090793",
				integrations: [new Sentry.Integrations.CaptureConsole(
						{
							// array of methods that should be captured
							// defaults to ['log', 'info', 'warn', 'error', 'debug', 'assert']
							levels: ['debug'],
						}
				)],
			});
		</script>
		{!! \Sentry\Laravel\Integration::sentryTracingMeta() !!}
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-151409328-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', 'UA-151409328-1');
		</script>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-B9JNVX79DJ"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-B9JNVX79DJ');
		</script>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-JD3XWPPFMH"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-JD3XWPPFMH');
		</script>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-YV39TMFKMC"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-YV39TMFKMC');
		</script>
		<link href="/css/vendors.bundle.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link href="/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/css/footer.css">
		<link rel="stylesheet" href="/css/search.css">
		<link rel="stylesheet" href="/css/header.css">
		<link rel="stylesheet" href="/css/message.css">
		<link rel="stylesheet" href="/css/dashboard.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.0.0/cropper.min.css" />
		<link rel="shortcut icon" href="img/favicon.jpg" />		
        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
	</head>
