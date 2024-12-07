<?php
namespace App\Filters\V1;

use App\Filters\ApiQueryFilter;
use Illuminate\Http\Request;

class WantlistQueryFilter extends ApiQueryFilter{
    protected $safeParameters = [
        "customerId"=> ["eq"],
        "price"=> ["eq", "lt", "gt"],
        "item"=> ["eq"],
        "status"=> ["eq"],
        "boughtDate" => ["eq"],
    ];

    protected $columnMap = [
        "boughtDate" => "bought_date",
        "customerId" => "customer_id"
    ]; 
}