<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wantlist extends Model
{
    /** @use HasFactory<\Database\Factories\WantlistFactory> */
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "price",
        "item",
        "status",
        "bought_date"
    ];   

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
