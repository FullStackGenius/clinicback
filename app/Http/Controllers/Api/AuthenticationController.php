<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\UserEmailVerification;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;

class AuthenticationController extends BaseController
{

    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function accountType()
    {
        try {
            $roles['roles'] = Role::select('id', 'name', 'description')->where('id', '!=', 1)->get();
            return  $this->sendCommonResponse('false', "", $roles, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('accountType Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function register(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:credential,google,apple',
                'id' => 'required_if:type,google,apple',
            ]);
            // For Google-based login or Register
            if ($request->type == 'google') {
                $checkUser = User::where('google_id', $request->id)->first();
                if (empty($checkUser)) {
                    $request->validate([
                        'id' => 'required_if:type,google',
                        'email' => 'required_if:type,google|email|unique:users,email',
                        'first_name' => 'required_if:type,google'
                    ]);
                    $user = User::create([
                        'google_id' => $request->id,
                        'name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'role_id' => $request->role_id,
                        'user_status' => 1,
                        'accept_condition' => 1,
                        'email_verified_at' => now(),
                    ]);
                    $user->userDetails()->create([
                        'completed_steps' => 0,
                        'next_step' => 0,
                    ]);
                    if (isset($request->phone_no) && !empty($request->phone_no)) {
                        $user->userDetails()->updateOrCreate(
                            ['user_id' => $user->id],
                            ['phone_number' => $request->phone_no]
                        );
                    }
                    $data['token'] =  $user->createToken('api-token')->plainTextToken;
                    $data['details'] =  $user;
                    $data['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $user->id)->first();
                } else {
                    $data['token'] =  $checkUser->createToken('api-token')->plainTextToken;
                    $data['details'] =  $checkUser;
                    $data['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $checkUser->id)->first();
                }
            }

            // For apple-based login or register
            if ($request->type == 'apple') {
                $checkUser = User::where('apple_id', $request->id)->first();
                if (empty($checkUser)) {
                    $request->validate([
                        'id' => 'required_if:type,apple',
                        'email' => 'required_if:type,apple|email|unique:users,email',
                        'first_name' => 'required_if:type,apple',
                    ]);
                    $user = User::create([
                        'apple_id' => $request->id,
                        'name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'role_id' => $request->role_id,
                        'user_status' => 1,
                        'accept_condition' => 1,
                        'email_verified_at' => now(),
                    ]);
                    $user->userDetails()->create([
                        'completed_steps' => 0,
                        'next_step' => 0,
                    ]);
                    if (isset($request->phone_no) && !empty($request->phone_no)) {
                        $user->userDetails()->updateOrCreate(
                            ['user_id' => $user->id],
                            ['phone_number' => $request->phone_no]
                        );
                    }
                    $data['token'] =  $user->createToken('api-token')->plainTextToken;
                    $data['details'] =  $user;
                    $data['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $user->id)->first();
                } else {
                    $data['token'] =  $checkUser->createToken('api-token')->plainTextToken;
                    $data['details'] =  $checkUser;
                    $data['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $checkUser->id)->first();
                }
            }

            if ($request->type == 'credential') {
                $request->validate([
                    'first_name' => 'required',
                    'last_name' => 'sometimes',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:8',
                    'role_id' => ['required', 'in:2,3'],
                ]);
                $user = User::create([
                    'name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'accept_condition' => 1,
                    'role_id' => $request->role_id,
                    'password' => bcrypt($request->password),

                ]);
                $user->userDetails()->create([
                    'completed_steps' => 0,
                    'next_step' => 0,
                ]);
                 $this->mailService->safeSend($user->email,new UserEmailVerification($user),'register mail');
              //  Mail::to($user->email)->send(new UserEmailVerification($user));
                $data['emailVerifyResendLink'] =  route('resend-email-verification-link', Crypt::encrypt($user->id));
                $data['emailAddress'] =  $user->email;
            }
            if (!empty($data)) {
                return  $this->sendCommonResponse('false', "", $data, 'User registered successfully. Please verify your email. Check your email inbox.', "", ProjectConstants::HTTP_OK);
            } else {
                return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('Register Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    public function login(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:credential,google,apple',
                'id' => 'required_if:type,google,apple',
            ]);
            // For credential-based login
            if ($request->type == 'credential') {
                $request->validate([
                    'email' => 'required_if:type,credential|email',
                    'password' => 'required_if:type,credential',
                ]);
                $checkUser = User::where('email', $request->email)->first();
                if (!$checkUser) {
                    return $this->sendCommonResponse(true, 'error', '', 'Invalid credentials', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
                }
                if ($checkUser->user_status == ProjectConstants::STATUS_INACTIVE) {
                    $success['emailAddress'] =  $checkUser->email;
                    $success['emailVerifyResendLink'] =  route('resend-email-verification-link', Crypt::encrypt($checkUser->id));
                    $success['unverified'] =  $checkUser->user_status;
                    return  $this->sendCommonResponse('false', "", $success, 'Your account requires email verification. Please verify your email to access the website.', '', ProjectConstants::HTTP_OK);
                    //return $this->sendCommonResponse(true, 'error', '', 'Invalid credentials', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
                }
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $user = Auth::user();
                    $success['token'] = $user->createToken('api-token')->plainTextToken;
                    $success['details'] = $user;
                    $success['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $user->id)->first();
                }
            }

            // For Google-based login or register
            if ($request->type == 'google') {
                Log::channel('daily')->info('google login type request \n: ' . json_encode($request->all()));
                $checkUser = User::where('google_id', $request->id)->first();
             
                if (empty($checkUser)) {
                    $request->validate([
                        'id' => 'required_if:type,google',
                        'email' => 'required_if:type,google|email',
                        'name' => 'required_if:type,google',
                    ]);
                    $checkUserEmail = User::where('email', $request->email)->first();
                    if (!empty($checkUserEmail)) {
                       
                        $checkUserEmail->update([
                            'google_id' => $request->id,
                            'user_status' => 1,
                            'accept_condition' => 1,
                        ]);
                     $user = $checkUserEmail;
                    }else{
                        $user = User::create([
                            'google_id' => $request->id,
                            'name' => $request->name,
                            'email' => $request->email,
                            'user_status' => 1,
                            'accept_condition' => 1,
                            'email_verified_at' => now(),
                        ]);
                    }
                    
                    $user->userDetails()->create([
                        'completed_steps' => 0,
                        'next_step' => 0,
                    ]);
                    if (isset($request->phone_no) && !empty($request->phone_no)) {
                        $user->userDetails()->updateOrCreate(
                            ['user_id' => $user->id],
                            ['phone_number' => $request->phone_no]
                        );
                    }
                    $success['token'] =  $user->createToken('api-token')->plainTextToken;
                    $success['details'] =  $user;
                    $success['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $user->id)->first();
                } else {
                    $success['token'] =  $checkUser->createToken('api-token')->plainTextToken;
                    $success['details'] =  $checkUser;
                    $success['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $checkUser->id)->first();
                }
            }


            // For apple-based login and register
            if ($request->type == 'apple') {
                $checkUser = User::where('apple_id', $request->id)->first();
                if (empty($checkUser)) {
                    $request->validate([
                        'id' => 'required_if:type,apple',
                        'email' => 'required_if:type,apple|email|unique:users,email',
                        'name' => 'required_if:type,apple',
                    ]);
                    $user = User::create([
                        'apple_id' => $request->id,
                        'name' => $request->name,
                        'email' => $request->email,
                        'user_status' => 1,
                        'accept_condition' => 1,
                        'email_verified_at' => now()
                    ]);
                    $user->userDetails()->create([
                        'completed_steps' => 0,
                        'next_step' => 0,
                    ]);
                    if (isset($request->phone_no) && !empty($request->phone_no)) {
                        $user->userDetails()->updateOrCreate(
                            ['user_id' => $user->id],
                            ['phone_number' => $request->phone_no]
                        );
                    }
                    $success['token'] =  $user->createToken('api-token')->plainTextToken;
                    $success['details'] =  $user;
                    $success['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $user->id)->first();
                } else {
                    $success['token'] =  $checkUser->createToken('api-token')->plainTextToken;
                    $success['details'] =  $checkUser;
                    $success['steps'] = UserDetail::select('next_step','completed_steps')->where('user_id', $checkUser->id)->first();
                }
            }
            if (!empty($success)) {
                return  $this->sendCommonResponse('false', "", $success, 'Login successful', $success['token'], ProjectConstants::HTTP_OK);
            } else {
                return $this->sendCommonResponse(true, 'error', '', 'Invalid credentials', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('login Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function resendEmailVerificationLink($token = null)
    {
        try {
            $token = Crypt::decrypt($token);
            if (empty($token)) {
                return $this->sendCommonResponse(true, 'error', '', 'Invalid or expired verification link.', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
            }
            $user =  User::find($token);
            if (empty($user)) {
                return $this->sendCommonResponse(true, 'error', '', 'Invalid or expired verification link.', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
            }

            $checkAlreadyVerify =  User::whereNotNull('email_verified_at')->where('id', $token)->first();
            if (!empty($checkAlreadyVerify)) {
                return  $this->sendCommonResponse('false', "", '', 'Email verification not required — already verified.', "", ProjectConstants::HTTP_OK);
            }
            $this->mailService->safeSend($user->email,new UserEmailVerification($user),'register mail');
          //  Mail::to($user->email)->send(new UserEmailVerification($user));
            return  $this->sendCommonResponse('false', "", '', 'Email sent! Please check your inbox to verify your address', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('resendEmailVerificationLink  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {

        try {
            $request->user()->currentAccessToken()->delete();
            return  $this->sendCommonResponse('false', "", '', 'Logged out successfully', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('logout  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
