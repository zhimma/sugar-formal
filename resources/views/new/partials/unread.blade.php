<script>
	$(document).ready(() =>{
		var formData = new FormData();
		var xhr = new XMLHttpRequest();
		xhr.open("get", "{{ route('getUnread', $user->id) }}", true);
		xhr.onload = function (e) {
			var response = e.currentTarget.response;
			$('#unreadCount').text(response);
			$('#unreadCount2').text(response);
		}
		xhr.send(formData);  /* Send to server */
	});

	Echo.private('NewMessage.{{ $user->id }}')
		.listen('NewMessage', (e) => {
			let unread = parseInt($('#unreadCount').text(), 10) || 0;
			let unread2 = parseInt($('#unreadCount2').text(), 10) || 0;
			unread++;
			unread2++;
			$('#unreadCount').text(unread);
			$('#unreadCount2').text(unread2);
			@if(request()->route()->getName() == 'chat2View')
			if($('.sjtable.' + e.from_id).find('.number.' + e.from_id).length === 0){
				$('.sjtable.' + e.from_id).prepend('<i class="number ' + e.from_id + '">1</i>');
			}
			else{
				let chatUnread = parseInt($('.number.' + e.from_id).text(), 10);
				chatUnread++;
				$('.number.' + e.from_id).text(chatUnread);
			}
			if(showMsg){
				$('.ellipsis.' + e.from_id).text(e.content);
			}
			@endif
		});
</script>