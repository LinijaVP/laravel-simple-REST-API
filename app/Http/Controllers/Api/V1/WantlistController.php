<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Wantlist;
use App\Models\Customer;
use App\Http\Requests\V1\StoreWantlistRequest;
use App\Http\Requests\V1\UpdateWantlistRequest;
use App\Http\Requests\V1\StoreMultipleWantlistRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\WantlistResource;
use App\Http\Resources\V1\WantlistCollection;
use App\Filters\V1\WantlistQueryFilter;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class WantlistController extends Controller implements HasMiddleware
{
    // Middleware declare for auth:sanctum
    public static function middleware(){
        return [
            new Middleware("auth:sanctum", except: ["index","show"]),
        ];
    }
    /**
    * Display Wantlists 
    * @OA\Get(
    *      path="/wantlist",
    *      operationId="getWantlist",
    *      tags={"Wantlists"},
    *      summary="Get list of wantlists",
    *      description="Returns list of wantlists",
    *      @OA\Parameter(
    *           name="customerId[eq]",
    *           in="query",
    *           description="Optional parameter to search by customer Id",
    *           required=false,
    *           @OA\Schema(
    *               type="integer",
    *               )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/WantlistResource")
    *       ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *   )
    * 
    * @OA\Get(
    *      path="/wantlist/{id}",
    *      operationId="getWantlistById",
    *      tags={"Wantlists"},
    *      summary="Get the wantlist with given id",
    *      description="Returns the wantlist with the given id",
    *      @OA\Parameter(
    *          name="id",
    *          description="Wantlist id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/WantlistResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    * )
    */
    public function index(Request $request)
    {
        $filter = new WantlistQueryFilter();
        $queryItems = $filter->transform($request); // In the form of [["Column","operator","value"]]

        if (count($queryItems) == 0) {
            return new WantlistCollection(Wantlist::paginate(50)); //or paginate
        } else {
            $wantlists = Wantlist::where($queryItems)->paginate(50)->append($request->query());
            return new WantlistCollection($wantlists);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
    * Store a newly created wantlist in storage.
    * @OA\Post(
    *      path="/wantlist",
    *      operationId="postWantlist",
    *      tags={"Wantlists"},
    *      summary="Creates a wantlist",
    *      description="Create a new wantlist",
    *      security={{"sanctum": {}},},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/StoreWantlistRequest")
    *      ),
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/WantlistResource")
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    * 
    */
    public function store(StoreWantlistRequest $request)
    {
        // Remove unwanted key value pairs
        $requestArray = Arr::except($request->all(),["customerId", "boughtDate"]);
        $customer = Customer::find($requestArray["customer_id"]);

        Gate::authorize("store",[Wantlist::class, $customer]);

        $wantlist = $customer->wantlist()->create($requestArray);
        return new WantlistResource($wantlist);
    }

    /**
     * Store multiple Wantlists at once and verifying them. 
     * A try catch is used so that the loop doesn't stop when it encounters an unauthorized request.
     */
    public function storeMultiple(StoreMultipleWantlistRequest $request)
    {
        $bulk = collect($request->all())->map(function($arr, $key) {
            return Arr::except($arr, ["customerId", "boughtDate"]);
        });

        foreach ($bulk->toArray() as $req){
            try{
                $customer = Customer::find($req["customer_id"]);

                Gate::authorize("store", [Wantlist::class, $customer]);

                $customer->wantlist()->create($req);
            } catch (AuthorizationException $e) {
                continue;
            }
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Wantlist $wantlist)
    {
        return new WantlistResource($wantlist);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wantlist $wantlist)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    * Store a newly created customer in storage.
    * @OA\Put(
    *      path="/wantlist/{id}",
    *      operationId="editWantlist",
    *      tags={"Wantlists"},
    *      summary="Edit a wantlist",
    *      description="Edit a wantlist",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Wantlist id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/UpdateWantlistRequest")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/WantlistResource")
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Resource Not Found"
    *      )
    * )
    * 
    * @OA\Patch(
    *      path="/wantlist/{id}",
    *      operationId="patchWantlist",
    *      tags={"Wantlists"},
    *      summary="Patch a wantlist",
    *      description="Patch a wantlist",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Wantlist id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/PatchWantlistRequest")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/WantlistResource")
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Resource Not Found"
    *      )
    * )
    */
    public function update(UpdateWantlistRequest $request, Wantlist $wantlist)
    {
        Gate::authorize("modify", $wantlist);
        $wantlist->update($request->all());
    }

    /**
    * Remove the specified wantlist from storage.
    * @OA\Delete(
    *      path="/wantlist/{id}",
    *      operationId="deleteWantlist",
    *      tags={"Wantlists"},
    *      summary="Delete a wantlist",
    *      description="Delete a wantlist",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Wantlist id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent()
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      ),
    *      @OA\Response(
    *          response=404,
    *          description="Resource Not Found"
    *      )
    * )
    */
    public function destroy(Wantlist $wantlist)
    {
        Gate::authorize("modify", $wantlist);

        $wantlist->delete();
        return ["message"=> __("Wantlist was deleted")];
    }
}
