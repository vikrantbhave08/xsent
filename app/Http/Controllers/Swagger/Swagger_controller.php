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


class Swagger_controller extends Controller
{

    public function __construct(Request $request)
    {               
        $this->middleware('CheckSwaggerBearer:swagger');       
    }

   
       
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
     


     * @OA\Get(
     *      path="/allusers",
     *      operationId="getUserList",
     *      tags={"Users"},
         * security={ {"bearerAuth": {} }},
     *      summary="Get list of users",
     *      description="Returns list of users",
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
     *  )


     * @OA\Get(
     * tags={"Getall-Province"},
     *security={
     *        {
     *         "passport": {}},
     *},
     *     path="/getall-province",
     *     @OA\Response(response="200", description="An example resource")
     * )
   


    * @OA\Post(
    * path="/login",
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


    public function login_auth(Request $request)
    {
        $validator = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

       

        if (!auth()->attempt($validator)) {
            return response()->json(['error' => 'Unauthorised'], 401);
        } else {
            $success['token'] = auth()->user()->createToken('authToken')->accessToken;
            $success['user'] = auth()->user();
            return response()->json(['success' => $success])->setStatusCode(Response::HTTP_ACCEPTED);
        }
    }


    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    
    public function all_users(Request $request)
    {
        
        echo "all users";
        
    }
   
}
