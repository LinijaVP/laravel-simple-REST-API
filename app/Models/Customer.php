<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "email",
        "city",
        "country",
        "budget",
    ];   
    public function wantlist() {
        return $this->hasMany(Wantlist::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
