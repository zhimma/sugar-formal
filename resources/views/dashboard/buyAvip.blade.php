@extends('layouts.website')
@section('app-content')
<style>
	.container .item {
		display: inline;
	}
</style>
<div class="m-portlet__head">
    <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
            <h3 class="m-portlet__head-text">
                購買 VIP 資格
            </h3>
        </div>
    </div>
</div>

<div class="m-portlet__body">

<div class="row">
   	<div class="col-lg-12">
   		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							網站專屬 Vip
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<h3>價格: 888 $NTD / 每月</h3><br>
                <p>購買後，可獲得額外權限，如檢視對方是否已讀、對方的進階資料等，並可隨時停止付款。(停止付款後，權限會維持至最近一次付款的效期終止日，如10/1付款，10/23停止付款，則權限將持續至10/30)</p>
                <p>客服Email：mmmaya111@gmail.com</p>
                <div class="container">&nbsp;
                    <div class="item">付款方式：<img src="/img/cclogos.jpg" style="width: 50%; margin-bottom: 0"></div>
					<div class="item"><button type="submit" class="btn btn-danger">付費升級VIP</button>&nbsp;</div>
                </div>
            </div>
			</div>
		</div>
	</div>
</div>
@stop
@section('javascript')
@stop

