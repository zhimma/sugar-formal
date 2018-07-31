@include('partials.header')
@include('partials.errors')
@include('partials.message')
<style>
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }
    .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 22px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        color: #f1f1f1;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    #main {
        transition: margin-left .5s;
        margin: 10px;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }

    #open{
        font-size:20px;
        cursor:pointer;
    }

    .navbar{
        display:inline-block;
        margin: -15px -15px 0px -15px;
        padding: 10px 20px 10px 20px;
        background-color: rgba(0,0,0,0.4);
        color: #FFFFFF;
    }

    .instructor{
        float: right;
        font-size:20px;
    }
</style>
<body>
    @include('admin.panel')
    <div class="navbar">
        <span onclick="openNav()" class="" id="open">&#9776;開啟選單</span>
        <span class="instructor">甜心花園網：管理後台</span>
    </div>
    <div id="main">
        @yield("app-content")
    </div>
    @yield("pre-javascript")
    @include('partials.scripts')
    @yield("javascript")
</body>
<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
        document.body.style.backgroundColor = "white";
    }
</script>