<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";
    protected $primaryKey = "id";
    protected $keyType = "string";
    protected $fillable = [
        "id",
        "name",
        "province",
        "provinceId",
        "regency",
        "regencyId",
        "district",
        "districtId",
    ];

    public $timestamps = true;
}
