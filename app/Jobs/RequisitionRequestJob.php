<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use App\Mail\RequisitionMail;

class RequisitionRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        Mail::send((new RequisitionMail($this->data['data'], $this->data['assetTypeName']))->to("rbtamanna@appnap.io")->cc("rbtamannarbt@gmail.com"));
//        Mail::send((new RequisitionMail($this->data['data'], $this->data['assetTypeName']))->to($this->data['to'])->cc($this->data['from']));
    }


}
