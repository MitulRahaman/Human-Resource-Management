<?php

namespace App\Http\Controllers\Calender;

use App\Http\Controllers\Controller;
use App\Services\CalenderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests\ExcelFileAddRequest;
use App\Traits\AuthorizationTrait;

class CalenderController extends Controller
{
    use AuthorizationTrait;
    private $calenderService;

    public function __construct(CalenderService $calenderService)
    {
        $this->calenderService = $calenderService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Calender');
    }
    public function index()
    {
        $hasManageCalenderPermission = $this->setSlug('manageCalender')->hasPermission();
        return \view('backend.pages.calender.index', compact('hasManageCalenderPermission'));
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
        $calender = $this->calenderService->getDates($year,$month);
        $data=
            [
                'dates' => $dates,
                'calender' => $calender,
            ];
        return response()->json($data);
    }
    public function store(Request $request)
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $this->calenderService->createCalender($request->all());
            return redirect('calender/')->with('success', "Calender Updated");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
        }
    }
    public function getEvents()
    {
        $events = $this->calenderService->getEvents();
        return response()->json($events);
    }
    public function saveEvent(Request $request)
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $this->calenderService->saveEvent($request->all());
            return redirect('calender/')->with('success', "Calender Updated");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
        }
    }
    public function upload()
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        return \view('backend.pages.calender.upload');
    }
    public function saveExcel(ExcelFileAddRequest $request)
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $calender = $this->calenderService->saveExcel($request->all());
            if($calender)
                return redirect('calender/')->with('success', "Calender Updated");
            return redirect()->back()->with('error', "Sorry! Calender could not be updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function updateEvent(Request $request)
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable'
        ]);
        if($validated)
        {

            try{
                $this->calenderService->updateEvent($request->all());
                return redirect('calender/')->with('success', "Calender Updated");
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
            }
        }
        else
            return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
    }
    public function addEvent(Request $request)
    {
        abort_if(!$this->setSlug('manageCalender')->hasPermission(), 403, 'You don\'t have permission!');
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
        ]);
        if($validated)
        {

            try{
                $this->calenderService->addEvent($request->all());
                return redirect('calender/')->with('success', "Calender Updated");
            } catch (\Exception $exception) {
                return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
            }
        }
        else
            return redirect()->back()->with('error', "OOPS! Calender could not be updated.");
    }
}
