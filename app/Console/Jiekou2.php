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
        $jiekou = Jiekou::where(['name'=>'sms2'])->first();
        while (1){
            $response = Curl::to($jiekou->url)
                ->get();
            $datas = json_decode($response);

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
}
