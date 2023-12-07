<?php

namespace App\Listeners;

use App\Events\RequisitionRequested;
use App\Services\RequisitionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RequisitionRequestedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use InteractsWithQueue;
    private $requisitionService;
    public $tries = 5;

    public function __construct(RequisitionService $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RequisitionRequested $event)
    {
        return $this->requisitionService->requisitionEmail($event->request);
    }
}
