<?php

namespace App\Exceptions;

use Sentry\Tracing\SamplingContext;

class Sentry
{
    public static function tracesSampler(SamplingContext $context): float
    {
        // The code you would have placed in the closure...
        // logger(print_r($context, true));
        return 0.05;
    }
}