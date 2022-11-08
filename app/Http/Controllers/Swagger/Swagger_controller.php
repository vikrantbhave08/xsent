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



    
     *  * @OA\Post(
    * path="/update-user",
    * summary="api/update-user",
    * description="api/update-user",
    * operationId="update_user",
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
    *       @OA\Property(property="user_id", type="string", example=""),
    *       @OA\Property(property="is_active ", type="string", example="1"),
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
    * path="/delete-user",
    * summary="api/delete-user",
    * description="api/delete-user",
    * operationId="delete_user",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="string", example=""),
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
    * path="/get-own-profile",
    * summary="api/get-users-profile",
    * description="api/get-users-profile",
    * operationId="get_owns_profile",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/get-users-profile",
    * summary="api/get-users-profile",
    * description="api/get-users-profile",
    * operationId="get_users_profile",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="number", example="6"),
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
    * path="/getall-childrens",
    * summary="api/get-children",
    * description="api/get-children",
    * operationId="get_childrens",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/get-children",
    * summary="api/get-children",
    * description="api/get-children",
    * operationId="get_children",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="number", example="6"),
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
    * path="/get-child-details",
    * summary="api/get-child-details",
    * description="api/get-child-details",
    * operationId="get_children_details",
    * tags={"Users"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="number", example="6"),
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
    * path="/parent-transfer-child",
    * summary="api/add-money-to-wallet",
    * description="api/add-money-to-wallet",
    * operationId="transfer_money",
    * tags={"Transfer Money To Wallet"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="user_id", type="number", example="6"),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="note", type="string", example=""),
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
    * path="/parent-transfer-shop",
    * summary="api/add-money-to-wallet",
    * description="api/add-money-to-wallet",
    * operationId="transfer_money_shop",
    * tags={"Transfer Money To Wallet"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_gen_id", type="string", example="6"),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="note", type="string", example=""),
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
    * path="/child-transfer-shop",
    * summary="api/add-money-to-wallet",
    * description="api/add-money-to-wallet",
    * operationId="transfer_money_child_to_shop",
    * tags={"Transfer Money To Wallet"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_gen_id", type="string", example="6"),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="note", type="string", example=""),
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
    * path="/add-bank-details",
    * summary="api/add-bank-details",
    * description="api/add-bank-details",
    * operationId="add_banks",
    * tags={"Bank Details"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="account_no", type="string", example="6"),
    *       @OA\Property(property="acc_holder_name", type="string", example=""),
    *       @OA\Property(property="display_name", type="string", example=""),
    *       @OA\Property(property="iban_no", type="string", example=""),
    *       @OA\Property(property="bank_identifier", type="string", example=""),
    *       @OA\Property(property="person_address", type="string", example=""),
    *       @OA\Property(property="city", type="string", example=""),
    *       @OA\Property(property="country", type="string", example=""),
    *       @OA\Property(property="swift_code", type="string", example=""),
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
    * path="/get-bank-details",
    * summary="api/get-bank-details",
    * description="api/get-bank-details",
    * operationId="get_bank",
    * tags={"Bank Details"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/get-notifications",
    * summary="api/get-notifications",
    * description="api/get-notifications",
    * operationId="get_notifications",
    * tags={"Notifications"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/get-users-wallet",
    * summary="api/get-users-wallet",
    * description="api/get-users-wallet",
    * operationId="get_wallet",
    * tags={"Wallet"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
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


     *  * @OA\Post(
    * path="/update-users-wallet",
    * summary="api/update-users-wallet",
    * description="api/update-users-wallet",
    * operationId="update_wallet",
    * tags={"Wallet"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="wallet_id", type="number", example=""),
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
    * path="/add-shop",
    * summary="api/add-shop",
    * description="api/add-shop",
    * operationId="add_shops",
    * tags={"Shops"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_name", type="string", example=""),
    *       @OA\Property(property="shop_city", type="string", example=""),
    *       @OA\Property(property="shop_country", type="string", example=""),
    *       @OA\Property(property="province", type="string", example=""),
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
    * path="/getall-shops-by-owner",
    * summary="api/get-shops-by-owner",
    * description="api/get-shops-by-owner",
    * operationId="getall_shops",
    * tags={"Shops"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/get-shop-by-owner",
    * summary="api/get-shops-by-owner",
    * description="api/get-shops-by-owner",
    * operationId="get_shop",
    * tags={"Shops"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_gen_id", type="string", example=""),
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
    * path="/transaction-summary",
    * summary="api/transaction-summary (Owner,Salesperson)",
    * description="api/transaction-summary (Owner,Salesperson)",
    * operationId="trans_summary",
    * tags={"Shop Transactions"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="shop_gen_id", type="string", example=""),
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
    * path="/children-transaction-summary",
    * summary="api/transaction-summary (Children)",
    * description="api/transaction-summary (Children)",
    * operationId="child_trans_summary",
    * tags={"Shop Transactions"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/parent-transaction-summary",
    * summary="api/transaction-summary (Parent)",
    * description="api/transaction-summary (Parent)",
    * operationId="parent_trans_summary",
    * tags={"Shop Transactions"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/parent-watch-children-transaction-summary",
    * summary="api/transaction-summary (Parent)",
    * description="api/transaction-summary (Parent)",
    * operationId="parent_watch_child_trans_summary",
    * tags={"Shop Transactions"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
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



     *  * @OA\Post(
    * path="/add-request-by-child",
    * summary="api/add-request (Child)",
    * description="api/add-request (Child)",
    * operationId="child_request_add",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="reason", type="string", example=""),
    *       @OA\Property(property="date_of_expenditure", type="string", example="2022-01-31"),
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
    * path="/add-request-by-parent",
    * summary="api/add-request (Parent)",
    * description="api/add-request (Parent)",
    * operationId="paernt_request_add",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="reason", type="string", example=""),
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
    * path="/add-request-by-owner",
    * summary="api/add-request (Owner)",
    * description="api/add-request (Owner)",
    * operationId="Owner_request_add",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *       @OA\Property(property="token", type="string", example=""),
    *       @OA\Property(property="amount", type="string", example=""),
    *       @OA\Property(property="reason", type="string", example=""),
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
    * path="/request-money-history-by-owner",
    * summary="api/request-money-history (Owner)",
    * description="api/request-money-history (Owner)",
    * operationId="Owner_request_history",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/request-money-history-by-parent",
    * summary="api/request-money-history (Parent)",
    * description="api/request-money-history (Parent)",
    * operationId="parent_request_history",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
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
    * path="/request-money-history-by-parent-for-children",
    * summary="api/request-money-history (Parent)",
    * description="api/request-money-history (Parent)",
    * operationId="parent_request_history_for_children",
    * tags={"Money Request"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
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


    
    *   @OA\Post(
    * path="/topup-history-for-parent",
    * summary="api/topup-history (Parent)",
    * description="api/topup-history (Parent)",
    * operationId="topup_history_parent",
    * tags={"Topup History"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},   
    *   @OA\Property(property="token", type="string", example=""), 
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



    
    
    * @OA\Post(
    * path="/topup-history-for-owner",
    * summary="api/topup-history (Owner)",
    * description="api/topup-history (Owner)",
    * operationId="topup_history_owner",
    * tags={"Topup History"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
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


    
    * @OA\Post(
    * path="/get-dashboard-data",
    * summary="api/get-dashboard-data (Child)",
    * description="api/get-dashboard-data (Child)",
    * operationId="dashboard_data",
    * tags={"Dashboard"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="year", type="number", example="2022"),
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

    
    * @OA\Post(
    * path="/get-dashboard-data-for-children",
    * summary="api/get-dashboard-data (Parent)",
    * description="api/get-dashboard-data (Parent)",
    * operationId="dashboard_data_for_children",
    * tags={"Dashboard"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="user_id", type="number", example=""),
    *   @OA\Property(property="year", type="number", example="2022"),
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
    

    * @OA\Post(
    * path="/billing-history",
    * summary="api/billing-history (SELF)",
    * description="api/billing-history (SELF)",
    * operationId="billing_history",
    * tags={"Billing History"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="year", type="number", example="2022"),
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

    

    * @OA\Post(
    * path="/billing-history-by-parent-for-children",
    * summary="api/billing-history (Parent)",
    * description="api/billing-history (Parent)",
    * operationId="billing_history_for_child",
    * tags={"Billing History"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="user_id", type="number", example=""),
    *   @OA\Property(property="year", type="number", example="2022"),
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

    

    * @OA\Post(
    * path="/payment-details",
    * summary="api/payment-details",
    * description="api/payment-details",
    * operationId="payment_details",
    * tags={"Payment Details"},
    * security={ {"bearerAuth": {} }},
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="amount", type="string", example=""),
    *   @OA\Property(property="payment_intent", type="string", example=""),
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
    

    * @OA\Post(
    * path="/add-complaint",
    * summary="api/add-complaint",
    * description="api/add-complaint",
    * operationId="complaint_add",
    * tags={"Complaints"},
    * security={ {"bearerAuth": {} }},
    * @OA\Parameter(name="complaint_img", in="path", description="file of Article", required=false,
     *         @OA\Schema(type="file")
     *     ),
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="reason_id", type="number", example=""),
    *   @OA\Property(property="complaint_details", type="string", example=""),
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

    * @OA\Post(
    * path="/get-complaints",
    * summary="api/get-complaints",
    * description="api/get-complaints",
    * operationId="get_complaint",
    * tags={"Complaints"},
    * security={ {"bearerAuth": {} }},
    * @OA\Parameter(name="complaint_img", in="path", description="file of Article", required=false,
     *         @OA\Schema(type="file")
     *     ),
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
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



    * @OA\Post(
    * path="/get-complaint-reasons",
    * summary="api/get-complaint-reasons",
    * description="api/get-complaint-reasons",
    * operationId="get_complaint_resons",
    * tags={"Complaints"},
    * security={ {"bearerAuth": {} }},
    * @OA\Parameter(name="complaint_img", in="path", description="file of Article", required=false,
     *         @OA\Schema(type="file")
     *     ),
    * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
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


     * @OA\Get(
     * tags={"Locations"},
     *security={
     *        {
     *         "bearerAuth": {}},
     *},
     *     path="/getall-province",
     *     @OA\Response(response="200", description="An example resource")
     * )
     * 
     * @OA\Get(
     * tags={"Locations"},
     *security={
     *        {
     *         "bearerAuth": {}},
     *},
      * @OA\RequestBody(
    *    required=true,
    *    description="Form Data or JSON",
    *    @OA\JsonContent(
    *       required={"email","password"},    
    *   @OA\Property(property="token", type="string", example=""),
    *   @OA\Property(property="province_id", type="number", example=""),
    *    ),
    * ),
     *     path="/getall-cities-by-province",
     *     @OA\Response(response="200", description="An example resource")
     * )
     * 
     * 


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

