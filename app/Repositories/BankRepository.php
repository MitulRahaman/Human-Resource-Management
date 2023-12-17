<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class BankRepository
{
    private  $name, $id, $address,  $created_at, $updated_at, $deleted_at;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
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
    public function getAllBankData()
    {
        return DB::table('banks as b')
            ->select('b.id', 'b.name', 'b.address',  DB::raw('date_format(b.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(b.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('b.id', 'desc')
            ->get();
    }
    public function isNameExists()
    {
        return Bank::withTrashed()->where('name', $this->name)->exists() ;
    }
    public function save()
    {
        return DB::table('banks')
            ->insertGetId([
                'name' => $this->name,
                'address' => $this->address,
                'created_at' => $this->created_at
            ]);
    }
    public function getBank($id)
    {
        $bank = Bank::onlyTrashed()->find($id);
        if($bank)
            return "Restore first";
        return Bank::findOrFail($id);
    }
    public function isNameUnique($id)
    {
        return Bank::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() ;
    }
    public function update()
    {
        return DB::table('banks')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'address' => $this->address ? $this->address : null,
                'updated_at' => $this->updated_at
            ]);
    }
    public function delete($id)
    {
        $bank= Bank::findOrFail($id);
        return $bank->delete();
    }
    public function restore($id)
    {
        return Bank::withTrashed()->where('id', $id)->restore();
    }

}
