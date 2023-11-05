<?php

namespace App\Http\Controllers\Calender;

use App\Http\Controllers\Controller;
use App\Services\CalenderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CalenderController extends Controller
{
    private $calenderService;

    public function __construct(CalenderService $calenderService)
    {
        $this->calenderService = $calenderService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Calender');
    }
    public function index()
    {
        return \view('backend.pages.calender.index');
    }
    public function manage()
    {
        return \view('backend.pages.calender.manage');
    }
    public function getDates(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $dates = [];
        for ($day = 1; $day <= Carbon::create($year, $month)->daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dates[] = $date->toDateString();
        }
        return response()->json($dates);
    }
    public function store(Request $request)
    {
        try{
            $calender = $this->calenderService->createCalender($request->all());
            if($calender)
            return redirect('calender/')->with('success', "Calender Updated");
            else
                return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
        }
    }
}
