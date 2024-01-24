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
            ->setMonth($data['month'])
            ->setYear($data['year'])
            ->setTitle(isset($data['title']) ? $data['title']:null)
            ->setDescription(isset($data['description']) ? $data['description']:null)
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->setDeletedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function getDates($year, $month)
    {
        return $this->calenderRepository->setMonth($month)
            ->setYear($year)
            ->getDates();
    }
    public function getEvents()
    {
        $events = $this->calenderRepository->getEvents();
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'start' => $event->start,
                'title' => ($event->title),
                'description' => $event->description
            ];
        }
        return $formattedEvents;
    }
    public function saveEvent($data)
    {
        return $this->calenderRepository->setOldDate($data['oldDate'])
            ->setNewDate($data['newDate'])
            ->settitle($data['title'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->saveEvent();
    }
    public  function saveExcel($data)
    {
        return $this->calenderRepository->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->saveExcel($data['file']);
    }
    public function updateEvent($data)
    {
        return $this->calenderRepository->setDate($data['date'])
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->updateEvent();
    }
    public function addEvent($data)
    {
        return $this->calenderRepository->setDate($data['day'])
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->addEvent();
    }

}
