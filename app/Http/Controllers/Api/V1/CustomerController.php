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


class CustomerController extends Controller implements HasMiddleware
{
    // Middleware declare for auth:sanctum
    public static function middleware(){
        return [
            new Middleware("auth:sanctum", except: ["index","show"]),
        ];
    }
    /**
     * Display a listing of the resource.
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
        return new CustomerCollection($customers->paginate(50)->appends($request->query()));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        Gate::authorize("modify", $customer);
        $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        Gate::authorize("modify", $customer);

        $customer->delete();
        return ["message"=> __("Customer was deleted")];
    }
}
