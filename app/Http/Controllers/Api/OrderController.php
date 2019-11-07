<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Orders\CommandRequest;
use App\Order;
use App\Services\Commands\CommandService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    private $service;

    public function __construct(CommandService $commandService)
    {
        $this->service = $commandService;
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
}
