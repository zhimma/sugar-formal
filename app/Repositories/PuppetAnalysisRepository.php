<?php
namespace App\Repositories;

use App\Models\LogUserLogin;
use App\Models\PuppetAnalysisCell;
use App\Models\PuppetAnalysisColumn;
use App\Models\PuppetAnalysisRow;
use App\Models\PuppetAnalysisIgnore;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Log;



class PuppetAnalysisRepository 
{
    public function __construct() 
    {
        $this->init();                 
    } 
    
    public function init() 
    {  
        $this->user(true);
        $this->col_entry(true);
        $this->row_entry(true);
        $this->cell_entry(true);
        $this->row_list(true);
        $this->ignore_entry(true);
        $this->error_msg('');        
    } 
    

    public function riseByUser($user_entry) 
    {
        $this->riseByUserEntry($user_entry);
        return $this;    
    }
    
    public function riseByUserEntry($user_entry) 
    {
        $this->init();
        $this->user($user_entry);
        return $this;    
    }   

    public function error_msg($msg=null) 
    {
        if($msg!==null) $this->_error_msg = $msg;
        return $this->_error_msg;        
    }     

    public function user($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->user = null;  
        }
        else if($value_or_reset!==false) {
            $this->user = $value_or_reset;
        }
        
        return $this->user;
    }     

    public function cell_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->cell_entry = new PuppetAnalysisCell;  
        }
        else if($value_or_reset!==false) {
            $this->cell_entry = $value_or_reset;
        }
        
        return $this->cell_entry;
    } 

    public function row_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->row_entry = new PuppetAnalysisRow;  
        }
        else if($value_or_reset!==false) {
            $this->row_entry = $value_or_reset;
        }    
        
        return $this->row_entry;
    } 


    public function col_entry($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->col_entry = new PuppetAnalysisColumn;  
        }
        else if($value_or_reset!==false) {
            $this->col_entry = $value_or_reset;
        }
        
        return $this->col_entry;
    }
    
    public function ignore_entry($value_or_reset=false)
    {
        if($value_or_reset===true) {
            $this->ignore_entry = new PuppetAnalysisIgnore;  
        }
        else if($value_or_reset!==false) {
            $this->ignore_entry = $value_or_reset;
        }
        
        return $this->ignore_entry;        
    }

    public function row_list($value_or_reset=false) 
    {
        if($value_or_reset===true) {
            $this->row_list = null;  
        }
        else if($value_or_reset!==false) {
            $this->row_list = $value_or_reset;
        }        
        
        return $this->row_list;
    } 
    
    
}
