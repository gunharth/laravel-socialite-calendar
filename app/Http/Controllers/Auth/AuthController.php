<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Auth\Guard;
use App\Social;

class AuthController extends Controller
{
    protected $auth;

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->auth = $auth;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getSocialRedirect( $provider )
    {
        $providerKey = \Config::get('services.' . $provider);
        if(empty($providerKey))
            return view('pages.status')
                ->with('error','No such provider');

        return Socialite::driver( $provider )
            ->scopes([
                'https://www.googleapis.com/auth/calendar',
                'https://www.googleapis.com/auth/plus.me',
                'https://www.googleapis.com/auth/plus.login',
                'https://www.googleapis.com/auth/plus.profile.emails.read',
                ])->redirect();

    }


    public function getSocialHandle( $provider )
    {
        $user = Socialite::driver( $provider )->user();

        $socialUser = null;

        //Check is this email present
        $userCheck = User::where('email', '=', $user->email)->first();
        if(!empty($userCheck))
        {
            $socialUser = $userCheck;
        }
        else
        {
            $sameSocialId = Social::where('social_id', '=', $user->id)->where('provider', '=', $provider )->first();

            if(empty($sameSocialId))
            {
                //There is no combination of this social id and provider, so create new one
                $newSocialUser = new User;
                $newSocialUser->email              = $user->email;
                $name = explode(' ', $user->name);
                $newSocialUser->name         = $name[0];
                //$newSocialUser->last_name          = $name[1];
                $newSocialUser->save();

                $socialData = new Social;
                $socialData->social_id = $user->id;
                $socialData->provider= $provider;
                $newSocialUser->social()->save($socialData);

                // Add role
                //$role = Role::whereName('user')->first();
                //$newSocialUser->assignRole($role);

                $socialUser = $newSocialUser;
            }
            else
            {
                //Load this existing social user
                $socialUser = $sameSocialId->user;
            }

        }

        $this->auth->login($socialUser, true);

        /*if( $this->auth->user()->hasRole('user'))
        {
            return redirect()->route('user.home');
        }

        if( $this->auth->user()->hasRole('administrator'))
        {
            return redirect()->route('admin.home');
        }*/
        /*$optParams = array(
          'maxResults' => 999,
          'orderBy' => 'startTime',
          //'timeMin' => $timeMin,
          //'timeMax' => $timeMax,
          'singleEvents' => true
        );
        $allevents = $this->service->events->listEvents('p3ol3iomgjspr2nssld48jm9f8@group.calendar.google.com', $optParams);
        $events = $allevents->items;
        foreach ($events as $event) {
            $items[] = array(
                'id' => $event->id,
                'title' => $event->summary,
                'start' => $event->start->dateTime,
                'end' => $event->end->dateTime,
                'description' => $event->description
            );
        }
        return $items;*/
        //return $token = $user->token;
        /*$client = new \Google_Client();

        $client->setAccessToken($user->token);
        $service = new \Google_Service_Calendar($client);*/
        
        session(['access_token' => $user->token]);
//return $user->token;
  //      return session('access_token');
        return redirect()->action('CalendarController@index');
        //return \App::abort(500);
    }
}
