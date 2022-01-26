@if(isset($_GET['status']))
    @if($_GET['status']=='false')
        <span style="color:red">資料輸入錯誤，請重新驗證</span>
    @elseif($_GET['status']=='age_failed')
        <span style="color:red">年齡未滿18歲，不得進行驗證</span>
    @endif
@endif
<form id="advance_auth_form" name="advance_auth_form" class="m-form m-form--fit" method="POST" action="/advance_auth_process">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <input type="hidden" name="userId" value="{{$user->id}}">

    <div class="de_input mtb20">
        <div class="zybg_new02 @if(in_array('i',Session::get('error_code')??[])) has_error @endif">                                       
            <input name="id_serial" id="id_serial" type="text" class="xy_input xy_left wbd" placeholder="請輸入身分證字號"  autocomplete="off">
        </div>
        <div class="zybg_new02 @if(in_array('p',Session::get('error_code')??[])) has_error @endif"  onclick="origin_phone_popup('{{$user->getAuthMobile(true)}}');return false;">
            <select name="phone_type" class="zy_select">
                <option>台灣</option>
            </select>
            <input @if($user->isPhoneAuth()) value="{{$user->getAuthMobile(true)}}" @endif style="@if($user->isPhoneAuth()) display:none;  @endif" name="phone_number" id="phone_number" type="text" class="xy_input xy_left" placeholder="請輸入手機號碼"  autocomplete="off">
            @if($user->isPhoneAuth())
            <input autocomplete="off" value="{{$user->getAuthMobile(true)}} (已驗證)" class="xy_input xy_left only_show"  disabled>
            @endif
        </div>

        <div class="zybg_new02 birthday_selector @if(in_array('b',Session::get('error_code')??[])) has_error @endif">
            <em>生日</em>
            <div class="se_zlman left">
              <select data-parsley-errors-messages-disabled name="year" id="year"  class="xy_input select_xx04 sel_year">
              </select>
            </div>
            <div class="se_zlman left">
              <select data-parsley-errors-messages-disabled name="month" id="month"  class="xy_input select_xx04 sel_month">
              </select>
            </div>  
            <div class="se_zlman left">
              <select data-parsley-errors-messages-disabled name="day" id="day"  class="xy_input select_xx04 sel_day">
              </select>
            </div>                                              
        </div>

        <button type="text" class="n_zybg_right btn_yz advanceAuthSubmit" onclick="tab_agree();return false;">驗證</button>
    </div>
</form>