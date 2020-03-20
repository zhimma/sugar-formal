
		<div class="bot">
			<a href="{!! url('notification') !!}" target="_blank" >站長開講</a> 丨
			<a href="{!! url('feature') !!}" target="_blank" > 網站使用</a> 丨
			<a href="{!! url('terms') !!}" target="_blank" > 使用條款</a> 丨
			<a href="{!! url('contact') !!}" target="_blank" > 聯絡我們</a>
			@if(Auth::check()) <a href="{!! url('dashboard2') !!}"> 丨 舊版頁面</a> @endif
			<a href="{!! url('') !!}" >
				<img src="/new/images/bot_10.png">
			</a>
		</div>
