<?php

namespace App\Exports;

use DB;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;



class ExportPermissions implements FromCollection, WithHeadings {




    public function headings(): array {




        // according to users table




        return [

            "ID",
            "Slug",

            "Name",
            "Description",
            "Status",
            "Created-at",
            "Updated-at",
            "Deleted-at"

        ];

    }




    public function collection(){

        $permission = DB::table('permissions')->get();


        return collect($permission);

    }

}