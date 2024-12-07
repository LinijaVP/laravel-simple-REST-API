<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Filters\V1\CustomerQueryFilter;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

use OpenApi\Attributes as OA;



class CustomerController extends Controller implements HasMiddleware
{
    // Middleware declare for auth:sanctum
    public static function middleware(){
        return [
            new Middleware("auth:sanctum", except: ["index","show"]),
        ];
    }
    
    /**
    * @OA\Get(
    *      path="/customer",
    *      operationId="getCustomers",
    *      tags={"Customers"},
    *      summary="Get list of customers",
    *      description="Returns list of customers",
    *      @OA\Parameter(
    *      name="name[eq]",
    *      in="query",
    *      description="Optional parameter to search by name",
    *      required=false,
    *      @OA\Schema(
    *          type="string",
    *        )
    *      ),
    *      @OA\Parameter(
    *      name="country[eq]",
    *      in="query",
    *      description="Optional parameter to search by country",
    *      required=false,
    *      @OA\Schema(
    *          type="string",
    *        )
    *      ),
    *      @OA\Parameter(
    *      name="type[eq]",
    *      in="query",
    *      description="Optional parameter to search by country",
    *      required=false,
    *      @OA\Schema(
    *          type="string", 
    *          enum={"S", "G"},
    *        )
    *      ),
    *      @OA\Parameter(
    *      name="showWantlists",
    *      in="query",
    *      description="Optional parameter to hide the customers wantlists",
    *      required=false,
    *      @OA\Schema(
    *          type="boolean",
    *          default=true
    *        )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
    *       ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    * 
    * @OA\Get(
    *      path="/customer/{id}",
    *      operationId="getCustomerById",
    *      tags={"Customers"},
    *      summary="Get the customer with given id",
    *      description="Returns customer with given id",
    *      @OA\Parameter(
    *          name="id",
    *          description="Customer id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\Parameter(
    *         name="showWantlists",
    *         in="query",
    *         description="Optional parameter to hide the customers wantlists",
    *         required=false,
    *         @OA\Schema(
    *             type="boolean",
    *             default=true
    *         )
    *     ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
        $filter = new CustomerQueryFilter();
        $queryItems = $filter->transform($request); // In the form of [["Column","operator","value"]]

        $showWantlists = $request->query("showWantlists");

        $customers = Customer::where($queryItems);

        // Add wantlists to the response if it has been queried
        if ($showWantlists) {
            $customers = $customers->with("wantlist");
        }
        return new CustomerCollection($customers->paginate(50)); //->appends($request->query()));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
    * Store a newly created customer in storage.
    * @OA\Post(
    *      path="/customer",
    *      operationId="postCustomers",
    *      tags={"Customers"},
    *      summary="Creates a customer",
    *      description="Create a new customer",
    *      security={{"sanctum": {}},},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/StoreCustomerRequest")
    *      ),
    *      @OA\Response(
    *          response=201,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
    public function store(StoreCustomerRequest $request)
    {
        $customer = $request->user()->customer()->create($request->all());

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        if (request()->query("showWantlists")) {
            return new CustomerResource($customer->loadMissing("wantlist"));
        }
        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }


    /**
    * Update the specified resource in storage.
    * Store a newly created customer in storage.
    * @OA\Put(
    *      path="/customer/{id}",
    *      operationId="editCustomer",
    *      tags={"Customers"},
    *      summary="Edit a customer",
    *      description="Edit a customer",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Customer id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/StoreCustomerRequest")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
    *      path="/customer/{id}",
    *      operationId="patchCustomer",
    *      tags={"Customers"},
    *      summary="Patch a customer",
    *      description="Patch a customer",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Customer id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(ref="#/components/schemas/PatchCustomerRequest")
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        Gate::authorize("modify", $customer);
        $customer->update($request->all());
    }

    /**
    * Remove the specified customer from storage.
    * @OA\Delete(
    *      path="/customer/{id}",
    *      operationId="deleteCustomer",
    *      tags={"Customers"},
    *      summary="Delete a customer",
    *      description="Delete a customer",
    *      security={{"sanctum": {}},},
    *      @OA\Parameter(
    *          name="id",
    *          description="Customer id",
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
    public function destroy(Customer $customer)
    {
        Gate::authorize("modify", $customer);

        $customer->delete();
        return ["message"=> __("Customer was deleted")];
    }
}
