<?php

namespace App\Observer;

use App\Models\SimpleTables\short_message;

class ShortMessageObserver
{
	public function __construct() 
    {

	} 	
	
    public function retrieved(short_message $sms_entry)
    {
    }
    
    public function created(short_message $sms_entry)
    {

        $sms_entry->created_by = auth()->id();
        $sms_entry->created_from = request()->path();
        $sms_entry->save();
    }    


    public function saved(short_message $sms_entry)
    {

    }


    public function deleting(short_message $sms_entry)
    {     
        $sms_entry->deleted_by = auth()->id();
        $sms_entry->deleted_from = request()->path();
        $sms_entry->save();
    }

}