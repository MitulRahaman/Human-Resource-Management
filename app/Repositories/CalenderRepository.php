<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
class CalenderRepository
{
    private $date, $month, $year, $day, $title, $description, $created_at, $updated_at, $deleted_at, $oldDate, $newDate;
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
    public function setOldDate($oldDate)
    {
        $this->oldDate = $oldDate;
        return $this;
    }
    public function setNewDate($newDate)
    {
        $this->newDate = $newDate;
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
        DB::transaction(function ()
        {
            $indexes = array_keys($this->day, 1);
            foreach ($indexes as $i)
            {
                $date= DB::table('calender')->select('id')->where('date',$this->date[$i])->first();
                if($date)
                {
                    $date = DB::table('calender')
                        ->where('id', '=',$date->id)
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
        });
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
        return DB::table('calender')->select('date as start', 'title', 'description')->get();
    }
    public  function saveEvent()
    {
        $date='';
        DB::transaction(function ()
        {
            DB::table('calender')->where('date',$this->oldDate)->delete();
            $date= DB::table('calender')->select('id')->where('date',$this->newDate)->first();
            if($date)
            {
                $date = DB::table('calender')
                    ->where('id', '=',$date->id)
                    ->update([
                        'title' => ($this->title)?  $this->title:'',
                        'updated_at' => $this->updated_at,
                    ]);
            }
            else
            {
                $date = DB::table('calender')
                    ->insertGetId([
                        'date' => $this->newDate,
                        'title' => ($this->title)?  $this->title:'',
                        'created_at' => $this->created_at
                    ]);
            }
        });
        return $date;
    }
    public function saveExcel($file)
    {

        DB::beginTransaction();
        try {
            $f= file($file);
            $row = explode(',', $f[0]);
            if(count($row)!=3 ||$row[0]!="date"|| $row[1]!="title" || $row[2]!="description\r\n")
                return false;
            array_splice($f, 0, 1);
            $date="";
            foreach($f as $line) {
                $l = explode(',', $line);
                $date= DB::table('calender')->select('id')->where('date',$l[0])->first();
                if($date)
                {
                    $date = DB::table('calender')
                        ->where('id', '=',$date->id)
                        ->update([
                            'title' => ($l[1])?  $l[1]:'',
                            'description' => ($l[2])?  $l[2]:'',
                            'updated_at' => $this->updated_at,
                        ]);
                }
                else
                {
                    $date = DB::table('calender')
                        ->insertGetId([
                            'date' => $l[0],
                            'title' => ($l[1])?  $l[1]:'',
                            'description' => ($l[2])?  $l[2]:'',
                            'created_at' => $this->created_at
                        ]);
                }
            }
            if($date>0)
            {
                DB::commit();
                return true;
            }
            return false;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function updateEvent()
    {
        return DB::table('calender')
            ->where('date', '=',$this->date)
            ->update([
                'title' => $this->title,
                'description' => $this->description,
                'updated_at' => $this->updated_at,
            ]);

    }
    public function addEvent()
    {
        $date= DB::table('calender')->select('id')->where('date',$this->date)->first();
        if($date)
        {
            $date = DB::table('calender')
                ->where('id', '=',$date->id)
                ->update([
                    'title' => ($this->title)?  $this->title:'',
                    'description' => ($this->description)?  $this->description:'',
                    'updated_at' => $this->updated_at,
                ]);
        }
        else
        {
            $date = DB::table('calender')
                ->insertGetId([
                    'date' => $this->date,
                    'title' => ($this->title)?  $this->title:'',
                    'description' => ($this->description)?  $this->description:'',
                    'created_at' => $this->created_at
                ]);
        }
        return $date;
    }
}
