<?php
namespace App\Filters;

use Illuminate\Http\Request;

class ApiQueryFilter{
    protected $safeParameters = [];

    protected $columnMap = []; 

    protected $operatorMap = [
        "eq" => "=",
        "lt" => "<",
        "lte" => "<=",
        "gt" => ">",
        "gte" => ">=",
        "neq" => "!=",
        "like" => "LIKE",
        "notlike" => "NOT LIKE",
    ]; 

    public function transform(Request $request){
        $eloquentQuery = [];

        foreach ($this->safeParameters as $parameter => $operators){
            $query = $request->query($parameter);

            if(!isset($query)){
                continue;
            }

            // maps query column to real column name in mysql table
            $column = $this->columnMap[$parameter] ?? $parameter;

            foreach ($operators as $operator){
                if(isset($query[$operator])){
                    // This is now in the form of [Column name, operator type ( =, >, <), and our query integer/string ]
                    $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloquentQuery;
    }
}