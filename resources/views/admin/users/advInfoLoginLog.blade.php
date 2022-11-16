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
                    @php
                        $CfpIDLogInLog = array_get($logInLog->CfpID,'CfpID_group',[]);
                        $CfpID_link_array = [];
                    @endphp
                    @if($CFP_count>0)
                        @foreach($CfpIDLogInLog as $gpKey =>$group)
                            @if($logInLog->CfpID['CfpID_online_people'][$gpKey] > 1)
                                <td nowrap class="loginItem" id="showcfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="cfpID{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-assign_user_id="{{ $user->id }}" data-yearMonth="{{substr($logInLog->loginDate,0,7)}}" data-cfpID="{{$group->cfp_id}}" data-blocked-people="{{ $logInLog->CfpID['CfpID_blocked_people'][$gpKey] }}" data-online-people="{{ $logInLog->CfpID['CfpID_online_people'][$gpKey] }}" data-count="{{ $group->dataCount }}" style="margin-left: 20px;min-width: 100px;{{ $group->CfpID_set_auto_ban ? 'background:yellow;' : '' }}">{{ $group->cfp_id }} <span class="cfp_bp" style="{{ $logInLog->CfpID['CfpID_blocked_people'][$gpKey] > 0 ? 'background-color: yellow;' : '' }}">[{{ $logInLog->CfpID['CfpID_blocked_people'][$gpKey] }}/{{ $logInLog->CfpID['CfpID_online_people'][$gpKey] }}]</span></td>
                                @php
                                    $CfpID_link_array[$group->cfp_id] = '<td class="loginItem" data-sectionName="cfpID' . substr($logInLog->loginDate,0,7) . '_group' . $gpKey . '" data-assign_user_id="' .  $user->id  . '" data-yearMonth="' . substr($logInLog->loginDate,0,7) . '" data-cfpID="' . $group->cfp_id . '" data-blocked-people="' .  $logInLog->CfpID['CfpID_blocked_people'][$gpKey]  . '" data-online-people="' .  $logInLog->CfpID['CfpID_online_people'][$gpKey]  . '" data-count="' .  $group->dataCount  . '" style="margin-left: 20px;min-width: 100px;' .  ($group->CfpID_set_auto_ban ? 'background:yellow;' : '')  . '">' .  $group->cfp_id  . ' <span class="cfp_bp" style="' .  ($logInLog->CfpID['CfpID_blocked_people'][$gpKey] > 0 ? 'background-color: yellow;' : '')  . '">[' .  $logInLog->CfpID['CfpID_blocked_people'][$gpKey]  . '/' .  $logInLog->CfpID['CfpID_online_people'][$gpKey]  . ']</span></td>';
                                @endphp
                            @else
                                @php
                                    $CfpID_link_array[$group->cfp_id] = '<td class="loginItem" data-sectionName="cfpID' . substr($logInLog->loginDate,0,7) . '_group' . $gpKey . '" data-assign_user_id="' .  $user->id  . '" data-yearMonth="' . substr($logInLog->loginDate,0,7) . '" data-cfpID="' . $group->cfp_id . '" data-blocked-people="' .  $logInLog->CfpID['CfpID_blocked_people'][$gpKey]  . '" data-online-people="' .  $logInLog->CfpID['CfpID_online_people'][$gpKey]  . '" data-count="' .  $group->dataCount  . '" style="margin-left: 20px;min-width: 100px;' .  ($group->CfpID_set_auto_ban ? 'background:yellow;' : '')  . '">' .  $group->cfp_id  . '</td>';
                                @endphp
                            @endif
                            
                        @endforeach
                    @endif
                    @if($IP_count>0)
                        @php
                            $IpLogInLog = array_get($logInLog->Ip,'Ip_group',[]);
                        @endphp
                        @foreach($IpLogInLog as $gpKey =>$group)
                            @if($logInLog->Ip['Ip_online_people'][$gpKey] > 1)
                                <td nowrap class="loginItem ipItem" id="showIp{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-sectionName="Ip{{substr($logInLog->loginDate,0,7)}}_group{{$gpKey}}" data-assign_user_id="{{ $user->id }}" data-yearMonth="{{substr($logInLog->loginDate,0,7)}}" data-ip="{{ $group->ip }}" data-blocked-people="{{ $logInLog->Ip['Ip_blocked_people'][$gpKey] }}" data-online-people="{{ $logInLog->Ip['Ip_online_people'][$gpKey] }}" data-count="{{ $group->dataCount }}" style="margin-left: 20px;min-width: 150px;{{ $group->IP_set_auto_ban ? 'background:yellow;' : '' }}">
                                    {{ $group->ip }} 
                                    <span class="cfp_bp" style="{{ $logInLog->Ip['Ip_blocked_people'][$gpKey] > 0 ? 'background-color: yellow;' : '' }}">
                                        [{{ $logInLog->Ip['Ip_blocked_people'][$gpKey] }}/{{ $logInLog->Ip['Ip_online_people'][$gpKey] }}]
                                    </span>
                                </td>
                            @endif
                        @endforeach
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
                                @if($item->cfp_id != '')
                                    {!!$CfpID_link_array[$item->cfp_id]!!}
                                @else
                                    <td>{{$item->cfp_id}}</td>
                                @endif
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
                                @if($item->cfp_id != '')
                                    {!!$CfpID_link_array[$item->cfp_id]!!}
                                @else
                                    <td>{{$item->cfp_id}}</td>
                                @endif
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
                                @if($item->cfp_id != '')
                                    {!!$CfpID_link_array[$item->cfp_id]!!}
                                @else
                                    <td>{{$item->cfp_id}}</td>
                                @endif
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