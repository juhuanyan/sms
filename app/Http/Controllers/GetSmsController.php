<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Jiekou;
use App\Models\Smss;
use App\User;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class GetSmsController extends Controller
{
    public function index(){
        $jiekou = Jiekou::where(['name'=>'sms2'])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $datas = json_decode($response);
            dd($datas);
            if (!$datas){
                break;
            } else {
                foreach($datas as $item) {
                    $sms = new Smss();
                    $sms->jiekouid = $jiekou->id;
                    $sms->caller = $item->Phone;
                    $sms->msg = urldecode($item->Msg);
                    $sms->deliverdate = $item->MoTime;
                    $sms->save();
                }
            }
        }
    }
    public function customerSms(Request $request, $username) {
        $user = AdminUser::where(['username'=>$username])->first();
        $realip = $this->get_real_ip();
        $fangwenip = explode(',', $user->fangwenip);
        if (($fangwenip && in_array($realip, $fangwenip) || !$user->fangwenip)){
            $sdt = $request->sdt;
            $edt = $request->edt;
            if ($sdt && !$edt){
                $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                    ->where('deliverdate', '>=', $sdt.' 00:00:00')
                    ->select('caller','msg','deliverdate')
                    ->get();
            } elseif(!$sdt && $edt){
                $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                    ->where('deliverdate', '<=', $edt.' 23:59:59')
                    ->select('caller','msg','deliverdate')
                    ->get();
            } elseif($sdt && $edt) {
                $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                    ->whereBetween('deliverdate', [$sdt.' 00:00:00', $edt.' 23:59:59'])
                    ->select('caller','msg','deliverdate')
                    ->get();
            } else {
                $smss = Smss::where(['jiekouid'=>$user->jiekouid])->select('caller','msg','deliverdate')->get();
            }

            return response($smss);
        } else {
            return response(['status'=>500,'msg'=>'非法访问!!!'], 500);
        }
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
}
