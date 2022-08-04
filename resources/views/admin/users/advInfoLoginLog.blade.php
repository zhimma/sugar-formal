<table class="table table-hover table-bordered">
    @foreach($userLogin_log as $logInLog)
        <tr>
            <td>
                <span class="loginItem showRecord" id="showloginTime{{substr($logInLog->loginDate,0,7)}}" data-sectionName="loginTime{{substr($logInLog->loginDate,0,7)}}" data-ip="不指定">{{ substr($logInLog->loginDate,0,7) . ' ['. $logInLog->dataCount .']' }}</span>
                <table>
                    @php
                        $CFP_count=count(array_get($logInLog->CfpID,'CfpID_group',[]));
                        $IP_count=count(array_get($logInLog->Ip,'Ip_group',[]));
                    @endphp
                    <td style="min-width: 100px"></td>
                    <td style="min-width: 100px"></td>
                    @if($CFP_count>0)
                        @foreach(array_get($logInLog->CfpID,'CfpID_group',[]) as $gpKey =>$group)
                            @if($gpKey<5)
                                <td class="loginItem" id="showcfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="cfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-assign_user_id="{{ $user->id }}" data-yearMonth="{{substr($logInLog->loginDate,0,7)}}" data-cfpID="{{$group->cfp_id}}" style="margin-left: 20px;min-width: 100px;{{ $group->CfpID_set_auto_ban ? 'background:yellow;' : '' }}">{{ $group->cfp_id.'('.$group->dataCount .')' }}</td>
                            @endif
                        @endforeach
                    @endif
                    @for($i=0; $i< 5-$CFP_count; $i++)
                        <th style="min-width: 100px"></th>
                    @endfor
                    @if($CFP_count>=6)
                        <th style="min-width: 100px">...</th>
                    @else
                        <th style="min-width: 100px"></th>
                    @endif

                    @if($IP_count>0)
                        @foreach(array_get($logInLog->Ip,'Ip_group',[]) as $gpKey =>$group)
                            @if($gpKey<10)
                                <td class="loginItem ipItem" id="showIp{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="Ip{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-assign_user_id="{{ $user->id }}" data-yearMonth="{{substr($logInLog->loginDate,0,7)}}" data-ip="{{ $group->ip }}" style="margin-left: 20px;min-width: 150px;{{ $group->IP_set_auto_ban ? 'background:yellow;' : '' }}">{{ $group->ip.'('.$group->dataCount .')' }}</td>
                            @endif
                        @endforeach
                    @endif
                    @for($i=0; $i<10- $IP_count; $i++)
                        <th style="min-width: 150px"></th>
                    @endfor
                    @if($IP_count>=11)
                        <th style="min-width: 150px">...</th>
                    @else
                        <th style="min-width: 150px"></th>
                    @endif
                </table>
            </td>
        </tr>
        <tr class="showLog" id="loginTime{{substr($logInLog->loginDate,0,7)}}">
            <td>
                <table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
                    <thead>
                    <tr class="info">
                        <th>登入時間</th>
                        <th>IP</th>
                        <th>登入裝置</th>
                        <th>User Agent</th>
                        <th>cfp_id</th>
                        <th>Country</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logInLog->items as $key => $item)
                        <tr>
                            <?php
                            // $sitem = explode("/i#", $item);
                            if(preg_match("/(iPod|iPhone)/", $item->userAgent))
                                $device = '手機';
                            else if(preg_match("/iPad/", $item->userAgent))
                                $device = '平板';
                            else if(preg_match("/android/i", $item->userAgent))
                                $device = '手機';
                            else
                                $device = '電腦';
                            ?>
                            <td>{{$item->created_at}}</td>
                            <td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
                            <td>{{ $device }}</td>
                            <td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
                            <td>{{$item->cfp_id}}</td>
                            <td>{{$item->country}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @foreach(array_get($logInLog->Ip,'Ip_group_items',[]) as $gpKey =>$group_items)
            <tr class="showLog" id="Ip{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}">
                <td>
                    <table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
                        <thead>
                        <tr class="info">
                            <th>登入時間</th>
                            <th>IP</th>
                            <th>登入裝置</th>
                            <th>User Agent</th>
                            <th>cfp_id</th>
                            <th>Country</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($group_items as $key => $item)
                            <tr>
                                <?php
                                // $sitem = explode("/i#", $item);
                                if(preg_match("/(iPod|iPhone)/", $item->userAgent))
                                    $device = '手機';
                                else if(preg_match("/iPad/", $item->userAgent))
                                    $device = '平板';
                                else if(preg_match("/android/i", $item->userAgent))
                                    $device = '手機';
                                else
                                    $device = '電腦';
                                ?>
                                <td>{{$item->created_at}}</td>
                                <td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
                                <td>{{ $device }}</td>
                                <td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
                                <td>{{$item->cfp_id}}</td>
                                <td>{{$item->country}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
        @foreach(array_get($logInLog->CfpID,'CfpID_group_items',[]) as $gpKey =>$group_items)
            <tr class="showLog" id="cfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}">
                <td>
                    <table class="table table-bordered" style="display: block; max-height: 500px; overflow-x: scroll;">
                        <thead>
                        <tr class="info">
                            <th>登入時間</th>
                            <th>IP</th>
                            <th>登入裝置</th>
                            <th>User Agent</th>
                            <th>cfp_id</th>
                            <th>Country</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($group_items as $key => $item)
                            <tr>
                                <?php
                                // $sitem = explode("/i#", $item);
                                if(preg_match("/(iPod|iPhone)/", $item->userAgent))
                                    $device = '手機';
                                else if(preg_match("/iPad/", $item->userAgent))
                                    $device = '平板';
                                else if(preg_match("/android/i", $item->userAgent))
                                    $device = '手機';
                                else
                                    $device = '電腦';
                                ?>
                                <td>{{$item->created_at}}</td>
                                <td><a href="{{ route('getIpUsers', [$item->ip]) }}" target="_blank">{{$item->ip}}</a></td>
                                <td>{{ $device }}</td>
                                <td>{{ str_replace("Mozilla/5.0","", $item->userAgent) }}</td>
                                <td>{{$item->cfp_id}}</td>
                                <td>{{$item->country}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    @endforeach
</table>
<script>
    $('#table_userLogin_log .hidden').hide();
    $('#table_userLogin_log td').click(function(){
        if($(this).find('.hidden').is(":visible")){
            $(this).find('.hidden').hide();
        }else{
            $(this).find('.hidden').show()
        }
    });

    $('.showLog').hide();
    $('.loginItem').click(function(){
        var sectionName =$(this).attr('data-sectionName');
        var assign_user_id=$(this).attr('data-assign_user_id');
        var yearMonth =$(this).attr('data-yearMonth');
        var ip =$(this).attr('data-ip');
        var cfpID =$(this).attr('data-cfpID');
        if(ip!=='不指定'){
            if(ip){
                window.open('/admin/users/ip/'+ip+'?assign_user_id='+ assign_user_id+'&yearMonth='+ yearMonth, '_blank');
            }else{
                window.open('/admin/users/ip/不指定?assign_user_id='+ assign_user_id+'&yearMonth='+ yearMonth +'&cfp_id='+ cfpID, '_blank');
            }
        }else{
            $('.showLog').hide();
            $('#'+sectionName).show();
        }
    });
    $('.loginItem_IP').click(function(){
        var sectionName =$(this).attr('data-sectionName');
        $('.showLog').hide();
        $('#'+sectionName).show();
    });

    $('.showRecord').click(function(){
        var getIP =$(this).attr('data-ip');
        var user_id='{{ $user->id }}';
        $('#ip10days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=10days');
        $('#ip20days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=20days');
        $('#ip30days').attr("href",'/admin/users/ip/' + getIP + '?user_id=' + user_id +'&period=30days');
    });

    $('.ipItem').click(function(){
        var getIP =$(this).attr('data-ip');
        var user_id='{{ $user->id }}';
        $('#ip10days').attr("href",'/admin/users/ip/' + getIP + '?period=10days');
        $('#ip20days').attr("href",'/admin/users/ip/' + getIP + '?period=20days');
        $('#ip30days').attr("href",'/admin/users/ip/' + getIP + '?period=30days');
    });

</script>