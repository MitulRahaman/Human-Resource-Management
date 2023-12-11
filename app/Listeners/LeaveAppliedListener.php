<?php

namespace App\Listeners;

use App\Events\LeaveApplied;
use App\Services\LeaveApplyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LeaveAppliedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    use InteractsWithQueue;
    private $leaveApplyService;
    public $tries = 1;

    public function __construct(LeaveApplyService $leaveApplyService)
    {
        $this->leaveApplyService = $leaveApplyService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LeaveApplied $event)
    {
        return $this->leaveApplyService->LeaveApplicationEmail($event->request);
    }
}
