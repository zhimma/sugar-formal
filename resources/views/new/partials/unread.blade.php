<script>
    let unread = parseInt($('#unreadCount').text(), 10) || 0;
    let unread2 = parseInt($('#unreadCount2').text(), 10) || 0;
    function animateValue(id, start, end, duration) {
        if (start === end) return;
        let range = end - start;
        let current = start;
        let increment = end > start? 1 : -1;
        let stepTime = Math.abs(Math.floor(duration / range));
        let obj = document.getElementById(id);
        let timer = setInterval(function() {
            current += increment;
            obj.innerHTML = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

	$(document).ready(() =>{
		let formData = new FormData();
        let xhr = new XMLHttpRequest();
		xhr.open("get", "{{ route('getUnread', $user->id) }}", true);
		xhr.onload = function (e) {
            let response = parseInt(e.currentTarget.response, 10);
            animateValue('unreadCount', unread, response, 500);
            animateValue('unreadCount2', unread2, response, 500);
		}
		xhr.send(formData);  /* Send to server */
	});

	Echo.private('NewMessage.{{ $user->id }}')
		.listen('NewMessage', (e) => {
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