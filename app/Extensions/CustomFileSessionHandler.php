<?php
 
namespace App\Extensions;

use Symfony\Component\Finder\Finder;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Support\Facades\Log;
 
class CustomFileSessionHandler extends FileSessionHandler
{
    public function gc($lifetime)
    {
        Log::info('test_enter_gc');
        $files = Finder::create()
                    ->in($this->path)
                    ->files()
                    ->ignoreDotFiles(true)
                    ->date('<= now - '.$lifetime.' seconds');
   
        foreach ($files as $file) {
            $this->files->delete($file->getRealPath());
        }
    }
}