<?php
namespace App\Filters\V1;

use App\Filters\ApiQueryFilter;
use Illuminate\Http\Request;

class CustomerQueryFilter extends ApiQueryFilter{
    protected $safeParameters = [
        "id"=> ["eq"],
        "name"=> ["eq"],
        "type"=> ["eq"],
        "email"=> ["eq"],
        "city" => ["eq"],
        "country" => ["eq"],
        "budget" => ["eq", "gt", "lt"],
    ];    
}