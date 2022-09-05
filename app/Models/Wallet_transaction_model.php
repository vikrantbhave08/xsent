<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet_transaction_model extends Model
{
    use HasFactory;
    
    public $timestamps = false;  // will not take default column created_at and updated_at

    protected $table = 'wallet_transaction';

    protected $primaryKey = 'wallet_trans_id';   //make default primary key

    protected $guarded = []; //allow  fill all data in table  
}
