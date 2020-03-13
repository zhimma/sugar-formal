<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class Sms extends Notification{

	/**
	*	Sms Vendor. 
	*	@var class Default is mitake.
	*/
	private $smsVendor;

	public function __construct(SmsMitake $smsVendor){

	}
}

class SmsMitake{

}

// 新增介面
// 創建SMS資料夾
?>