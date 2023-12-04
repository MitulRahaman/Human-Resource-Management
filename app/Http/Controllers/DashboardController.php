<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;

class DashboardController extends Controller
{
    use AuthorizationTrait;
    private  $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        View::share('main_menu', 'dashboard');
        View::share('sub_menu', 'dashboard');
    }
    public function index()
    {
        $hasManageRequisitionPermission = $this->hasPermission("manageRequisition");
        $hasManageLeavePermission = $this->hasPermission("manageLeaves");
        $total_requisition = $this->dashboardService->totalRequisitionRequests();
        $total_on_leave = $this->dashboardService->totalOnLeave();
        $total_pending_leave = $this->dashboardService->totalPendingLeave();
        return \view('backend.pages.dashboard', compact('hasManageRequisitionPermission','hasManageLeavePermission','total_pending_leave','total_on_leave','total_requisition'));
    }
    public function fetchRequisitionData(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $total_row = $this->dashboardService->totalRequisitionRequests();
        return response()->json([
            'data' => $this->dashboardService->fetchRequisitionData($page, $perPage),
            'total_pages' => ceil($total_row / $perPage),
        ]);
    }

    public function fetchOnLeaveData()
    {
        return $this->dashboardService->fetchOnLeaveData();
    }
    public function fetchPendingLeaveData()
    {
        return $this->dashboardService->fetchPendingLeaveData();
    }

}
