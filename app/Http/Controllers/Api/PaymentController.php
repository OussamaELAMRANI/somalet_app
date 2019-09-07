<?php

namespace App\Http\Controllers\Api;

use App\Services\Payments\PayementService;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
   private $service;

   public function __construct(PayementService $payementService)
   {
       $this->service = $payementService;
   }

   public function store($cmd)
   {
      return $this->service->addPayment($cmd);
   }

   public function checkout()
   {
      return $this->service->getCheckout();
   }

   public function cheques()
   {
      return $this->service->getCheques();
   }

}
