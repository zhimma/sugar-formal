<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

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
            $process = new Process('cd ' . $root_path . '; sudo sh ./deploy.sh');
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }
        else {
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
            $process = new Process('cd ' . $root_path . '; sudo sh ./staging.sh');
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }
        else {
            return response('hash not equal', 403);
        }
    }

    public function manualDeploy() {
        $root_path = base_path();
        $process = new Process('cd ' . $root_path . '; sudo sh ./deploy.sh');
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        return "呼叫完成";
    }
}
