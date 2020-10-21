<html>
<head>
<script type="text/javascript">
function formSubmit()
  {
  document.getElementById("myForm").submit()
  }
</script>
</head>

<body>

<form id="myForm" action="/sms_add" method="post">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
Msg: <input type="text" name="message" size="20"><br />

<br />
<input type="button" onclick="formSubmit()" value="Submit">
</form>



</body>

</html>