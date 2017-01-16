<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Encore\Admin\Facades\Admin;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;


class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = Admin::user();

        $realip = $this->get_real_ip();
        $dengluip = explode(',', $user->dengluip);
        if ($user->dengluip && !in_array($realip, $dengluip)){

            return Redirect::to('admin/auth/logout')->withInput()->withErrors(array('msg' => 'IP禁止访问'));

        }
        return $next($request);
    }

    public function get_real_ip(){
        static $realip;
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $realip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else if(isset($_SERVER['HTTP_CLIENT_IP'])){
                $realip=$_SERVER['HTTP_CLIENT_IP'];
            }else{
                $realip=$_SERVER['REMOTE_ADDR'];
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR')){
                $realip=getenv('HTTP_X_FORWARDED_FOR');
            }else if(getenv('HTTP_CLIENT_IP')){
                $realip=getenv('HTTP_CLIENT_IP');
            }else{
                $realip=getenv('REMOTE_ADDR');
            }
        }
        return $realip;
    }
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed') ? trans('auth.failed') : 'These credentials do not match our records.';
    }
}
