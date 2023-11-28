<?php

namespace App\Services;

use App\Repositories\AuthorizationRepository;

class AuthorizationService
{

    private $authorizationRepository;

    public function __construct(AuthorizationRepository $authorizationRepository)
    {
        $this->authorizationRepository = $authorizationRepository;
    }

    public function manageLeaveAuthorization($id)
    {
        //$this->authorizationRepository->setId($this->id)->manageLeaveAuthorization();
    }
    
 }

