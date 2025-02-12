<?php

namespace App\Http\Controllers\Api\v1\Customer;

use App\Helpers\CResponse;
use App\Helpers\CValidator;
use App\Helpers\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Strict\StrictCustomerResource;
use App\Mail\CustomerEmailVerificationMail;
use App\Models\Customer;
use App\Models\EmailVerification;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{

    public function show(Request $request): StrictCustomerResource
    {
        $customer = $request->user();
        return new StrictCustomerResource($customer);
    }


    public function verify_mobile_number(Request $request): Application|ResponseFactory|Response|StrictCustomerResource
    {
        $data = $this->validate($request, [
            'uid' => ['required'],
            'mobile_number' => ['required']
        ]);

        $user = FirebaseHelper::getUserFromUID($data['uid']);
        $mobile_number = $data['mobile_number'];
        if ($user) {
            if ($user->phoneNumber != $mobile_number) {
                return CResponse::error("Mobile number is not verified");
            }
            $customer = $request->user();
            $customer->mobile_number = $mobile_number;
            $customer->mobile_number_verified_at = Carbon::now();
            $customer->save();
            return new StrictCustomerResource($customer);
        } else {
            return CResponse::error();
        }


    }

    public function send_verification_email(Request $request): Application|ResponseFactory|Response
    {

        $customer = $request->user();
        if ($customer->email_verified_at != null) {
            return CResponse::error('Already validated');
        }
        $email = $customer->email;
        if (!isset($email)) {
            $validated_data = $this->validate($request, [
                'email' => ['required']
            ]);
            $email = $validated_data['email'];
        }
        $token = EmailVerification::createEmailToken(Customer::class, $customer->id, $email);
        $send_message = Mail::to($email)->send(new CustomerEmailVerificationMail($token));
        if ($send_message != null) {
            return CResponse::success('Email was sent');
        } else {
            return CResponse::error();
        }

    }

    public function verify_email(Request $request): Response|Application|ResponseFactory
    {
        $token = $request->get('token');
        $email_verification = EmailVerification::getEmailVerification($token);
        if ($email_verification->verifiable_type == Customer::class) {
            $customer = Customer::where('email', $email_verification->email)->first();
            if ($customer) {
                $customer->email_verified_at = now();
                $customer->save();
                return CResponse::success('Your account is now verified');
            } else {
                return CResponse::no_content();
            }
        } else {
            return CResponse::no_content();
        }
    }


    public function update(Request $request): Application|ResponseFactory|Response|StrictCustomerResource
    {
        $customer = $request->user();

        if (env('DEMO', false) && env('DEMO_MAX_CUSTOMER_ID', 0) >= $customer->id) {
            return CResponse::demoCreateNewError('admin');
        }
        $data = [...$customer->toArray(), ...$request->all(),];

        $validated_data = CValidator::validate($data, Customer::rules($customer->id));
        $customer->fill($validated_data);
        $customer->saveAvatarImage($request);
        if ($request->has('password')) {
            $customer->password = Hash::make($request->get('password'));
        }

        $customer->save();
        return new StrictCustomerResource($customer);

    }

    public function remove_avatar(Request $request): StrictCustomerResource
    {
        $customer = $request->user();
        $customer->deleteAvatar();
        $customer->save();
        return new StrictCustomerResource($customer);
    }


    /**
     * TODO: ----------- You need to setup your own business logic to delete, which data
     */
    public function delete_account(Request $request): Application|ResponseFactory|Response
    {
        $customer = $request->user();
        $validated_data = CValidator::validate($request->all(), [
            'password' => 'required'
        ]);

        if (!Hash::check($validated_data['password'], $customer->password)) {
            return CResponse::validation_error(['password' => 'Password is wrong']);
        }

        //TODO: Here need to add -------------------
        return CResponse::success('Account deleted');
    }


}

