
<style>
    .show {margin-top:50px;maring-bottom:10px;}
    table,tr,td,th {border-width:3px; border-style:solid;border-collapse: collapse;border-spacing:0;}
    .error {color:red;font-weight:bolder;}
    td, th{ padding:5px;text-align: center;vertical-align: middle;}
    th {background-color:#c9c8c7}
    th.cfp_id_th {background-color:#a3bec2}
   
 </style>
 <script>

    function doCheck() {
        
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/checkDuplicate");
        xhr.send();
        alert('開始產生數據，結束後將自動重新整理頁面');
        xhr.onload = function () {

            response = xhr.responseText;
            
            if (200 <= xhr.status && xhr.status <= 299) {
                if(response=='1') {
                    location.reload();
                }
                else {
                   alter('執行失敗，錯誤訊息:'+response);
                }
            }
            else {
                alert('執行失敗，錯誤代碼:'+xhr.status);
            }

        };        
    }
     
</script>
 
<!--{{--
<form method="GET" >
    <div><label >開始日期：</label><input name="sdate" placeholder="YYYY/MM/DD" value="{{request()->sdate?:'2021/06/23'}}"/></div>
    
    <div><label >結束日期：</label><input name="edate"  placeholder="YYYY/MM/DD" value="{{request()->edate?:date('Y/m/d')}}" /></div>
    <input type="submit" name="submit" value="送出" />
</form>
@if ($error_msg)
<div class="error">{{$error_msg}}</div>
@endif
--}}-->
<div>
    <form method="get" >
    <input type="button" name="check" value="產出數據" onclick="doCheck();return false;" />
    <input type="submit" name="clear" value="清空數據" />
    </form>
</div>


@forelse ($columnSet as $g=>$col)
<div class="show">
    <h2>第 {{ $g+1 }} 組</h2>
    <table>
        <tr>
            <th ></th>
    @foreach ($col as $c=> $colName)
            <th class="{{$columnTypeSet[$g][$c]}}_th"> {{$columnTypeSet[$g][$c]}} ：{{$colName}} </th>
    @endforeach
        </tr>
    @foreach ($rowSet[$g] as $r=>$rowName)
        <tr>
            <th>{{$rowName}}</th>
        @for ($n=0;$n<count($col);$n++)
            <td> {{(isset($cellValue[$g][$r][$n]))?$cellValue[$g][$r][$n]->time.' ( '.$cellValue[$g][$r][$n]->num.' 次 ) ':'無'}}</td>
        @endfor
        </tr>
    @endforeach   
    </table>
</div>
@empty
    <div>無資料</div>
@endforelse