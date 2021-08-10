<tr>
	<th>
		<style>
			body table tr td .date_input {width:25%;display:inline-block;min-width:80px;padding-left:0;padding-right:0;margin-left:0;margin-right:0;}
			body table tr td .time_input {
				width:25%;
				min-width:55px;
				text-align:left;
				background-color:transparent;
				border-color:transparent;
				border:none;
				display:inline-block;
				cursor:default;
				padding-left:0;padding-right:0;
				margin-left:0;margin-right:0;
			}
		</style>	
		<script>
			function set_now_time() {
				$('#autotime_1').val(str_pad(hour) + ':' + str_pad(minute) + ':' + str_pad(second));
				$('#autotime_2').val(str_pad(hour) + ':' + str_pad(minute) + ':' + str_pad(second));
			}
			
			function set_end_date(){
				$('#datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
				$('.datepicker_2').val(year + '-' + str_pad(month) + '-' + str_pad(day));
			}
			function str_pad(n) {
				return String("00" + n).slice(-2);
			}
			
			function clear_time_input() {
				$('#autotime_1').val('');
				$('#autotime_2').val('');
			}
		</script>								
		開始時間
	</th>
	<td>
		<input type='text' id="datepicker_1" name="date_start" data-date-format='yyyy-mm-dd' value="{{ request()->date_start ?? "" }}" class="form-control date_input"  autocomplete="off">
		<input type='text' id="autotime_1" name="time_start" value="{{ request()->time_start ?? "" }}" class="form-control time_input" readonly>
		<script>
			jQuery(document).ready(function(){
				jQuery("#datepicker_1").datepicker(
					{
						dateFormat: 'yy-mm-dd',
						todayHighlight: !0,
						orientation: "bottom left",
						templates: {
							leftArrow: '<i class="la la-angle-left"></i>',
							rightArrow: '<i class="la la-angle-right"></i>'
						}
					}
				).on("changeDate", function (e) {
					clear_time_input();
					$("#datepicker_2").val($(this).val());
				}).val();       
			});
		</script>							
	</td>
<tr>
	<th>結束時間</th>
	<td>
		<input type='text' id="datepicker_2" name="date_end" data-date-format='yyyy-mm-dd' value="{{ request()->date_end ?? "" }}" class="form-control date_input"  autocomplete="off">
		<input type='text' id="autotime_2" name="time_end" value="{{ request()->time_end ?? "" }}" class="form-control time_input" readonly>							
		<script>
			jQuery(document).ready(function(){
				jQuery("#datepicker_2").datepicker(
					{
						dateFormat: 'yy-mm-dd',
						todayHighlight: !0,
						orientation: "bottom left",
						templates: {
							leftArrow: '<i class="la la-angle-left"></i>',
							rightArrow: '<i class="la la-angle-right"></i>'
						}
					}
				).on("changeDate", function (e) {
					clear_time_input();
				}).val();
			});
		</script>							
	</td>
</tr>
<tr>
	<th>預設時間選項</th>
	<td>
		<a class="text-white btn btn-success today">最近一天</a>
		<a class="text-white btn btn-success last3days">最近三天</a>
		<a class="text-white btn btn-success last5days">最近五天</a>
		<a class="text-white btn btn-success last7days">最近七天</a>
		<a class="text-white btn btn-success last10days">最近十天</a>
		<script>
			let date = new Date();
			let year = date.getFullYear();
			let month = date.getMonth() + 1;
			let day = date.getDate();
			let today = new Date(year, month, day);
			let hour = date.getHours();
			let minute = date.getMinutes();
			let second = date.getSeconds();
			let minus_date = new Date(today);		
			jQuery(document).ready(function(){				
				$('.today').click(
					function(){
						minus_date.setDate(minus_date.getDate() - 1);
						$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						
						set_now_time();
						
						set_end_date();
						minus_date.setDate(minus_date.getDate() +1);
					});
				$('.last3days').click(
					function () {
						minus_date.setDate(minus_date.getDate() - 3);
						$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						set_end_date();
						set_now_time();
						minus_date.setDate(minus_date.getDate() + 3);
					});
				$('.last10days').click(
					function () {
						minus_date.setDate(minus_date.getDate() - 10);
						$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						set_end_date();
						set_now_time();
						minus_date.setDate(minus_date.getDate() + 10);
					});
				$('.last5days').click(
					function () {
						var start_date = new Date(new Date().setDate(date.getDate() - 5));
						$('#datepicker_1').val(start_date.getFullYear() + '-' + str_pad(parseInt(start_date.getMonth()+1)) + '-' + str_pad(start_date.getDate()));
						$('.datepicker_1').val(start_date.getFullYear() + '-' + str_pad(parseInt(start_date.getMonth()+1)) + '-' + str_pad(start_date.getDate()));
						set_end_date();
						set_now_time();
					});
				$('.last7days').click(
					function () {
						minus_date.setDate(minus_date.getDate() - 7);
						$('#datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						$('.datepicker_1').val(minus_date.getFullYear() + '-' + str_pad(minus_date.getMonth()) + '-' + str_pad(minus_date.getDate()));
						set_end_date();
						set_now_time();
						minus_date.setDate(minus_date.getDate() + 7);
					});
				
			});
		</script>							
	</td>
</tr>