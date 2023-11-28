<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AuthorizationRepository
{
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function manageLeaveAuthorization()
    {
        
    } 

}
