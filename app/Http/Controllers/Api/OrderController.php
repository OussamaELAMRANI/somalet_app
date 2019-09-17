<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CommandRequest;
use App\Services\Commands\CommandService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
   private $service;

   public function __construct(CommandService $commandService)
   {
      $this->service = $commandService;
   }

   public function store(CommandRequest $request)
   {
      return $this->service->addCommand($request);
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
