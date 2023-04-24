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
<h1>{{ $name }}的VIP記錄 @if($expiry != "0000-00-00 00:00:00" && isset($expiry)) (到期日: {{ $expiry }}) @endif</h1>
<br>
<h4 style="text-align: left">使用者訂單</h4>
<table class='display table cell-border' id="data-table">
	<thead>
		<tr>
			<th></th>
			<th>訂單編號</th>
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
			<th>檢查訂單</th>
		</tr>
	</thead>
	<tbody>
{{--	@forelse ($order as $row)--}}
{{--		<tr>--}}
{{--			<td>{{$row->order_id}}</td>--}}
{{--			<td>{{ substr($row->order_date, 0, 10) }}</td>--}}
{{--			<td>{{ substr($row->order_expire_date, 0, 10) }}</td>--}}
{{--			<td>{{$row->service_name}}</td>--}}
{{--			<td>{{$row->payment}}</td>--}}
{{--			<td>{{$row->payment_type}}</td>--}}
{{--			<td>--}}
{{--				@php--}}
{{--				$payDate = json_decode($row->pay_date, true);--}}
{{--				@endphp--}}
{{--				@foreach($payDate as $key => $value)--}}
{{--					<span class="badge badge-info">{!! substr($value[0], 0, 10) !!}</span>--}}
{{--				@endforeach--}}

{{--			</td>--}}
{{--			<td>{{$row->amount}}</td>--}}
{{--			<td>{{$row->payment_flow}}</td>--}}


{{--		</tr>--}}
{{--	@empty--}}
{{--		<tr>--}}
{{--			<td colspan="9">找不到資料</td>--}}

{{--		</tr>--}}
{{--	@endforelse--}}
	</tbody>
{{--	<tfoot>--}}
{{--		<tr>--}}
{{--			<th></th>--}}
{{--			<th>訂單編號</th>--}}
{{--			<th>訂購日期</th>--}}
{{--			<th>到期日</th>--}}
{{--			<th>購買項目</th>--}}
{{--			<th>付費週期</th>--}}
{{--			<th>付費方式</th>--}}
{{--			<th>扣款日期</th>--}}
{{--			<th>扣款失敗</th>--}}
{{--			<th>卡號</th>--}}
{{--			<th>金額</th>--}}
{{--			<th>金流平台</th>--}}
{{--			<th>異動時間</th>--}}
{{--			<th>定期定額</th>--}}
{{--			<th>檢查訂單</th>--}}
{{--		</tr>--}}
{{--	</tfoot>--}}
</table>
<br><hr><br>
<table class='table table-bordered table-hover'>
	@if(isset($VIP))
	<tr>
		<th colspan="6" style="text-align: left;">{{$VIP->order_id}}  @if($VIP->active==1)<span class="badge badge-success">VIP</span>@else <span class="badge badge-secondary">取消</span>@endif</th>
	</tr>
	@endif
	<tr>
		<th>VIP 動作</th>
		<th>TXN ID</th>
        <th>Action</th>
        <th>是否免費</th>
		<th>資料建立時間</th>
        <th>資料建立時間</th>
    </tr>
	@forelse ($results as $result)
	<tr>
		<td>{{ $result->member_name }}</td>
		<td>{{ $result->txn_id }}</td>
        <td>{{ $result->action }}</td>
        <td>@if($result->free==1)是 @else否 @endif</td>
		<td>{{ $result->created_at }}</td>
        <td>{{ $result->updated_at }}</td>
	</tr>
	@empty
	<tr>
		<td colspan="6">找不到資料</td>
	</tr>
	@endforelse
</table>
<br><hr><br>
<table class='table table-bordered table-hover'>
	@if(isset($VVIP))
	<tr>
		<th colspan="7" style="text-align: left;">{{$VVIP->order_id}}  @if($VVIP->active==1)<span class="badge badge-success">VVIP</span>@else <span class="badge badge-secondary">取消</span>@endif</th>
	</tr>
	@endif
	<tr>
		<th>VVIP 動作</th>
		<th>服務項目</th>
		<th>訂單編號</th>
		<th>TXN ID</th>
		<th>Action</th>
		<th>資料建立時間</th>
		<th>資料建立時間</th>
	</tr>
	@forelse ($vvip_log_data as $row)
		<tr>
			<td>{{ $row->content }}</td>
			<td>{{ $row->service_name }}</td>
			<td>{{ $row->order_id }}</td>
			<td>{{ $row->txn_id }}</td>
			<td>{{ $row->action }}</td>
			<td>{{ $row->created_at }}</td>
			<td>{{ $row->updated_at }}</td>
		</tr>
	@empty
		<tr>
			<td colspan="7">無資料</td>
		</tr>
	@endforelse
</table>
<br><hr><br>
<table class='table table-bordered table-hover'>
	@if(isset($hideOnline))
	<tr>
		<th colspan="7" style="text-align: left;">{{$hideOnline->order_id}}  @if($hideOnline->active==1)<span class="badge badge-success">隱藏付費</span>@else <span class="badge badge-secondary">取消</span>@endif</th>
	</tr>
	@endif
	<tr>
		<th>隱藏付費 動作</th>
		<th>服務項目</th>
		<th>訂單編號</th>
		<th>TXN ID</th>
		<th>Action</th>
		<th>資料建立時間</th>
		<th>資料建立時間</th>
	</tr>
	@forelse ($hideOnline_log_data as $row)
		<tr>
			<td>{{ $row->content }}</td>
			<td>{{ $row->service_name }}</td>
			<td>{{ $row->order_id }}</td>
			<td>{{ $row->txn_id }}</td>
			<td>{{ $row->action }}</td>
			<td>{{ $row->created_at }}</td>
			<td>{{ $row->updated_at }}</td>
		</tr>
	@empty
		<tr>
			<td colspan="7">無資料</td>
		</tr>
	@endforelse
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

		$.noConflict();

		var url = $(location).attr('href'),
				parts = url.split("/"),
				last_part = parts[parts.length-2];

		let table = $('#data-table').DataTable({
			processing: true,
			serverSide: true,
			responsive: true,
			autoWidth: true,
			order: [[2, 'desc']],
			sDom: "Rlfrtip",
			pageLength: 40,
			ajax: '../../order/list/' + {{ $user_id }},
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
				{ data: 'business_id', name: 'order.business_id' },
				// {
				// 	data: 'email',
				// 	name: 'users.email',
				// 	render: function(data,type,row,meta) {
				// 		return data ? '<a href="users/advInfo/' + row.user_id + '" target="_blank">' + data + '</a>' : '';
				// 	}
				// },
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
							str = '<form action="../../order/orderCheckByServiceNameOrOrderId" method="get">' +
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
</html>
@stop