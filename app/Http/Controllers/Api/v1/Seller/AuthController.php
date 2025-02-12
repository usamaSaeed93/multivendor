<?php

namespace App\Http\Controllers\Api\v1\Seller;

use App\Helpers\CResponse;
use App\Helpers\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Strict\StrictSellerResource;
use App\Models\OtpVerification;
use App\Models\Seller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{


    public function login(Request $request): Response|Application|ResponseFactory
    {
        $validated_data = $this->validate($request, Seller::loginRules(), Seller::loginRuleMessage());

        $seller = Seller::getFromMobileOrEmail($request->get('mobile_number'), $request->get('email'));

        if ($seller) {
            if (Hash::check($request->get('password'), $seller->password)) {
                $token = $seller->createToken($request->get('fcm_token'));
                return CResponse::success(['seller' => new StrictSellerResource($seller), 'token' => $token->access_token]);
            } else {
                return CResponse::validation_error(['password' => 'Password is not correct']);
            }
        } else {
            return CResponse::validation_error(['email' => 'This email is not found', 'mobile_number' => 'This mobile number is not found']);

        }
    }


    public function google_login(Request $request): Response|Application|ResponseFactory
    {
        $validated_data = $this->validate($request, Seller::googleLoginRules());

        $google_user = FirebaseHelper::getUserFromUID($validated_data['uid']);
        if ($google_user == null) {
            return CResponse::error();
        }
        $email = $google_user->email;

        $user = Seller::where('email', $email)->first();
        if ($user == null) {
            return CResponse::error('This email is not found');
        }

        $token = $user->createToken($request->get('fcm_token'));

        return CResponse::success(['seller' => new StrictSellerResource($user), 'token' => $token->access_token]);
    }


    public function login_via_otp(Request $request): Response|Application|ResponseFactory
    {
        $this->validate($request, Seller::verifyOTPRule(),);
        $otp = $request->get('otp');
        $seller = Seller::getFromMobileOrEmail($request->get('mobile_number'), $request->get('email'));
        if ($seller) {
            $otp_verification = OtpVerification::getOTPVerificationByOTP(Seller::class, $seller->id, $otp);
            if ($otp_verification != null) {
                $otp_verification->delete();
                $token = $seller->createToken($request->get('fcm_token'));
                return CResponse::success(['seller' => new StrictSellerResource($seller), 'token' => $token->access_token]);

            } else {
                return CResponse::validation_error(['otp' => 'OTP is not correct']);
            }
        } else {
            return CResponse::validation_error(['email' => 'This email is not found', 'mobile_number' => 'This mobile number is not found']);

        }
    }

    public function send_otp(Request $request): Response|Application|ResponseFactory
    {
        $this->validate($request, Seller::sendOTPRule());
        $email = $request->get('email');
        $mobile_number = $request->get('mobile_number');
        $seller = Seller::getFromMobileOrEmail($mobile_number, $email);
        if ($seller) {
            if ($email) {
                $otp_verification = OtpVerification::createOTPTokenByEmail(Seller::class, $seller->id, $email);
                if ($otp_verification->sendViaEmail()) {
                    return CResponse::success('OTP was sent on registered email');
                }
            } else {
                $otp_verification = OtpVerification::createOTPTokenByMobile(Seller::class, $seller->id, $mobile_number);
                if ($otp_verification->sendViaSMS()) {
                    return CResponse::success('OTP was sent on registered mobile number');
                }
            }

        } else {
            return CResponse::validation_error(['email' => 'This email is not found', 'mobile_number' => 'This mobile number is not found']);

        }
        return CResponse::error();
    }


    public function logout(Request $request): Response|Application|ResponseFactory
    {

        if ($request->get('fcm_token') !== null) {
            $request->user()->deleteToken($request->get('fcm_token'));
        }

        return CResponse::success();
    }


}

