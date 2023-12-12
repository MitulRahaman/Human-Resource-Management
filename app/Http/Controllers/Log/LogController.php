<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\LogService;
use App\Traits\AuthorizationTrait;

class LogController extends Controller
{
    use AuthorizationTrait;
    private $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
        View::share('main_menu', 'Logs');
        View::share('sub_menu', 'Logs');
    }
    public function index()
    {
        abort_if(!$this->setSlug('viewLog')->hasPermission(), 403, 'You don\'t have permission!');
        return \view('backend.pages.log.index');
    }
    public function fetchData()
    {
        return $this->logService->fetchData();
    }
}
