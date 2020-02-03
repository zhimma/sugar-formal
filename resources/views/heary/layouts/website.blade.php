<html>
	@include('heary.partials.head')
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push">
		@include('heary.partials.header')
		@yield("app-content")
		@include('heary.partials.footer')
	</body>
</html>