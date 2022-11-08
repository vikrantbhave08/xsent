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
     *  * @OA\Post(
    * path="/reset-password",
    * summary="api/reset-password",
    * description="api/reset-password",
    * operationId="reset_password",
    * tags={"Reset Password"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Reset Users Password",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="current_password", type="string", example=""),
    *       @OA\Property(property="new_password", type="string", example=""),
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
    * path="/verify-mobile",
    * summary="api/verify-mobile",
    * description="api/verify-mobile",
    * operationId="verify_mobile",
    * tags={"Mobile Verification"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="contact_no", type="number", example="9876543210"),
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
    * path="/mobile-verified",
    * summary="api/mobile-verified",
    * description="api/mobile-verified",
    * operationId=" Mobile_Verified",
    * tags={"Mobile Verification"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Pass user credentials",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="number", example=""),
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
    * path="/register-parent",
    * summary="api/register",
    * description="api/register",
    * operationId="Register Parent",
    * tags={"Registration"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="name", type="string",  example="Test User"),
    *       @OA\Property(property="contact_no", type="number",  example="9876543210"),
    *       @OA\Property(property="email", type="string",  example="user1@mail.com"),
    *       @OA\Property(property="country", type="string",  example="UAE"),
    *       @OA\Property(property="province", type="string",  example="Abu Dhabi"),
    *       @OA\Property(property="city", type="string",  example="city name"),
    *       @OA\Property(property="password", type="string",  example="PassWord12345"),
    *       @OA\Property(property="fcm_token", type="string", example=""),
    *       @OA\Property(property="user_role", type="number", example="2 or 3"),
    *       @OA\Property(property="birth_date", type="date", example="2022-01-31"),
    *       @OA\Property(property="gender", type="string", example="Male/Female"),
    *       @OA\Property(property="university", type="string", example=""),
    *       @OA\Property(property="token", type="string", example=""),
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
     *  @OA\Post(
    * path="/register-shop",
    * summary="api/register ",
    * description="api/register",
    * operationId="Register Shop ",
    * tags={"Registration"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="name", type="string",  example="Test User"),
    *       @OA\Property(property="shop_name", type="string",  example="shop_name"),
    *       @OA\Property(property="shop_city", type="string",  example="shop_city"),
    *       @OA\Property(property="shop_country", type="string",  example="shop_country"),
    *       @OA\Property(property="contact_no", type="number",  example="9876543210"),
    *       @OA\Property(property="email", type="string",  example="user1@mail.com"),
    *       @OA\Property(property="country", type="string",  example="UAE"),
    *       @OA\Property(property="province", type="string",  example="Abu Dhabi"),
    *       @OA\Property(property="city", type="string",  example="city name"),
    *       @OA\Property(property="password", type="string",  example="PassWord12345"),
    *       @OA\Property(property="fcm_token", type="string", example=""),
    *       @OA\Property(property="user_role", type="number", example="2 or 3"),
    *       @OA\Property(property="birth_date", type="date", example="2022-01-31"),
    *       @OA\Property(property="gender", type="string", example="Male/Female"),
    *       @OA\Property(property="university", type="string", example=""),
    *       @OA\Property(property="token", type="string", example=""),
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
    * path="/add-student",
    * summary="api/add-user",
    * description="api/add-user",
    * operationId="add_user",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="name", type="string",  example="Test User"),
    *       @OA\Property(property="contact_no", type="number",  example="9876543210"),
    *       @OA\Property(property="email", type="string",  example="user1@mail.com"),
    *       @OA\Property(property="country", type="string",  example="UAE"),
    *       @OA\Property(property="province", type="string",  example="Abu Dhabi"),
    *       @OA\Property(property="city", type="string",  example="city name"),
    *       @OA\Property(property="password", type="string",  example="PassWord12345"),
    *       @OA\Property(property="fcm_token", type="string", example=""),
    *       @OA\Property(property="child_role", type="number", example="4"),
    *       @OA\Property(property="birth_date", type="date", example="2022-01-31"),
    *       @OA\Property(property="gender", type="string", example="Male/Female"),
    *       @OA\Property(property="university", type="string", example=""),
    *       @OA\Property(property="token", type="string", example=""),
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


    
    *   @OA\Post(
    * path="/add-salesperson",
    * summary="api/add-user",
    * description="api/add-user",
    * operationId="add_salesperson",
    * tags={"Add User"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},
    *       @OA\Property(property="name", type="string",  example="Test User"),
    *       @OA\Property(property="contact_no", type="number",  example="9876543210"),
    *       @OA\Property(property="email", type="string",  example="user1@mail.com"),
    *       @OA\Property(property="country", type="string",  example="UAE"),
    *       @OA\Property(property="province", type="string",  example="Abu Dhabi"),
    *       @OA\Property(property="city", type="string",  example="city name"),
    *       @OA\Property(property="password", type="string",  example="PassWord12345"),
    *       @OA\Property(property="fcm_token", type="string", example=""),
    *       @OA\Property(property="child_role", type="number", example="5"),
    *       @OA\Property(property="birth_date", type="date", example="2022-01-31"),
    *       @OA\Property(property="gender", type="string", example="Male/Female"),
    *       @OA\Property(property="university", type="string", example=""),
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_id", type="number", example=""),
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

