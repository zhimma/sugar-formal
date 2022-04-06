<html>

<body>
<!--載入jquery-->
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
<script src="/new/js/fingerprint2.js"></script>


<script>
var orientation = getOrientationStatu();
var network = getNetwork();

/*取得電池等級*/
navigator.getBattery().then(function(battery) {
     batterylevel = battery.level;
});

addFingerprint();
function addFingerprint(){
    var options = {
        excludes: {userAgent: false, language: true}
    }
    Fingerprint2.getV18(options, function (result, components) {
        $.ajax({
            url: "/Fingerprint2/addFingerprint?{{csrf_token()}}={{now()->timestamp}}", data:{"_token": "{{ csrf_token() }}", "result":result, "components":components, "batterylevel":batterylevel, "orientation":orientation, "network":network}, type:"POST", success: function(result){

            console.log('code:'+result.code+';msg:'+result.msg);
        }});
    })
}



function getOrientationStatu() {
    var orientationStatus = ''
    var orientation = window.matchMedia("(orientation: portrait)")
    if (orientation.matches) {
        orientationStatus = "竖屏"
    } else {
        orientationStatus = "横屏"
    }
    return orientationStatus
}

function getNetwork() {
    var netWork = navigator && navigator.connection && navigator.connection.effectiveType
    return netWork
}
</script>
</body>
</html>