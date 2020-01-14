<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CommandRequest;
use App\Order;
use App\Services\Commands\CommandService;
use App\Http\Controllers\Controller;
use App\Services\Production\ProductionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
   private $service;
   /**
    * @var ProductionService
    */
   private $production;

   public function __construct(CommandService $commandService, ProductionService $production)
   {
      $this->service = $commandService;
      $this->production = $production;
   }

   public function store(Request $request) // CommandRequest
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

   public function toPrint($id)
   {
      return $this->service->getCommandByIdToPrint($id);
   }

   public function getLastOrderNumber()
   {
      $last = DB::table('orders')->latest()->first();
      if (is_null($last))
         return response()->json(['cmd_number' => 0], Response::HTTP_OK);

      return response()->json($last, Response::HTTP_OK);
   }

   public function canceledOrder($id, Request $req)
   {
      $order = Order::find($id);
      $order->update([
         'canceled_date' => Carbon::now()->toDateString(),
         'is_canceled' => 1,
         'canceled_description' => $req->get('description'),
      ]);


      return \response()->json("Update Successfully !");
   }

   public function validateCanceledOrder($id)
   {
      $order = Order::find($id);
      $order->update(['validate_canceled' => 1]);


      return \response()->json("Validate Successfully !");
   }

   public function canceled()
   {
      return $this->service->getCommands(1);
   }

   public function addProductionCommand()
   {
      return $this->production->addExternalCommand();
   }

   public function getAllProductionCommand()
   {
      return Order::with('productSize')->get();
   }
}
