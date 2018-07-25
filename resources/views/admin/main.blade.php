@include('partials.header')
<div class="m-content">				          
    <div class="row">
        @include('admin.panel')
        <div style='margin-left: 25px;' class='content'>
            @yield("app-content")
        </div>
    </div>
</div>