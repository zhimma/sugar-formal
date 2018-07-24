@include('partials.header')
<div class="m-content">				          
    <div class="row">
        @include('admin.panel')
        <div class="content col-lg-9 col-md-8">
            <div class="">
                @yield("app-content")
            </div>
        </div>
    </div>
</div>