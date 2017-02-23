<?php

namespace App\Console;

use App\Models\Jiekou;
use App\Models\Smss;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Console\Command;

class Jiekou2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jiekou2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '接口2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    static public function quote()
    {
        $jiekou = Jiekou::where(['id'=>3])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $datas = json_decode($response);

            if (!$datas){
                break;
            } else {
                $jiekou->updated_at = \Carbon\Carbon::createFromTimeStamp(time(), 'Asia/Shanghai')->toDateTimeString();
                $jiekou->save();
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
}
