@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
</style>
<body style="padding: 15px;">
<div class="col col-12 col-sm-12 col-md-8 col-lg-6">
    <form action="{{ route('order/orderGeneratorById') }}" method='post'>
        {!! csrf_field() !!}
        <div class="form-row align-items-center">
            <div class="col-auto">產生會員訂單</div>
            <div class="col-auto">
                <label class="sr-only" for="uid">會員ID或email</label>
                <input type="text" class="form-control mb-2" name="uid" id="uid" placeholder="輸入會員ID或email, 例如：12345或abc@gmail.com" style="width: 350px;">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">送出</button>
            </div>
            <div class="col-auto">
                此功能會依據會員過去訂購紀錄來產生訂單資料
            </div>
        </div>
    </form>
    <form action="{{ route('order/orderEcPayCheck') }}" method='post' target="_blank">
        {!! csrf_field() !!}
        <div class="form-row align-items-center">
            <div class="col-auto">綠界訂單反查</div>
            <div class="col-auto">
                <label class="sr-only" for="order_id">綠界訂單編號</label>
                <input type="text" class="form-control mb-2" name="order_id" id="order_id" placeholder="輸入綠界訂單，例如SG123456" style="width: 220px;">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">送出</button>
            </div>
            <div class="col-auto">
                此功能用來反查綠界訂單資料是否有效
            </div>
        </div>
    </form>
</div>
<hr>
<h1>訂單查詢</h1>
<table class="display table cell-border" id="data-table">
    <thead>
        <tr>
            <th>訂單編號</th>
            <th>會員ID</th>
            <th>email</th>
            <th>訂購日期</th>
            <th>到期日</th>
            <th>服務項目</th>
            <th>付費週期</th>
            <th>付費方式</th>
            <th>扣款日期</th>
            <th>金額</th>
            <th>金流平台</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th>訂單編號</th>
            <th>會員ID</th>
            <th>email</th>
            <th>訂購日期</th>
            <th>到期日</th>
            <th>服務項目</th>
            <th>付費週期</th>
            <th>付費方式</th>
            <th>扣款日期</th>
            <th>金額</th>
            <th>金流平台</th>
        </tr>
    </tfoot>
</table>
</body>

{{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />--}}
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://legacy.datatables.net/extras/thirdparty/ColReorderWithResize/ColReorderWithResize.js"></script>
<style>
    thead th {
        border-right: 1px;
        border-style: double;
        border-color: #dddddd;
    }
    thead th:last-child {
        border-right: unset;
    }
    thead th:first-child {
        border-left: unset;
    }
</style>
<script type="text/javascript">
    $(document).ready( function () {

        $.noConflict();
        let table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            sDom: "Rlfrtip",
            pageLength: 100,
            ajax: '{!! route('order/list') !!}',
            columns: [
                { data: 'order_id', name: 'order.order_id' },
                { data: 'user_id', name: 'order.user_id' },
                {
                    data: 'email',
                    name: 'users.email',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="users/advInfo/' + row.user_id + '" target="_blank">' + data + '</a>' : '';
                    }
                },
                {
                    data: 'order_date',
                    name: 'order.order_date',
                    render: function(data,type,row,meta) {
                        return data ? data.toString().substring(0, 10) : '';
                    }
                },
                {
                    data: 'order_expire_date',
                    name: 'order.order_expire_date',
                    render: function(data,type,row,meta) {
                        return data ? data.toString().substring(0, 10) : '';
                    }
                },
                {
                    data: 'service_name',
                    name: 'order.service_name'/*,
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case 'hideOnline':
                                text = '隱藏付費';
                                break;
                            case null:
                                text = '';
                                break;
                            default:
                                text = data;
                        }
                        return text;
                    }*/
                },
                {
                    data: 'payment',
                    name: 'order.payment'/*,
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case null:
                                text = '定期月繳';
                                break;
                            case 'cc_quarterly_payment':
                                text = '定期季繳';
                                break;
                            case 'cc_monthly_payment':
                                text = '定期月繳';
                                break;
                            case 'one_quarter_payment':
                                text = '單次季繳';
                                break;
                            case 'one_month_payment':
                                text = '單次月繳';
                                break;
                            default:
                                text = data;
                        }
                        return text;
                    }*/
                },
                {
                    data: 'payment_type',
                    name: 'order.payment_type'/*,
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case null:
                                text = '信用卡';
                                break;
                            case 'Credit_CreditCard':
                                text = '信用卡';
                                break;
                            case 'BARCODE_BARCODE':
                                text = '超商條碼';
                                break;
                            case 'CVS_CVS':
                                text = '超商代碼';
                                break;
                            case 'ATM_TAISHIN':
                                text = '台新銀行 ATM';
                                break;
                            case 'ATM_BOT':
                                text = '台灣銀行 ATM';
                                break;
                            case 'ATM_CHINATRUST':
                                text = '中國信託 ATM';
                                break;
                            case 'ATM_FIRST':
                                text = '第一銀行 ATM';
                                break;
                            case 'ATM_LAND':
                                text = '土地銀行 ATM';
                                break;
                            default:
                                text = data;
                        }
                        return text;
                    } */
                },
                {
                    data: 'pay_date',
                    name: 'order.pay_date',
                    render: function(data,type,row,meta) {
                        let str='';
                        if(data != null) {
                            let dd = JSON.parse(data.replace(/&quot;/g, '"'));
                            $.each(dd, function (key, value) {
                                let v = value.toString().substring(0, 10);
                                str += '<span class="badge badge-info">' + v + '</span> ';
                            });
                        }
                        return str;
                    }
                },
                { data: 'amount', name: 'order.amount' },
                {
                    data: 'payment_flow',
                    name: 'order.payment_flow'/*,
                    render: function(data,type,row,meta) {
                        let text;
                        switch(data) {
                            case 'newebpay':
                                text = '藍新';
                                break;
                            case 'ecpay':
                                text = '綠界';
                                break;
                            default:
                                text = data;
                        }
                        return text;
                    }*/
                },
            ]
        });

        if(window.location.hash) {
            table.search( window.location.hash.substring(1) ).draw();
        }
    });
</script>


@stop