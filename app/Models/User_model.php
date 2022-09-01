<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_model extends Model
{ 
    use HasFactory;

    public $timestamps = false;  // will not take default column created_at and updated_at
    protected $table = 'users';

    const USER = 'creation_date';

    protected $primaryKey = 'user_id';   //make default primary key

    protected $guarded = []; //allow  fill all data in table  

    public function check_user_exists($request)
    {
        $user=User_model::select('users.*','auth_user.auth_id')
                        ->leftjoin('auth_user', 'users.user_id', '=', 'auth_user.user_id')
                        ->where('users.email', $request['username'])
                        ->where('auth_user.user_role',$request['user_role'])->first();

                   
                    if(!empty($user))
                    {
                        $data=array('status'=>true,'msg'=>'User already exists');
                    }else{
                        $data=array('status'=>false,'msg'=>'User not exists');
                    }

                    return $data;
    }
}
