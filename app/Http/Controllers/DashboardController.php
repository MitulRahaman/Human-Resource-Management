<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    use AuthorizationTrait;

    private  $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        View::share('main_menu', 'Dashboard');
        View::share('sub_menu', 'Dashboard');
    }
    public function index()
    {
        $hasManageRequisitionPermission = $this->setSlug(Config::get('variable_constants.permission.manageRequisition'))->hasPermission();
        $hasManageLeavePermission = $this->setSlug(Config::get('variable_constants.permission.manageLeaves'))->hasPermission();
        $total=[
            'requisition'=> $this->dashboardService->totalRequisitionRequests(),
            'on_leave' => $this->dashboardService->totalOnLeave(),
            'pending_leave' => $this->dashboardService->totalPendingLeave(),
            'pending_requisition' => $this->dashboardService->totalPendingRequisition(),
            'users' => $this->dashboardService->totalUser(),
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
