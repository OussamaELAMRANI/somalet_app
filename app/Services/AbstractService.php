<?php


namespace App\Services;


use Illuminate\Http\Request;

abstract class AbstractService
{
   protected $req;

   /**
    * Inject Request Object
    * ProductService constructor.
    * @param Request $request
    */
   public function __construct(Request $request)
   {
      $this->req = $request;
   }

   protected function sendResponse($message, $status = 200)
   {
      return response()->json($message, $status);
   }


}
