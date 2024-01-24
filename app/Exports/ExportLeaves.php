<?php
  namespace App\Exports;

  use DB;
  use Maatwebsite\Excel\Concerns\FromCollection;
  use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportLeaves implements FromCollection, WithHeadings {

   public function headings(): array {

    return [
        "Leave Types",
        "Users",
        "Allocated Leaves",
        "Leaves Taken",
        // "allocated sick leave",
        // "sick leave taken",
        // "allocated causal Leave",
        // "causal Leave taken",
        // "allocated annual Leave",
        // "annual Leave taken",
        // "allocated maternity Leave",
        // "maternity Leave taken",
        // "allocated paternity Leave",
        // "paternity Leave taken",
        // "allocated unpaid Leave",
        // "unpaid Leave taken",
        // "allocated total Leave",
        // "total Leave taken",
       ];
    }

   public function collection() {
    $leavesTaken = DB::table('users as u')
    ->rightJoin('leaves as l', function ($join) {
        $join->on('u.id', '=', 'l.user_id');
    })
    ->rightJoin('leave_types as lt', function ($join) {
        $join->on('l.leave_type_id', '=', 'lt.id');
    })
    ->leftJoin('total_yearly_leaves as tyl', function ($join) {
        $join->on('lt.id', '=', 'tyl.leave_type_id')
        ->where('tyl.year', '=', date("Y"));
    })
    ->whereNULL('u.deleted_at')
    ->whereNULL('l.deleted_at')
    ->select('lt.name as leave type', 'u.full_name as user_name', 'tyl.total_leaves as allocated leaves',  DB::raw('SUM(l.total) AS leaves_taken'))
    ->groupBy('l.leave_type_id', 'u.id', 'tyl.id')
    ->orderBy('lt.id')
    ->get();

    return collect($leavesTaken);
   }
}
