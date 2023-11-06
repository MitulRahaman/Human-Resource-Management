<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
class CalenderRepository
{
    private $date, $month, $year, $day, $title, $description, $created_at, $updated_at, $deleted_at;
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function update()
    {
        $date ='';
        $indexes = array_keys($this->day, 1);
            foreach ($indexes as $i)
            {
                $date= DB::table('calender')->select('id')->where('date',$this->date[$i])->first();
                if($date)
                {
                    $date = DB::table('calender')
                        ->where('id', $date->id)
                        ->update([
                                'title' => ($this->title[$i])?  $this->title[$i]:'',
                                'description' => ($this->description[$i])? $this->description[$i]:'',
                                'updated_at' => $this->updated_at,
                            ]);
                }
                else
                {
                    $date = DB::table('calender')
                        ->insertGetId([
                            'date' => $this->date[$i],
                            'title' => ($this->title[$i])?  $this->title[$i]:'',
                            'description' => ($this->description[$i])? $this->description[$i]:'',
                            'created_at' => $this->created_at
                        ]);
                }

            }
        $indexes = array_keys($this->day, 0);
        foreach ($indexes as $i)
        {
            DB::table('calender')->where('date',$this->date[$i])->delete();
        }
        return $date;
    }
    public function getDates()
    {
        return DB::table('calender')->select('date','title', 'description')
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->get();
    }
    public function getEvents()
    {
        return DB::table('calender')->select('date as start', 'title')->get();
    }
}
