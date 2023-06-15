<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Mail\SendMailreset;
use App\Mail\SendOTPreset;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        if (!$this->validateEmail($request->email)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        $this->send($request->email);  //this is a function to send mail 
        return $this->successResponse();
    }

    public function send($email)  //this is a function to send mail 
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new SendMailreset($token, $email));  // token is important in send mail 
    }

    public function requestOtp(Request $request)
    {
           $otp = rand(100000,999999);
           $email = $request->email;
           $pass_reset = DB::table('password_resets')->where('email','=',$email)->get();
        //    Log::info("otp = ".$otp);
        // dd($pass_reset);
        if(count($pass_reset)>0){
            $user = DB::table('password_resets')->where('email','=',$request->email)->update(['token' => $otp]);
        }else{
            $user = DB::table('password_resets')->
               insert([
                'email' => $request->email,
                'token' => $otp
            ]);
        }
   
           if($user){
   
        //    $mail_details = [
        //        'subject' => 'Testing Application OTP',
        //        'body' => 'Your OTP is : '. $otp
        //    ];
          
            Mail::to($email)->send(new SendOTPreset($otp, $email));
          
            return response(["status" => 200, "message" => "OTP sent successfully", "email" => $request->email]);
           }
           else{
               return response(["status" => 401, 'message' => 'Invalid'],400);
           }
    }

       public function verifyOtp(Request $request){
    
        $user  = DB::table('password_resets')->where([['email','=',$request->email],['token','=',$request->otp]])->first();
        if($user){
            // auth()->login($user, true);
            DB::table('password_resets')->where('email','=',$request->email)->update(['token' => null]);
            // $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response(["status" => 200, "message" => "Success"]);
        }
        else{
            return response(["status" => 401, 'message' => 'Invalid'],400);
        }
    }

    public function createToken($email)  // this is a function to get your request email that there are or not to send mail
    {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(40);
        $this->saveToken($token, $email);
        return $token;
    }


    public function saveToken($token, $email)  // this function save new password
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }



    public function validateEmail($email)  //this is a function to get your email from database
    {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse()
    {
        return response()->json([
            'status' => false,
            'code' => 404,
            'message' => 'Email does\'t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Reset Email is send successfully, please check your inbox.',
        ], Response::HTTP_OK);
    }
}
