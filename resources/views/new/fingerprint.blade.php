<html>

<body>
<!--載入jquery-->
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
<script src="/new/js/fingerprint2.js"></script>


<script>
var batterylevel;

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
            url: "/Fingerprint2/addFingerprint", data:{"_token": "{{ csrf_token() }}", "result":result, "components":components, "batterylevel":batterylevel}, type:"POST", success: function(result){

            console.log('code:'+result.code+';msg:'+result.msg);
        }});
    })
}




</script>
</body>
</html>