@include('new.partials.header')
<body class="" >
    @include('new.layouts.navigation')
    @yield("app-content")
    @include('new.partials.footer')
    @include('new.partials.scripts')
</body>
</html>