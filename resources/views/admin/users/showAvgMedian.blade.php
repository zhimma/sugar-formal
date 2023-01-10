@include('partials.header')
@include('partials.message')
<body style="padding: 15px;">
<h1>招手比調整</h1>
<h4>招手比計算方式，招手比 = <span class="greetingRate">{{ $greetingRate }}</span></h4>
<div style="margin-bottom:20px;">
	<input type="text" name="infix" value="{{ $infix }}" style="width:60%;">
	<button class="btn btn-warning compute" onclick="modify(0)">計算</button>
	<button class="btn btn-info save" onclick="modify(1)">確定修改</button>
</div>
<div style="color:red;">說明：
	<ul>
		<li>1. 平均數請使用 Avg 為關鍵字</li>
		<li>2. 中位數請使用 Median 為關鍵字</li>
		<li>3. 公式請記得加括號</li>
		<li>4. 僅支援 +-*/、Max、Min</li>
		<li>5. 如有使用 Max, Min，括號與逗號請依照格式填寫，例: Max(Avg,Median)</li>
	</ul> 
</div>

<h4>一個月內的 avg 與 中位數</h4>
<table class="table table-hover table-bordered">
	<tr>
		<th>日期</th>
		<th width="30%">Avg</th>
		<th width="30%">中位數</th>
	</tr>
	@forelse ($day_statistic as $data)
		<tr>
			<td>
				{{ $data->date }}
			</td>
			<td>
				{{ $data->average_recipients_count_of_vip_male_senders }}
			</td>
			<td>
				{{ $data->median_recipients_count_of_vip_male_senders }}
			</td>
		</tr>
    @empty
        沒有訊息
    @endforelse
</table>

</body>
</html>

<link type="text/css" rel="stylesheet" href="/new/css/app.css">
<script>
	function modify(save){
		var infix = $('input[name="infix"]').val();
		 $.ajax({
            type: 'POST',
            url: '/admin/greetingRate/modify',
            data:{
                _token: '{{csrf_token()}}',
                infix: infix,
                save: save,
            },
            dataType:"json",
            success: function(res){
				$('.greetingRate').text(res);
        }});
	}
</script>