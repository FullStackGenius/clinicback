<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\BaseController as BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForgotpasswordController extends BaseController
{

    public function sendForgotPassword(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();
            if (empty($user)) {
                throw new Exception('User not found');
            }
            $token = Crypt::encrypt($user->id);
            $resetPasswordLink =  ProjectConstants::RESET_PASSWORD_LINK.$token;
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email], // Condition to check
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );
            $user->resetPasswordLink = $resetPasswordLink;
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            $data['resetPasswordLink'] = $resetPasswordLink;
            return  $this->sendCommonResponse('false', "",'', 'Forgotpassword mail send successfully. Check your email inbox.', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('sendForgotPassword Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '',  $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeForgotPassword(Request $request)
    {
        
        
        try {

            $request->validate([
                'token' => 'required',
                'password' => 'required'
            ]);

            $token = Crypt::decrypt($request->token);
            $user = User::find($token);

            $existingToken = DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->first();

            // Check if a token exists and if it's expired (more than 60 minutes old)
            if ($existingToken) {
                $createdAt = Carbon::parse($existingToken->created_at);
                if ($createdAt->diffInMinutes(Carbon::now()) > 60) {
                    throw new Exception('Token has expired. Please request a new password reset.');
                }
                $userData = User::where('email',$user->email)->where('id',$user->id)->first();
                $userData->password = bcrypt($request->password);
                $userData->save();
                DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            }else{
                throw new Exception('something went wrong');
            }
           
            
        return  $this->sendCommonResponse('false', "", '', 'Password reset successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('changeForgotPassword Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '',  $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
