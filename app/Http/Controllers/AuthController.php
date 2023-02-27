<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Google2FA;

class AuthController extends Controller
{

    public function login(Request $request){



        $user = DB::table('users')
        ->where('email',$request->input('email'))
        ->where('password', $request->password)
        ->first();

        if(!$user){
            return redirect()->route('index')->with('error','Invalid email/password');
        }

        $request->session()->put('user_id', $user->id);
        return redirect()->route('google2fa');

    }

    public function register(Request $request){


        if($request->method() === 'GET'){
            return view('signup');
        }

        $alreadyExists = DB::table('users')->where('email',$request->email)->first();
        if($alreadyExists){
            return redirect()->back()->with('error','Email already exists');
        }



        $secret =  Google2FA::generateSecretKey();
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->secret= $secret;

        $device = Device::where('user_id', $user->id)
            ->where('name', gethostname())
            ->where('platform', php_uname('s'))
            ->where('session_id', session()->getId())
            ->first();

        $request->session()->put('google2fa_secret',$secret);
        $user->save();
        if (!$device) {
            Device::create([
                'user_id' => $user->id,
                'name' => gethostname(),
                'platform' => php_uname('s'),
                'session_id' => session()->getId(),
                'last_used_at' => now(),
            ]);
    }
        return redirect('/')->with('success','Registeration successful');
    }

    public function twoFaAuthentication(Request $request){

        if($request->method()==='GET'){
            return view('google2fa');
        }





        dd("2FA enabled");
    }


    public function askFor2FAAuthentication(Request $request){
        $userId = $request->session()->get('user_id');

        $user = User::find($userId);


        if(!$user){
            return redirect()->route('login')->with('error','Session lost! Please login again');
        }
        $currentSessionId = session()->getId();


        $device = Device::where('user_id', $user->id)
                ->where('session_id', $currentSessionId)
                ->first();

        if($request->method() == 'POST'){
            $verificationCode = $request->get('one_time_password');
            $secret = $request->session()->get('google2fa_secret');
            if (!$secret || !Google2FA::verifyGoogle2FA($secret, $verificationCode)) {
                // store secret in the session only for the next request
                $request->session()->reflash();
                return redirect()->route('google2fa')->with('error','Invalid OTP');

            }
            if(!$device){
                Device::create([
                    'user_id' => $user->id,
                    'name' => gethostname(),
                    'platform' => php_uname('a'),
                    'session_id' => $currentSessionId,
                    'last_used_at' => now(),
                ]);
            }
            $user->is_2fa_enabled = true;
            $user->save();
            dd("Enable and set true");

        }


        // if user 2FA isn't enabled
         if(!$user->is_2fa_enabled || !$device){

            // if secret doesn't exists
            // create and update immediately
            if(!$user->secret){
                $user->secret = Google2FA::generateSecretKey();
                User::where('id',$user->id)->update(['secret'=>$user->secret]);
            }

            $request->session()->flash('google2fa_secret', $user->secret);
            $request->session()->put('user_id', $user->id);

            $qrCode = !$user->is_2fa_enabled ? Google2FA::getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->secret,
                200
            ): null;
            $request->session()->put('google2fa_secret',$user->secret);


            return view('google2fa', [
                'qrCode' => $qrCode,
                'secret' => $user->secret,

                'error' => !$device ? 'Device not registered. Please verify your identity' : 'Please enable 2FA for security requirement',
                'id'=> $user->id
            ]);

        }

        else{
            dd("2FA + DEVICE both are valid");
        }



    }
}
