<?php

namespace App\Console;

use App\Models\Jiekou;
use App\Models\Smss;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Console\Command;

class Jiekou1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jiekou1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '接口1';

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
        $jiekou = Jiekou::where(['id'=>2])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $obj_xml = simplexml_load_string($response);
            $items = $obj_xml->Body->Deliver;
            if (!$obj_xml->Body->Deliver){
                break;
            } else {
                $jiekou->updated_at = \Carbon\Carbon::createFromTimeStamp(time(), 'Asia/Shanghai')->toDateTimeString();
                $jiekou->save();
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
}
