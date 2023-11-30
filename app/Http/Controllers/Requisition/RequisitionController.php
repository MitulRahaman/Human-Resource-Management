<?php

namespace App\Http\Controllers\Requisition;

use App\Services\RequisitionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class RequisitionController extends Controller
{
    private $requisitionService;

    public function __construct(RequisitionService $requisitionService)
    {
        $this->requisitionService = $requisitionService;
        View::share('main_menu', 'Requisition');
    }
    public function index()
    {
        View::share('sub_menu', 'Manage');
        return view('backend.pages.requisition.index');
    }
    public function fetchData()
    {
        return $this->requisitionService->fetchData();
    }
}
