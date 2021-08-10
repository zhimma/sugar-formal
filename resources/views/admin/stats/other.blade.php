@extends('admin.main')
@section('app-content')
<style>
	li{
		list-style: decimal;
	}
</style>
<body style="padding: 15px;">
<h1>其他 VIP 相關統計資料</h1>
<ol>
	<li>
		男 VIP 人數: <button class="btn btn-primary getData" id="1">取得資料</button>
	</li>
	<li>
		30 天內有上線的女 VIP 人數: <button class="btn btn-primary getData" id="2">取得資料</button>
	</li>
	<li>
		30 天內男 VIP 發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="3">取得資料</button>
	</li>
	<li>
		30 天內普通男會員發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="4">取得資料</button>
	</li>
	<li>
		車馬費邀請總數/有回應的比例: <button class="btn btn-primary getData" id="5">取得資料</button>
	</li>
	<li>
		一個月內上站男會員總數: <button class="btn btn-primary getData" id="6">取得資料</button>
	</li>
	<li>
		優選會員(男)人數: <button class="btn btn-primary getData" id="7">取得資料</button>
	</li>
	<li>
		30 天內優選會員(男)發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="8">取得資料</button>
	</li>
	<li>
		三天內男 VIP 發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="9">取得資料</button>
	</li>
	<li>
		三天內普通男會員發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="10">取得資料</button>
	</li>
	<li>
		三天內優選會員(男)發訊總數/獲得回應比例: <button class="btn btn-primary getData" id="11">取得資料</button>
	</li>
</ol>
</body>
<script>
	$(".getData").each(function() {
		let $this = $(this); //store $(this) reference in a variable
		$(this).on("click", function (){
			$this.text('載入中');
			$.ajax({
				type: 'POST',
				url: "{{ route('stats/vip/other') }}",
				data:{
					_token: '{{csrf_token()}}',
					number: $(this).attr('id'),
				},
				dataType:"text",
				success: function (result) {
					$this.text(result);
					$this.removeClass("btn btn-primary");
					$this.attr("disabled", true);
					$this.css('color', '#000');
					$this.css('border', 'none');
				}
			});
		});
	});
</script>
</html>
@stop