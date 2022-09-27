<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_history_model extends Model
{
    use HasFactory;

    public $timestamps = false;  // will not take default column created_at and updated_at
    
    protected $table = 'payment_history';
    
    protected $primaryKey = 'payment_id';   //make default primary key
    
    protected $guarded = []; //allow  fill all data in table
}
