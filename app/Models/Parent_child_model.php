<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parent_child_model extends Model
{
    use HasFactory;

    public $timestamps = false;  // will not take default column created_at and updated_at
    protected $table = 'parent_child';

    protected $primaryKey = 'assign_id';   //make default primary key

    protected $guarded = []; //allow  fill all data in table  
}
