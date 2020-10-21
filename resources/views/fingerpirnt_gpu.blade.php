<html>
<meta charset="UTF-16">
<!-- ajax post 資料時所需的token-->
<meta name="_token" content="{{ csrf_token() }}">
<head>
<title>Cross Browser Test</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/seedrandom/2.3.10/seedrandom.min.js"></script>
<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
<script src="js/fingerprint/depth_texture/framework.js"></script>
<script src="js/fingerprint/depth_texture/meshes.js"></script>
<script src="js/fingerprint/depth_texture/webgl-nuke-vendor-prefix.js"></script>
<script src="js/fingerprint/depth_texture/webgl-texture-float-extension-shims.js"></script>

<script src="js/fingerprint/three/three.js"></script>
<script src="js/fingerprint/three/js/Detector.js"></script>
<script src="js/fingerprint/three/js/shaders/FresnelShader.js"></script>
<script src="js/fingerprint/three/js/loaders/DDSLoader.js"></script>
<script src="js/fingerprint/three/js/loaders/PVRLoader.js"></script>
<script src="js/fingerprint/js/advert.js"></script>
<script src="js/fingerprint/js/cookie.js"></script>
<script src="js/fingerprint/js/loader.js"></script>
</head>
<body>

	<div id = "test_canvases" style="display: none;"></div>

	<object id="fontListSWF" name="fontListSWF" type="application/x-shockwave-flash" data="fonts/FontList.swf" width="1" height="1">
    	<param name="movie" value="FontList.swf">
		<embed src="FontList.swf" width="1" height="1"></embed>
	</object>

	<script src="js/fingerprint/texture/app.js"></script>
	<script src="js/fingerprint/js/util.js"></script>
	<script src="js/fingerprint/js/gl-matrix.js"></script>
	<script src="js/fingerprint/cube/no_texture.js"></script>
	<script src="js/fingerprint/camera/camera.js"></script>
	<script src="js/fingerprint/line/app.js"></script>
	<script src="js/fingerprint/simpleLight/app.js"></script>
	<script src="js/fingerprint/moreLight/app.js"></script>
	<script src="js/fingerprint/twoTexturesMoreLight/app.js"></script>
	<script src="js/fingerprint/transparent/app.js"></script>
	<script src="js/fingerprint/js/toServer.js"></script>
	<script src="js/fingerprint/video/video.js"></script>
	<script src="js/fingerprint/three/lighting.js"></script>
	<script src="js/fingerprint/js/audio.js"></script>
	<script src="js/fingerprint/js/detect-zoom.min.js"></script>
	<script src="js/fingerprint/js/sha1.js"></script>
	<script src="js/fingerprint/canvas/canvas.js"></script>
	<script src="js/fingerprint/three/clipping.js"></script>
	<script src="js/fingerprint/three/bubbles.js"></script>
	<script src="js/fingerprint/three/compressedTexture.js"></script>
	<script src="js/fingerprint/js/languageDetector.js"></script>
	<script src="js/fingerprint/depth_texture/vsm-filtered-shadow.js"></script>
	<script src="js/fingerprint/js/index.js"></script>
	<script src="js/fingerprint/js/fontdetect.js"></script>
	<script src="js/fingerprint/languages/languageDetector.js"></script>
</body>
</html>
