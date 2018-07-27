@include('partials.header')
<body>
    <div class="m-content">				          
        <div class="row">
            @include('admin.panel')
            <div style='margin-left: 25px;' class='content'>
                @yield("app-content")
            </div>
        </div>
    </div>
    @yield("pre-javascript")
    @include('partials.scripts')
    @yield("javascript")
</body>