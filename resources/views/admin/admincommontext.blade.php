@extends('admin.main')
@section('app-content')
<body style="padding: 15px;">
    <h1>編輯文案</h1>
    <table class="table-bordered table-hover center-block" style="width: 100%;" id="table">
        <tr>
            <th class="text-center">說明</th>
            <th class="text-center">目錄</th>
            <th class="text-center">預覽內容</th>
            <th class="text-center">編輯框 NAME取代對方名稱 DATE取代VIP到期日</th>
            <th class="text-center" style="white-space:nowrap;">操作</th>
        </tr>
        @foreach($commontext as $a)
        <form action="{{ route('admin/commontext/save', $a->id) }}" method="post">
            {!! csrf_field() !!}
            <tr class="template">
                <td class="text-center show" style="white-space:nowrap;">
                    {{ $a->title }}
                </td>
                <td class="text-center show" style="white-space:nowrap;">
                    {{ $a->category }}
                </td>
                <td style="width: 35%;">{!! nl2br($a->content) !!}</td>
                <td id="show"><div contenteditable="false" class="div2input" style="border:1px #C0C0C0 solid;padding:2px;">{!! nl2br($a->content) !!}</div></td>
                <td id="hide"><textarea style="background:transparent;"  name="content2" cols="80">{{ $a->content }}</textarea></td>
                <td>
    				<input type="hidden" class="input2post" name="content" vlaue=""> 
                    <input type="hidden" name="id" value="{{ $a->id }}">
                    <input type="button" class='text-white btn btn-danger' name="source" value="原始碼">
                    <br>
                    <br>
                    <input id="save" name="save" type="submit" class='text-white btn btn-success' value="送出修改">
                </td>
            </tr>
        </form>
        @endforeach
    </table>
</body>
<script type="text/javascript">
    //原碼框自適應高度
    $('textarea').each(function () {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    //DIV內容送表單
    $("input[name='save']").each( function(){
        $(this).bind("click" , function(){
            var id = $("input[name='save']").index(this);
            var val = $(".div2input").eq(id).html();
            $(".input2post").eq(id).val(val);
        });
    });

    //先隱藏原碼 點擊可切換原碼或切回
    var hidetds = $("td[id='hide']");   
    var showtds = $("td[id='show']");   
    for(i = 0; i < hidetds.length; i++){     
        hidetds[i].style.display = "none";
    }
    $("input[name='source']").each( function(){
        $(this).bind("click" , function(){
            var id = $("input[name='source']").index(this);
            hidetds[id].style.display = (hidetds[id].style.display == "none")? "": "none";
            showtds[id].style.display = (showtds[id].style.display == "none")? "": "none";
        });
    });

</script>
@stop
