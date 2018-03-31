<?php
namespace App\Http\Middleware;

use App\Libs\Slog as Log;
use Closure;
use Illuminate\Http\Request;

/**
 * 测试环境下，记录请求日志
 */
class RequestLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $arr      = parse_url($request->fullUrl());
        $path     = array_get($arr, 'path', '');
        //测试环境下，将请求不存在路由记录到日志
        if (strpos($path, '/api') !== false) {
            Log::record(json_encode([
                'path'        => $path,
                'app_version' => $request->header('App-Version'),
                'app_utm'     => $request->header('App-Utm'),
                'method'      => $request->method(),
                'request'     => $request->all(),
                'response'    => json_decode($response->original),
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), "api_request");
        }

        return $response;
    }
}
