<?php

namespace App\Http\Controllers\Mobile_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Validator;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Mobile_api\Login_controller;

use App\Models\User_model;
use App\Models\Auth_users;
use App\Models\Parent_child_model; 
use App\Models\Wallet_model;
use App\Models\Wallet_transaction_model;
use App\Models\Shop_transaction_model;
use App\Models\Shops_model;
use App\Models\Shopkeepers_model;
use App\Models\Amount_requests_model;
use App\Models\Notifications_model;
use App\Models\Cities_model;
use App\Models\Province_model;
use App\Models\Bank_details_model;
use App\Models\Payment_history_model;
use App\Models\Shop_cat_model;


class Swagger_controller extends Controller
{
    public function __construct()
    {       
       
    }


    

    

       
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Integration Swagger in Laravel with Passport Auth Documentation",
     *      description="Implementation of Swagger with in Laravel",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url="",
     *      description="Demo API Server"
     * )
     * 
    * @OA\SecurityScheme(
    * securityScheme="bearerAuth",
    *  type="http",
    *  scheme="bearer"
    * )


    
    


     * @OA\Get(
     * tags={"Getall-Province"},
     *security={
     *        {
     *         "passport": {}},
     *},
     *     path="/xsent/api/getall-province",
     *     @OA\Response(response="200", description="An example resource")
     * )
   


    * @OA\Post(
    * path="/xsent/api/login",
    * summary="Sign in",
    * description="Login by email, password",
    * operationId="authLogin",
    * tags={"Authentication"},
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
    *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
    *       @OA\Property(property="app_type", type="boolean", example="true")
    *    ),
    * ),
    * @OA\Response(
    *    response=422,
    *    description="Wrong credentials response",
    *    @OA\JsonContent(
    *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
    *        )
    *     )
    * )
    *   */

    public function index(Request $request)
    {  
       
    }     

   
}
