@extends('admin.main')
@section('app-content')
<style>
.table > tbody > tr > td, .table > tbody > tr > th{
    vertical-align: middle;
}
.table > tbody > tr > th{
    text-align: center;
}
td.text-center {
    cursor: pointer;
}
.fa-plus-circle{
    color: green;
}
.fa-minus-circle{
    color: red;
}
</style>
<body style="padding: 15px;">
<div class="col col-12 col-sm-12">
{{--    <form action="{{ route('order/orderGeneratorById') }}" method='post'>--}}
{{--        {!! csrf_field() !!}--}}
{{--        <div class="form-row align-items-center">--}}
{{--            <div class="col-auto">產生會員訂單</div>--}}
{{--            <div class="col-auto">--}}
{{--                <label class="sr-only" for="uid">會員ID或email</label>--}}
{{--                <input type="text" class="form-control mb-2" name="uid" id="uid" placeholder="輸入會員ID或email, 例如12345或abc@gmail.com" style="width: 350px;">--}}
{{--            </div>--}}
{{--            <div class="col-auto">--}}
{{--                <button type="submit" class="btn btn-primary mb-2">送出</button>--}}
{{--            </div>--}}
{{--            <div class="col-auto">--}}
{{--                此功能會依據會員過去訂購紀錄來產生訂單資料--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </form>--}}
    <form action="{{ route('order/orderEcPayCheck') }}" method='get' target="_blank">
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
    <form action="{{ route('order/orderFunPointPayCheck') }}" method='get' target="_blank">
        {!! csrf_field() !!}
        <div class="form-row align-items-center">
            <div class="col-auto">FunPoint訂單反查</div>
            <div class="col-auto">
                <label class="sr-only" for="order_id">FunPoint訂單編號</label>
                <input type="text" class="form-control mb-2" name="order_id" id="order_id" placeholder="輸入FunPoint訂單，例如SG123456" style="width: 220px;">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2">送出</button>
            </div>
            <div class="col-auto">
                此功能用來反查FunPoint訂單資料是否有效
            </div>
        </div>
    </form>

    <form action="{{ route('order/orderCheckByServiceNameOrOrderId') }}" method='get'>
        {!! csrf_field() !!}
        <div class="form-row align-items-center">
            <div class="col-auto">訂單批次檢查</div>
            <div class="col-auto">
                <label class="sr-only" for="service_name">服務項目</label>
{{--                <input type="text" class="form-control mb-2" name="order_id" id="order_id" placeholder="輸入FunPoint訂單，例如SG123456" style="width: 220px;">--}}

                <select name="service_name" class="form-control">
                    <option value="">--選擇服務項目--</option>
                    <option value="VIP">VIP</option>
                    <option value="VVIP">VVIP</option>
                    <option value="hideOnline">隱藏付費</option>
                </select>

            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-2 orderCheckSubmit">送出</button>
            </div>
            <div class="col-auto">
            </div>
        </div>
    </form>

</div>
<hr>
<h1>訂單查詢</h1>
<table class="display table cell-border" id="data-table">
    <thead>
        <tr>
            <th></th>
            <th>訂單編號</th>
            <th>會員ID</th>
            <th>email</th>
            <th>BUSINESS ID</th>
            <th>訂購日期</th>
            <th>到期日</th>
            <th>購買項目</th>
            <th>付費週期</th>
            <th>付費方式</th>
            <th>扣款日期</th>
            <th>扣款失敗</th>
            <th>卡號</th>
            <th>金額</th>
            <th>金流平台</th>
            <th>異動時間</th>
            <th>定期定額</th>
{{--            <th>待補天數</th>--}}
{{--            <th>退費狀態</th>--}}
{{--            <th>退費金額</th>--}}
            <th>檢查訂單</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>訂單編號</th>
            <th>會員ID</th>
            <th>email</th>
            <th>BUSINESS ID</th>
            <th>訂購日期</th>
            <th>到期日</th>
            <th>購買項目</th>
            <th>付費週期</th>
            <th>付費方式</th>
            <th>扣款日期</th>
            <th>扣款失敗</th>
            <th>卡號</th>
            <th>金額</th>
            <th>金流平台</th>
            <th>異動時間</th>
            <th>定期定額</th>
{{--            <th>待補天數</th>--}}
{{--            <th>退費狀態</th>--}}
{{--            <th>退費金額</th>--}}
            <th>檢查訂單</th>
        </tr>
    </tfoot>
</table>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
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
        $('.orderCheckSubmit').on('click', function() {
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> 檢查中...';
            if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
            }
            setTimeout(function() {
                $this.html($this.data('original-text'));
            }, 36000);
        });

        $.noConflict();

        let table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: true,
            order: [[4, 'desc']],
            sDom: "Rlfrtip",
            pageLength: 100,
            ajax: '{!! route('order/list') !!}',
            columns: [
                {
                    className: 'text-center',
                    data: 'order_id',
                    render: function(data,type,row,meta) {
                        let str = '';
                        if (data.length == 12 &&
                            data.includes("SG") &&
                            (row.payment == 'cc_monthly_payment' || row.payment == 'cc_quarterly_payment')
                        ) {
                            str = '<i class="fa fa-plus-circle"></i>';
                        }

                        return data ? str : '';
                    }
                },
                {
                    data: 'order_id',
                    name: 'order.order_id'
                },
                { data: 'user_id', name: 'order.user_id' },
                {
                    data: 'email',
                    name: 'users.email',
                    render: function(data,type,row,meta) {
                        return data ? '<a href="users/advInfo/' + row.user_id + '" target="_blank">' + data + '</a>' : '';
                    }
                },
                { data: 'business_id', name: 'order.business_id' },
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
                {
                    data: 'pay_fail',
                    name: 'order.pay_fail',
                    render: function(data,type,row,meta) {
                        let str='';
                        if(data != null) {
                            let dd = JSON.parse(data.replace(/&quot;/g, '"'));
                            $.each(dd, function (key, value) {
                                let v = value.toString().substring(0, 10);
                                str += '<span class="badge badge-dark">' + v + '</span> ';
                            });
                        }
                        return str;
                    }
                },
                {
                    data: 'card4no',
                    name: 'order.card4no',
                    render: function(data,type,row,meta) {
                        let str='';
                        if(data != null) {
                            str = row.card6no + '******' + data;
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
                {
                    data: 'updated_at',
                    name: 'order.updated_at',
                    render: function(data,type,row,meta) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                },
                {
                    data: 'ExecStatus',
                    name: 'order.ExecStatus',
                    render: function(data,type,row,meta) {
                        let str='';
                        let payment = row.payment;
                        if (payment == 'cc_monthly_payment' || payment == 'cc_quarterly_payment') {
                            if (data == 1) {
                                str = '<span class="badge badge-success">繳費中</span>';
                            } else if (data == 0) {
                                str = '<span class="badge badge-secondary">繳費取消</span>';
                            }
                        }
                        return str;
                    }
                },
                // { data: 'remain_days', name: 'order.remain_days' },
                // { data: 'need_to_refund', name: 'order.need_to_refund' },
                // { data: 'refund_amount', name: 'order.refund_amount' },
                {
                    data: 'order_id',
                    name: 'order.order_id',
                    render: function(data,type,row,meta) {
                        let str='';
                        if (data.length == 12 &&
                            data.includes("SG") &&
                            (row.payment == 'cc_monthly_payment' || row.payment == 'cc_quarterly_payment')
                        ){
                            str = '<form action="order/orderCheckByServiceNameOrOrderId" method="get">' +
                                '<input type="hidden" name="service_name" value="'+ row.service_name +'"> ' +
                                '<input type="hidden" name="order_id" value="'+ row.order_id +'"> ' +
                                '<button type="submit" class="btn btn-primary orderCheckSubmit">訂單檢查</button>' +
                                '</from>';
                        }
                        return str;
                    }
                },
            ]
        });

        function format(d) {
            // `d` is the original data object for the row
            let html = '<table class="'+d.order_id+'" style="font-size: 10px; padding-left: 0px;"></table>';
            $.ajax({
                type: 'POST',
                url: "{{ route('order/order_log/list') }}",
                data:{
                    _token: '{{csrf_token()}}',
                    order_id: d.order_id,
                },
                success: function (xhr, status, error) {
                    $('.'+d.order_id).append(xhr.detail);
                }
            });
            return html;
        }

        $('#data-table').on('click', 'tbody td.text-center', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                $(this).html('<i class="fa fa-plus-circle"></i>');
                // This row is already open - close it
                row.child.hide();
            } else {
                $(this).html('<i class="fa fa-minus-circle"></i>');
                // Open this row
                row.child(format(row.data())).show();
            }
        });

        $('#data-table').on('requestChild.dt', function (e, row) {
            row.child(format(row.data())).show();
        });

        if(window.location.hash) {
            table.search( window.location.hash.substring(1) ).draw();
        }
    });
</script>

</body>



@stop