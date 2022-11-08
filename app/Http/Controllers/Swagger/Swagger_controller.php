<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\User;
use Symfony\Component\HttpFoundation\Response;


use Illuminate\Support\Facades\Auth;

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

 
       
    /**
     * 
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
    *      url=L5_SWAGGER_CONST_HOST,
    *      description="Demo API Server"
    * )
    * 
    * @OA\SecurityScheme(
    * securityScheme="bearerAuth",
    *  type="http",
    *  scheme="bearer"
    * )
     *
     * 
    

       * @OA\Post(
    * path="/login",
    * summary="Sign in",
    * description="Login by email, password",
    * operationId="authLogin",
    * tags={"Login"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
    *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
    *       @OA\Property(property="fcm_token", type="string", example=""),
    *       @OA\Property(property="app_type", type="string", example="shop or parent"),
    *    ),
    * ),
  *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
    * )

    
     *  * @OA\Post(
    * path="/logout",
    * summary="/logout",
    * description="api/logout",
    * operationId="logout",
    * tags={"Logout"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="User Logout",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="logout", type="string", example=""),
    *    ),
    * ),
  *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
    * )


    
     *
     *  * @OA\Post(
    * path="/forgot-password",
    * summary="api/forgot-password",
    * description="api/forgot-password",
    * operationId="forgot",
    * tags={"Forgot Password"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
    *       @OA\Property(property="user_role", type="string", example=""),
    *    ),
    * ),
  *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
    * )
    


     * 
     * 
     * 
     */

    class Swagger_controller extends Controller
    {
      public function __construct(Request $request)
      {
          
      }
    
    }

