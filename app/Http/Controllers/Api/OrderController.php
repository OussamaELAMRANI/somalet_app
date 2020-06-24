<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\Services\Commands\CommandService;
use App\Http\Controllers\Controller;
use App\Services\Production\ProductionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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

   public function update(Order $order)
   {
      $upProducts = request()->get('products');
      $date_cmd = request()->get('date_cmd');
      $order->update(['date_cmd' => Carbon::parse($date_cmd)->toDate()]);

      $products = collect($upProducts);

      if (!$products->isEmpty()) {
         $order->product()->detach();
         foreach ($products as $product) {
            $order->product()->attach($product['product_id'], [
               'qte' => $product['qte'],
               'qte_rapport' => $product['qte_rapport'],
               'discount' => $product['discount'],
               'price' => $product['price'],
            ]);
         }
      }
      $order->update();

      return response()->json($order);
   }

   public function index()
   {
      return $this->service->getCommands();
   }

   /**
    * Use Object to render Order data (by ID)
    *
    * @param Order $order
    * @return Order
    */
   public function show(Order $order)
   {
      return $this->service->getCommandById($order);
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

   /**
    * Cancel one Order
    *
    * @method POST
    *
    * @param Order $order
    * @param Request $req
    * @return JsonResponse
    */
   public function canceledOrder(Order $order, Request $req)
   {
      $order->update([
         'canceled_date' => Carbon::now()->toDateString(),
         'is_canceled' => 1,
         'canceled_description' => $req->get('description'),
      ]);


      return \response()->json("Update Successfully !");
   }

   /**
    * Validate Canceled Order
    * That make it return to the real stock
    * Then delete it on the client Movements
    *
    * @method POST
    * @param Order $order
    * @return JsonResponse
    */
   public function validateCanceledOrder(Order $order)
   {
      $order->update(['validate_canceled' => 1]);

      return \response()->json("Validate Successfully !");
   }

   /**
    * Get Canceled Orders (Valid/noValid)
    * by other criteria of search (by Date) ...
    *
    * @method GET
    * @return JsonResponse
    */
   public function canceled()
   {
      $canceledOrders = Order::where('is_canceled', 1)
         ->where('validate_canceled',
            (request()->has('valid') ? request()->get('valid') : 0));

      if (request()->has('from') && request()->has('to')) {
         $fromDate = request()->get('from');
         $toDate = request()->get('to');
         $canceledOrders->whereBetween('date_cmd', [$fromDate, $toDate]);
      }

      return response()->json($canceledOrders->filter(request())->last());
   }

   public function addProductionCommand()
   {
      return $this->production->addExternalCommand();
   }

   public function getAllProductionCommand()
   {
      return Order::with('productSize')->get();
   }

   /**
    * @param Order $order
    * @return JsonResponse
    */
   public function destroy(Order $order)
   {
      $order->product()->detach();
      try {
         $order->delete();
      } catch (\Exception $e) {
         \response()->json(['message' => $e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
      }

      return \response()->json(['message' => 'Delete it on success']);
   }
}
