<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop_cat_model extends Model
{
    use HasFactory;

    public $timestamps = false;  // will not take default column created_at and updated_at
    protected $table = 'shop_categories';

    protected $primaryKey = 'shop_cat_id';   //make default primary key

    protected $guarded = []; //allow  fill all data in table  
}
