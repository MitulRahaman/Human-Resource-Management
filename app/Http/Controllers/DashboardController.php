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
        $hasManageRequisitionPermission = $this->hasPermission(Config::get('variable_constants.permission.manageRequisition'));
        $hasManageLeavePermission = $this->hasPermission(Config::get('variable_constants.permission.manageLeaves'));
        $total=[
            'requisition'=> $this->dashboardService->totalRequisitionRequests(),
            'on_leave' => $this->dashboardService->totalOnLeave(),
            'pending_leave' => $this->dashboardService->totalPendingLeave(),
        ];
        return \view('backend.pages.dashboard', compact('hasManageRequisitionPermission','hasManageLeavePermission','total'));
    }
    public function fetchRequisitionData(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = 10;
        $total_row = $this->dashboardService->totalRequisitionRequests();
        return response()->json([
            'data' => $this->dashboardService->fetchRequisitionData($page, $limit),
            'total_pages' => ceil($total_row / $limit),
        ]);
    }

    public function fetchOnLeaveData()
    {
        $limit = 10;
        return $this->dashboardService->fetchOnLeaveData($limit);
    }
    public function fetchPendingLeaveData()
    {
        $limit = 10;
        return $this->dashboardService->fetchPendingLeaveData($limit);
    }

}
