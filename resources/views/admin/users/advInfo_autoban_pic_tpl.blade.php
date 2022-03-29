@if($pic->pic??null)
<div class="autoban_pic_unit">

<input type="checkbox" id="{{str_replace('/','',$pic->pic)}}" name="pic[]" value="{{$pic->pic}}" />
<label for="{{str_replace('/','',$pic->pic)}}">

<span>
@if(($pic->operator??null) || (($pic->deleted_at??null) && $pic->deleted_at!='0000-00-00 00:00:00'))
已刪
@else
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
@endif
</span>

<img src="{{asset($pic->pic)}}" onerror="this.src='{{asset('img/filenotexist.png')}}'" />
</label>

</div>
@endif