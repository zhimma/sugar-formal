@include('partials.header')
<body>
<form id="form" method="POST" action="search">
	{!! csrf_field() !!}
	<input type="hidden" name='search' value="{{ $email }}">
</form>
變更完成！
</body>
<script>
document.getElementById('form').submit();
</script>
</html>