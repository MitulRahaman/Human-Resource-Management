<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class BranchRepository
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function indexBranch()
    {
        return DB::table('branches')->get();
    }

    public function storeBranch($data)
    {
       return Branch::create([
            'name' => $data->name,
            'address' => $data->address,
            'status' => 1,
       ]);
    }

    public function editBranch($id)
    {
        return Branch::find($id);
    }

    public function updateBranch($data, $id)
    {
        $branch = Branch::find($id);
        return $branch->update($data->validated());
    }

    public function updateStatus($id)
    {
        $data = Branch::find($id);
        if($data->status)
            $data->update(array('status' => Config::get('variable_constants.activation.inactive')));
        else
            $data->update(array('status' => Config::get('variable_constants.activation.active')));
    }

    public function destroyBranch($id)
    {
        $data = Branch::find($id);
        $data->update(array('status' => 0));
        return $data->delete();
    }

    public function restoreBranch($id)
    {
        return DB::table('branches')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function isNameExists()
    {
        return DB::table('branches')->where('name', '=', $this->name)->first();
    }

    public function isNameExistsForUpdate($current_name)
    {
        return DB::table('branches')->where('name', '!=', $current_name)->where('name', $this->name)->first();
    }

    public function getBranches()
    {
        return Branch::where('status', Config::get('variable_constants.activation.active'))->get();
    }
}
