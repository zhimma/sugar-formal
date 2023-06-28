<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            exec('cd ' . $root_path . '; sudo sh ./deploy.sh > ~/debug.log 2>&1');
            \Sentry\captureMessage('deployed');
            logger('deployed');
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
            exec('cd ' . $root_path . '; sudo sh ./staging.sh > ~/debug.log 2>&1');
            \Sentry\captureMessage('staging deployed');
            logger('staging deployed');
        }
        else {
            \Sentry\captureMessage('hash not equal');
            logger('hash not equal');
            return response('hash not equal', 403);
        }
    }

    public function manualDeploy() {
        $root_path = base_path();
        var_dump(exec('cd ' . $root_path . '; sudo sh ./deploy.sh > ~/debug.log 2>&1', $output, $errno), $output, $errno);
        \Sentry\captureMessage('production manually deployed');
        logger('production manually deployed');
        return "呼叫完成";
    }
}
