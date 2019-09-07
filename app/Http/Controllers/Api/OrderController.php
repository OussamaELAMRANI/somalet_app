<?php

namespace App\Http\Controllers\Api;

use App\Services\Commands\CommandService;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
   private $service;

   public function __construct(CommandService $commandService)
   {
      $this->service = $commandService;
   }

   public function store()
   {
      return $this->service->addCommand();
   }

   public function index()
   {
      return $this->service->getCommands();
   }

   public function show($id)
   {
      return $this->service->getCommandById($id);
   }

}
