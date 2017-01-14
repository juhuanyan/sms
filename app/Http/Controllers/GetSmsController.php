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
        $response = Curl::to('http://www.bing.com')
            ->get();
        dd($response);

        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $obj_xml = simplexml_load_string($response);
            $items = $obj_xml->Body->Deliver;

            if (!$obj_xml->Body->Deliver){
                break;
            } else {
                foreach($items as $item) {
                    $sms = new Smss();
                    $sms->jiekouid = $jiekou->id;
                    $sms->caller = $item->Caller;
                    $sms->msg = urldecode($item->Msg);
                    $sms->deliverdate = $item->DeliverDate;
                    $sms->save();
                }
            }
        }
    }
    public function customerSms(Request $request, $username) {

        $user = AdminUser::where(['username'=>$username])->first();
        $sdt = $request->sdt;
        $edt = $request->edt;
        if ($sdt && !$edt){
            $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                ->where('deliverdate', '>=', $sdt)
                ->select('caller','msg','deliverdate')
                ->get();
        } elseif(!$sdt && $edt){
            $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                ->where('deliverdate', '<=', $edt)
                ->select('caller','msg','deliverdate')
                ->get();
        } elseif($sdt && $edt) {
            $smss = Smss::where(['jiekouid'=>$user->jiekouid])
                ->whereBetween('deliverdate', [$sdt, $edt])
                ->select('caller','msg','deliverdate')
                ->get();
        } else {
            $smss = Smss::where(['jiekouid'=>$user->jiekouid])->select('caller','msg','deliverdate')->get();
        }

        return response($smss);
    }
}
