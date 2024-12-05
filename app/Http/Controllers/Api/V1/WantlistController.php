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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     */
    public function update(UpdateWantlistRequest $request, Wantlist $wantlist)
    {
        Gate::authorize("modify", $wantlist);
        $wantlist->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wantlist $wantlist)
    {
        Gate::authorize("modify", $wantlist);

        $wantlist->delete();
        return ["message"=> __("Wantlist was deleted")];
    }
}
