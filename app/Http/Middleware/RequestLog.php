<?php

namespace App\Http\Middleware;

use App\Libs\Slog as Log;
use Closure;
use Illuminate\Http\Request;

class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $arr      = parse_url($request->fullUrl());
        $path     = array_get($arr, 'path', '');
        $log_file = str_replace('/', '_', trim($path, '/'));
        //测试环境下记录请求参数日志
        if (env('APP_ENV', 'PRODUCTION') == 'TESTING' && $path) {
            Log::info(json_encode([
                'path'     => $path,
                'method'   => $request->method(),
                'request'  => $request->all(),
                'response' => json_decode($response->original),
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $log_file);
        }

        return $response;
    }
}
