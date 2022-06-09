<?php
 
namespace App\Extensions;

use Symfony\Component\Finder\Finder;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Support\Facades\Log;
use App\Models\SetAutoBan;
 
class CustomFileSessionHandler extends FileSessionHandler
{
    public function gc($lifetime)
    {
        $files = Finder::create()
                    ->in($this->path)
                    ->files()
                    ->ignoreDotFiles(true)
                    ->date('<= now - '.$lifetime.' seconds');
   
        foreach ($files as $file) {
            $user_id = 0;
            foreach (unserialize($this->files->get($file->getRealPath())) as $key => $value) {
                if(substr_count($key, 'login_web') > 0){
                    $user_id = $value;
                }
            }
            //Log::Info($user_id);
            SetAutoBan::logout_warned($user_id);
            $this->files->delete($file->getRealPath());
        }
    }
}