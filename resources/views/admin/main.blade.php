@include('partials.header')
<div class="m-content">				          
    <div class="row">
        @include('admin.panel')
        <div>
            @yield("app-content")
        </div>
    </div>
</div>