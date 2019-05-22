<?php
namespace App\Http\Middleware;
use Carbon\Carbon;
use Closure;
class ApiDataLogger{
    private $startTime;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $this->startTime = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response){
        if ( env('API_DATALOGGER', true) ) {
            $endTime = microtime(true);
            $filename = 'api_datalogger_' . Carbon::now()->format('Y-m-d') . '.log';
            $dataToLog  = 'Time: '   . Carbon::now()->toDateTimeString() . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' . $request->ip() . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . $request->getContent() . "\n";
            //$dataToLog .= 'Output: ' . $response->getContent() . "\n";
            \File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
        }
    }
}