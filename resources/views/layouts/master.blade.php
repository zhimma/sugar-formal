    @include('partials.header')
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-footer--push" >
	<div class="m-grid m-grid--hor m-grid--root m-page">

        @include("layouts.navigation")



        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
			<div class="m-grid__item m-grid__item--fluid m-wrapper">

				<div class="m-content">
				          <div class="row">
                <div class="col-md-12">
                    @include('partials.errors')
                    @include('partials.message')
                </div>
            </div>
				    <div class="row">
                        @include('dashboard.panel')
                        <div class="content col-lg-9 col-md-8">
                            <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                                @yield("app-content")
                            </div>
                        </div>
                    </div>
                </div>

        @include('partials.footer')
        @include('partials.scrollup')
        <script type="text/javascript">
            var _token = '{!! Session::token() !!}';
            var _url = '{!! url("/") !!}';
        </script>
        @yield("pre-javascript")
        @include('partials.scripts')
        @yield("javascript")
    </body>
</html>
