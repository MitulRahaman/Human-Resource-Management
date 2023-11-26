<?php

namespace App\Traits;

trait AuthorizationTrait {

  private $authorizationService;

  public function __construct(AuthorizationService $authorizationService)
  {
    $this->authorizationService = $authorizationService;
  }

  public function checkAuthorization($request) {
    
    dd($request);

  }


}
?>
  
