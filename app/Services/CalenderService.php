<?php

namespace App\Services;

use App\Repositories\CalenderRepository;
use Illuminate\Support\Facades\Config;

class CalenderService
{
    private $calenderRepository;
    public function __construct(CalenderRepository $calenderRepository)
    {
        $this->calenderRepository = $calenderRepository;
    }
    public function createCalender($data)
    {
        return $this->calenderRepository->setDate($data['date'])
            ->setDay($data['day'])
            ->setTitle(isset($data['title']) ? $data['title']:null)
            ->setDescription(isset($data['description']) ? $data['description']:null)
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->setDeletedAt(date('Y-m-d H:i:s'))
            ->update();
    }

}
