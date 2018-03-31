<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckParamsWare
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
        //请求参数
        $params = $request->all();
        // if (empty($params)) {
        //     exit(json_encode(['code' => 10001, 'message' => '参数错误']));
        // }

        //校验参数是否合法
        $commonLogic = logic('Common');
        $res         = $commonLogic->checkPublicParams($params);
        if ($res === false) {
            write_log(['code' => $commonLogic->getErrorCode(), 'message' => $commonLogic->getErrorMessage(), 'request_data' => $params], 'api_params_check', 'error');
            exit(json_encode(['code' => 10002, 'message' => '参数检验不合法，请求异常']));
        }

        return $next($request);
    }

}
