<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 初次使用時，要確定
 * 1. nginx/apache 和 PHP 的使用者擁有免密碼 sudo 的權限
 * 2. 最好在 sudo 後再執行這段：git config --global --add safe.directory {專案路徑}
 *    {專案路徑} 要置換為實際值
 */
class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $payload = $request->getContent();
        $hash = $request->header('X-Hub-Signature') ?? $request->header('X-Gitlab-Token');
        if ($hash == 'test') {
            echo 'test';
        }
        $localToken = config('app.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $payload, $localToken, false);
        if ($hash == $localToken) {
            $root_path = base_path();
            $result = shell_exec('cd ' . $root_path . '; sudo sh ./deploy.sh 2>&1');
            \Sentry\captureMessage($result);
            logger($result);
        }
        else {
            \Sentry\captureMessage('hash not equal');
            logger('hash not equal');
            return response('hash not equal', 403);
        }
    }

    public function staging(Request $request)
    {
        $payload = $request->getContent();
        $hash = $request->header('X-Hub-Signature') ?? $request->header('X-Gitlab-Token');
        $localToken = config('app.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $payload, $localToken, false);
        if ($hash == $localToken) {
            $root_path = base_path();
            $result = shell_exec('cd ' . $root_path . '; sudo sh ./staging.sh 2>&1');
            \Sentry\captureMessage($result);
            logger($result);
        }
        else {
            \Sentry\captureMessage('hash not equal');
            logger('hash not equal');
            return response('hash not equal', 403);
        }
    }

    public function manualDeploy() {
        ini_set('opcache.enable', '0');
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        $root_path = base_path();
        $result = shell_exec('cd ' . $root_path . '; sudo sh ./deploy.sh 2>&1');
        \Sentry\captureMessage('production manually deployed' . $result);
        logger('production manually deployed' . $result);
        ini_set('opcache.enable', '1');
        return "呼叫完成";
    }
}
