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
    
 }

