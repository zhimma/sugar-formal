<script src="/js/vendors.bundle.js" type="text/javascript"></script>
<script src="/js/scripts.bundle.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_zh_TW.min.js"></script>
<script src="/js/jquery.twzipcode.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        var isMobile = false;
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Opera Mobile|Kindle|Windows Phone|PSP|AvantGo|Atomic Web Browser|Blazer|Chrome Mobile|Dolphin|Dolfin|Doris|GO Browser|Jasmine|MicroB|Mobile Firefox|Mobile Safari|Mobile Silk|Motorola Internet Browser|NetFront|NineSky|Nokia Web Browser|Obigo|Openwave Mobile Browser|Palm Pre web browser|Polaris|PS Vita browser|Puffin|QQbrowser|SEMC Browser|Skyfire|Tear|TeaShark|UC Browser|uZard Web|wOSBrowser|Yandex.Browser mobile/i.test(navigator.userAgent)) isMobile = true;

        // 偵測手機or電腦裝置
        if(isMobile) {
            $('.device').attr("value", "1");
        }
        else {
            $('.device').attr("value", "0");
        }


        // 車馬費內容
        $('.invite').on('click', function(event) {
            var r = confirm("   車馬費說明 \n\n這筆費用是用來向女方表達見面的誠意。\n\n●若約見順利\n站方在扣除 288 手續費，交付 1500 與女方。\n\n●若有爭議(例如放鴿子)\n站方將依女方提供的證明資料，決定是否交付款項與女方。\n\n●爭議處理\n若女方提出證明文件，則交付款項予女方。\n若女方於於約見日五日內未提出相關證明文件。\n將扣除手續費後匯回男方指定帳戶。\n\n注意：此費用一經匯出，即全權交由本站裁決處置。\n本人絕無異議，若不同意請按取消鍵返回。");

            if(!r) {
                event.preventDefault();
            }
        });

        // 升級VIP內容
        $('.upgradevip').on('click', function(event) {
            var r = confirm("加入 VIP 須知。\n●加入VIP後將於每月於第一次刷卡日期自動扣款，至取消為止。\n●升級VIP之後，升級VIP的選項會變成取消VIP，取消後次月即停止扣款\n●訊息不會被過濾掉(會員可以設定拒接非VIP會員來信)\n●不受限制的收發信件(下個月起普通會員收發信件總數將受限)\n●可以觀看進階統計資料\n●可以知道訊息是否已讀\n●可以知道對方是否封鎖自己\n●您申請每月自動扣款並完成繳費，經確認繳費程序完成且已成功開啟本站相關服務設定，即視同您已經開始使用所購買之每月自動扣款\n●最短 VIP 時間為一個月，若使用不足一個月，以一個月計算，不予退費。\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！★");
            if(!r) {
                event.preventDefault();
            }
        });

        // 取消VIP內容
        $('.cancelvip').on('click', function(event) {
            @if(isset($vipLessThan7days) && $vipLessThan7days)
                var r = confirm("取消 VIP 須知。\n●最短 VIP 時間為一個月，若使用不足一個月，以一個月計算，不予退費。\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！★\n★★若取消時間低於七個工作天，則下個月將會繼續扣款，並且 VIP 時間延長至下下個月為止。★★\n★★由於您於本日" + "{{ \Carbon\Carbon::now()->toDateString() }}" + "申請取消 VIP 。您每月的 VIP 扣款日期為 " + "{{ $vipRenewDay }}" + " 日。取消扣款作業需七個工作天(申請VIP時有提示)，本月取消作業不及，下個月將會進行最後一次扣款，您的 VIP 時間將會到 " + "{{ $vipNextMonth->toDateString() }}" + "。不便之處敬請見諒。★★");
            @else
                var r = confirm("取消 VIP 須知。\n●最短 VIP 時間為一個月，若使用不足一個月，以一個月計算，不予退費。\n★取消 VIP 時間需要七個工作天，如下個月不續約請提前取消，以免權益受損！★\n★★若取消時間低於七個工作天，則下個月將會繼續扣款，並且 VIP 時間延長至下下個月為止。★★");
            @endif

            if(!r) {
                event.preventDefault();
            }
        });
    });

</script>
