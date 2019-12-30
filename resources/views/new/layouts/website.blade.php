@include('new.partials.header')
<body class="" >
    @include('new.layouts.navigation')
    @yield("app-content")
    @include('new.partials.footer')
    @include('new.partials.message')
    @include('new.partials.scripts')

    @yield("javascript")
</body>
</html>