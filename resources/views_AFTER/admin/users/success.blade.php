@include('partials.header')
@include('partials.message')
<body>
@if (Session::has('message') && ! is_array(Session::get('message')))
	<div class="alert alert-success alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span> {{ Session::get('message') }} </span>
	</div>
    <? Session::forget('message'); ?>
@else
	<form id="form" method="POST" action="search">
		{!! csrf_field() !!}
		<input type="hidden" name='search' value="@if(isset($email)) {{ $email }} @endif">
	</form>
	變更完成！
	</body>
	<script>
	document.getElementById('form').submit();
	</script>
	</html>
@endif