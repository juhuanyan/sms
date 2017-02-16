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
    public function index(Request $request){

        $jiekou = $request->jiekou;
        switch ($jiekou) {
            case 'sms2':
                $this->sms2();
                break;

            default :
                $this->sms1();
                break;
        }
        $jiekou = Jiekou::find($request->id);
        $jiekou->updated_at = \Carbon\Carbon::createFromTimeStamp(time(), 'Asia/Shanghai')->toDateTimeString();
        $jiekou->save();
        return redirect('admin/jiekou');


    }
    public function sms1()
    {
        $jiekou = Jiekou::where(['name'=>'sms1'])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $obj_xml = simplexml_load_string($response);
            $items = $obj_xml->Body->Deliver;
            if (!$obj_xml->Body->Deliver){
                return true;
            } else {
                foreach($items as $item) {
                    $sms = new Smss();
                    $sms->jiekouid = $jiekou->id;
                    $sms->caller = $item->Caller;
                    $sms->msg = urldecode($item->Msg);
                    $sms->deliverdate = str_replace("/","-",$item->DeliverDate);
                    $sms->save();
                }
            }
        }
    }
    public function sms2()
    {
        $jiekou = Jiekou::where(['name'=>'sms2'])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $datas = json_decode($response);

            if (!$datas){
                return true;
            } else {
                foreach($datas as $item) {
                    $y = substr($item->MoTime, 0, 4);
                    $m = substr($item->MoTime, 4, 2);
                    $d = substr($item->MoTime, 6, 2);
                    $h = substr($item->MoTime, 8, 2);
                    $i = substr($item->MoTime, 10, 2);
                    $s = substr($item->MoTime, 12, 2);

                    $deliverdate = $y.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;

                    $sms = new Smss();
                    $sms->jiekouid = $jiekou->id;
                    $sms->caller = $item->Phone;
                    $sms->msg = urldecode($item->Msg);
                    $sms->deliverdate = $deliverdate;
                    $sms->save();
                }
            }
        }
    }
    public function customerSms(Request $request, $username) {
        $user = AdminUser::where(['username'=>$username])->first();
        $jiekouid = explode(',', $user->jiekouid);
        $realip = $this->get_real_ip();
        $fangwenip = explode(',', $user->fangwenip);
        if (($fangwenip && in_array($realip, $fangwenip) || !$user->fangwenip)){
            $sdt = $request->sdt;
            $edt = $request->edt;
            if ($sdt && !$edt){
                $smss = Smss::whereIn('jiekouid', $jiekouid)
                    ->where('deliverdate', '>=', $sdt.' 00:00:00')
                    ->select('caller','msg','deliverdate')
                    ->get();
            } elseif(!$sdt && $edt){
                $smss = Smss::whereIn('jiekouid', $jiekouid)
                    ->where('deliverdate', '<=', $edt.' 23:59:59')
                    ->select('caller','msg','deliverdate')
                    ->get();
            } elseif($sdt && $edt) {
                $smss = Smss::whereIn('jiekouid', $jiekouid)
                    ->whereBetween('deliverdate', [$sdt.' 00:00:00', $edt.' 23:59:59'])
                    ->select('caller','msg','deliverdate')
                    ->get();
            } else {
                $smss = Smss::whereIn('jiekouid', $jiekouid)->select('caller','msg','deliverdate')->get();
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
