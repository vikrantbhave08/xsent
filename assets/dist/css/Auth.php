<?php

namespace Illuminate\Support\Facades;

use Laravel\Ui\UiServiceProvider;
use RuntimeException;
Use Session;

use App\Models\User_model;
use App\Models\U_mu;
use App\Models\U_st;
use App\Models\U_country;
use App\Models\U_region;

/**
 * @method static \Illuminate\Auth\AuthManager extend(string $driver, \Closure $callback)
 * @method static \Illuminate\Auth\AuthManager provider(string $name, \Closure $callback)
 * @method static \Illuminate\Contracts\Auth\Authenticatable loginUsingId(mixed $id, bool $remember = false)
 * @method static \Illuminate\Contracts\Auth\Authenticatable|null user()
 * @method static \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard guard(string|null $name = null)
 * @method static \Illuminate\Contracts\Auth\UserProvider|null createUserProvider(string $provider = null)
 * @method static \Symfony\Component\HttpFoundation\Response|null onceBasic(string $field = 'email',array $extraConditions = [])
 * @method static bool attempt(array $credentials = [], bool $remember = false)
 * @method static bool check()
 * @method static bool guest()
 * @method static bool once(array $credentials = [])
 * @method static bool onceUsingId(mixed $id)
 * @method static bool validate(array $credentials = [])
 * @method static bool viaRemember()
 * @method static bool|null logoutOtherDevices(string $password, string $attribute = 'password')
 * @method static int|string|null id()
 * @method static void login(\Illuminate\Contracts\Auth\Authenticatable $user, bool $remember = false)
 * @method static void logout()
 * @method static void logoutCurrentDevice()
 * @method static void setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static void shouldUse(string $name);
 *
 * @see \Illuminate\Auth\AuthManager
 * @see \Illuminate\Contracts\Auth\Factory
 * @see \Illuminate\Contracts\Auth\Guard
 * @see \Illuminate\Contracts\Auth\StatefulGuard
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth';
    }

    /**
     * Register the typical authentication routes for an application.
     *
     * @param  array  $options
     * @return void
     *
     * @throws \RuntimeException
     */
    public static function routes(array $options = [])
    {
        if (! static::$app->providerIsLoaded(UiServiceProvider::class)) {
            throw new RuntimeException('In order to use the Auth::routes() method, please install the laravel/ui package.');
        }

        static::$app->make('router')->auth($options);
    }

    public static function admin_user()
    {
        $user= User_model::where('token', session('admin_token'))->first();
        if(!empty($user))
        {
            $users_region=User_model::select('users_region.region_id',)
                                    ->leftjoin('users_region', 'users.user_id', '=', 'users_region.user_id')
                                    ->where('users_region.user_id', $user->user_id)
                                    ->groupBy('users_region.region_id')
                                    ->get()->toArray();
            $user->users_region=!empty($users_region) ? array_column($users_region,'region_id') : array();

            $users_st=User_model::select('users_st.type_id',)
            ->leftjoin('users_st', 'users.user_id', '=', 'users_st.user_id')
            ->where('users_st.user_id', $user->user_id)
            ->groupBy('users_st.type_id')
            ->get()->toArray();
            $user->users_st=!empty($users_st) ? array_column($users_st,'type_id') : array();

            $users_mu=User_model::select('users_mu.mu_id',)
            ->leftjoin('users_mu', 'users.user_id', '=', 'users_mu.user_id')
            ->where('users_mu.user_id', $user->user_id)
            ->groupBy('users_mu.mu_id')
            ->get()->toArray();
            $user->users_mu=!empty($users_mu) ? array_column($users_mu,'mu_id') : array();

            $users_country=User_model::select('users_country.country_id',)
            ->leftjoin('users_country', 'users.user_id', '=', 'users_country.user_id')
            ->where('users_country.user_id', $user->user_id)
            ->groupBy('users_country.country_id')
            ->get()->toArray();
            $user->users_country=!empty($users_country) ? array_column($users_country,'country_id') : array();

        }

        return $user;
    }

    public static function psu_user()
    {
        $user= User_model::where('token', session('psu_token'))->first();

        if(!empty($user))
        {
            $users_region=User_model::select('users_region.region_id',)
                                    ->leftjoin('users_region', 'users.user_id', '=', 'users_region.user_id')
                                    ->where('users_region.user_id', $user->user_id)
                                    ->groupBy('users_region.region_id')
                                    ->get()->toArray();
            $user->users_region=!empty($users_region) ? array_column($users_region,'region_id') : array();

            $users_st=User_model::select('users_st.type_id',)
            ->leftjoin('users_st', 'users.user_id', '=', 'users_st.user_id')
            ->where('users_st.user_id', $user->user_id)
            ->groupBy('users_st.type_id')
            ->get()->toArray();
            $user->users_st=!empty($users_st) ? array_column($users_st,'type_id') : array();

            $users_mu=User_model::select('users_mu.mu_id',)
            ->leftjoin('users_mu', 'users.user_id', '=', 'users_mu.user_id')
            ->where('users_mu.user_id', $user->user_id)
            ->groupBy('users_mu.mu_id')
            ->get()->toArray();
            $user->users_mu=!empty($users_mu) ? array_column($users_mu,'mu_id') : array();

            $users_country=User_model::select('users_country.country_id',)
            ->leftjoin('users_country', 'users.user_id', '=', 'users_country.user_id')
            ->where('users_country.user_id', $user->user_id)
            ->groupBy('users_country.country_id')
            ->get()->toArray();
            $user->users_country=!empty($users_country) ? array_column($users_country,'country_id') : array();

        }

        return $user;
    }

    public static function pbm_user()
    {
        $user = User_model::where('token', session('pbm_token'))->first();

        if(!empty($user))
        {
            $users_region=User_model::select('users_region.region_id',)
                                    ->leftjoin('users_region', 'users.user_id', '=', 'users_region.user_id')
                                    ->where('users_region.user_id', $user->user_id)
                                    ->groupBy('users_region.region_id')
                                    ->get()->toArray();
            $user->users_region=!empty($users_region) ? array_column($users_region,'region_id') : array();

            $users_st=User_model::select('users_st.type_id',)
            ->leftjoin('users_st', 'users.user_id', '=', 'users_st.user_id')
            ->where('users_st.user_id', $user->user_id)
            ->groupBy('users_st.type_id')
            ->get()->toArray();
            $user->users_st=!empty($users_st) ? array_column($users_st,'type_id') : array();

            $users_mu=User_model::select('users_mu.mu_id',)
            ->leftjoin('users_mu', 'users.user_id', '=', 'users_mu.user_id')
            ->where('users_mu.user_id', $user->user_id)
            ->groupBy('users_mu.mu_id')
            ->get()->toArray();
            $user->users_mu=!empty($users_mu) ? array_column($users_mu,'mu_id') : array();

            $users_country=User_model::select('users_country.country_id',)
            ->leftjoin('users_country', 'users.user_id', '=', 'users_country.user_id')
            ->where('users_country.user_id', $user->user_id)
            ->groupBy('users_country.country_id')
            ->get()->toArray();
            $user->users_country=!empty($users_country) ? array_column($users_country,'country_id') : array();

        }

        return $user;
    }

    public static function pu_user()
    {
        return User_model::where('token', session('pu_token'))->first();
    }

    public static function sap_user()
    {
        return User_model::where('token', session('sap_token'))->first();
    }
}
