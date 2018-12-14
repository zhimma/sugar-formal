@include('partials.newheader')
<body>
    
    <div class="centerbg">
        <div class="weui-pt30 weui-pb30 container">

            <div class="row">
                <div class="col-md-12">
                    @include('partials.errors')
                    @include('partials.message')
                </div>
            </div>
        @include('layouts.newnavigation')

        @yield("app-content")
        @include('partials.newfooter')
        @include('partials.scrollup')
        <script type="text/javascript">
            var _token = '{!! Session::token() !!}';
            var _url = '{!! url("/") !!}';
            
        </script>
        @yield("pre-javascript")
        @include('partials.newscripts')
        @yield("javascript")
    </body>
</html>