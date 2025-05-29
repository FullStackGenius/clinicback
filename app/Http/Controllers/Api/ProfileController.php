<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\UserDetail;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserLanguage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;


class ProfileController extends BaseController
{

    public function saveUserRole(Request $request)
    {

        try {
            $request->validate([
                'role' => 'required|integer',
            ]);
            $user = Auth::user();
            $user = User::where('id', $user->id)->first();
            $user->role_id = $request->role;
            $user->save();
            $data['user'] = $user;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveUserRole  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function saveZeroStep(Request $request)
    {

        try {

            $user = Auth::user();
            $this->UpdateYourCurrentStep($user, 1, 0);
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            $data['details'] = $user;
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveZeroStep  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function saveFreelanceExp(Request $request)
    {

        try {

            $request->validate([
                'user_freelance_exp' => 'required|integer',
            ]);
            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($userDetail)) {

                UserDetail::where('user_id', $user->id)->update([
                    'your_experience_id' => $request->user_freelance_exp,
                    'next_step' => 2,
                    'completed_steps' => 1
                ]);
            } else {
                UserDetail::create([
                    'user_id' => $user->id,
                    'your_experience_id' => $request->user_freelance_exp,
                    'next_step' => 2,
                    'completed_steps' => 1
                ]);
            }
            $data['details'] = UserDetail::select('id', 'your_experience_id') // Select the foreign key
                ->with([
                    'yourExperience' => function ($query) {
                        $query->select('id', 'name'); // Select specific columns
                    }
                ])
                ->where('user_id', $user->id)
                ->first();
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveFreelanceExp  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveFreelanceGoal(Request $request)
    {

        try {
            $request->validate([
                'user_freelance_goal' => 'required|integer',
            ]);

            // $user = Auth::user();
            //  $user->createMeta('user_freelance_goal' ,$request->user_freelance_goal);
            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($userDetail)) {

                $userDetail =  UserDetail::where('user_id', $user->id)->update([
                    'your_goal_id' => $request->user_freelance_goal,
                    'next_step' => 3,
                    'completed_steps' => 2
                ]);
            } else {
                $userDetail = UserDetail::create([
                    'user_id' => $user->id,
                    'your_goal_id' => $request->user_freelance_goal,
                    'next_step' => 3,
                    'completed_steps' => 2
                ]);
            }
            $data['details'] = UserDetail::select('your_goal_id', 'id', 'user_id')->where('user_id', $user->id)->first();
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveFreelanceGoal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function UpdateYourCurrentStep($user, $next_step, $completed_steps)
    {
        $userDetail = UserDetail::where('user_id', $user->id)->first();
        if (!empty($userDetail)) {

            $userDetail =  UserDetail::where('user_id', $user->id)->update([

                'next_step' => $next_step,
                'completed_steps' => $completed_steps
            ]);
        } else {
            $userDetail = UserDetail::create([
                'user_id' => $user->id,
                'next_step' => $next_step,
                'completed_steps' => $completed_steps
            ]);
        }
    }

    public function saveHowLikeToWork(Request $request)
    {

        try {
            $request->validate([
                'user_like_to_work' => 'required|array',
                'user_like_to_work.*' => 'integer',
            ]);
            $user = Auth::user();
            // $user->getHowLikeToWork()->sync($request->user_like_to_work);
            $user->getHowLikeToWork()->updateOrCreate(
                ['user_id' => $user->id],
                ['how_like_to_work_id' => $request->user_like_to_work[0]]
            );
            $this->UpdateYourCurrentStep($user, 4, 3);
            $data['details'] = User::select('id', 'name')->with('getHowLikeToWork')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveHowLikeToWork  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveUserSubCategory(Request $request)
    {

        try {
            $request->validate([
                'sub_category' => 'required|array',
                'sub_category.*' => 'integer',
            ]);

            $user = Auth::user();
            $user->subCategory()->sync($request->sub_category);
            $this->UpdateYourCurrentStep($user, 6, 5);
            $data['details'] = User::select('id', 'name')->with('subCategory:id,name,category_id')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveUserSubCategory  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function saveProfileImage(Request $request)
    // {

    //     try {
    //         $validated = $request->validate([
    //             'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max size: 2MB
    //         ]);

    //         // If validation passes, store the image in the 'public/user-image' folder
    //         $image = $request->file('profile_image');
    //         $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

    //         // Store the image in the storage folder
    //         $path = $image->storeAs('user-image', $imageName, 'public');
    //         $user = Auth::user();

    //         if ($user->profile_image) {
    //             Storage::disk('public')->delete('user-image/' . $user->profile_image);
    //         }
    //         $id = $user->id;

    //         User::where('id', $id)->update([
    //             'profile_image' => $imageName
    //         ]);

    //         $user = User::find($id);
    //         $data['details'] = $user;
    //         return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
    //     } catch (ValidationException $e) {
    //         return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     } catch (\Exception $e) {
    //         Log::channel('daily')->info('saveProfileImage  Api log \n: ' . $e->getMessage());
    //         return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function saveProfileImage(Request $request)
    {
        try {
            // Validate image input
            $validated = $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max size: 2MB
            ]);

            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                return $this->sendCommonResponse(true, 'error', '', 'User not authenticated', '', ProjectConstants::HTTP_UNAUTHORIZED);
            }

            // Initialize Intervention Image
            $manager = new ImageManager(new Driver());

            // Get uploaded image
            $image = $request->file('profile_image');

            // Generate a unique filename (WebP format)
            $imageName = Str::uuid() . '.webp';

            // Process & encode image to WebP
            $processedImage = $manager->read($image->getRealPath())
                ->encode(new WebpEncoder(quality: 80));

            // Store the processed image
            Storage::disk('public')->put("user-image/{$imageName}", $processedImage);

            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete("user-image/{$user->profile_image}");
            }

            // Update user's profile image
            $user->update(['profile_image' => $imageName]);

            // Prepare response data
            $data['details'] = $user;

            return $this->sendCommonResponse(false, "", $data, 'Profile image updated successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProfileImage Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'Something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveUserSkill(Request $request)
    {

        try {
            $request->validate([
                'skills' => 'required|array',
                'skills.*' => 'integer',
            ]);

            $user = Auth::user();
            $user->skills()->sync($request->skills);
            $this->UpdateYourCurrentStep($user, 7, 6);
            $data['details'] = User::select('id', 'name')->with('skills:id,name')->find($user->id);;
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveUserSkill  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveProfileHeadline(Request $request)
    {

        try {
            $request->validate([
                'headline' => 'required'
            ]);

            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($userDetail)) {

                UserDetail::where('user_id', $user->id)->update([
                    'profile_headline' => $request->headline

                ]);
            } else {
                UserDetail::create([
                    'user_id' => $user->id,
                    'profile_headline' => $request->headline

                ]);
            }
            $this->UpdateYourCurrentStep($user, 8, 7);
            $data['details'] = UserDetail::select('id', 'user_id', 'profile_headline')->where('user_id', $user->id)->first();
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProfileHeadline  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveAboutYourSelf(Request $request)
    {

        try {
            $request->validate([
                'about_yourself' => 'required'
            ]);

            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($userDetail)) {

                UserDetail::where('user_id', $user->id)->update([
                    'about_yourself' => $request->about_yourself

                ]);
            } else {
                UserDetail::create([
                    'user_id' => $user->id,
                    'about_yourself' => $request->about_yourself

                ]);
            }
            $this->UpdateYourCurrentStep($user, 12, 11);
            $data['details'] =  UserDetail::select('id', 'user_id', 'about_yourself')->where('user_id', $user->id)->first();;
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveAboutYourSelf  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveFreelanceRate(Request $request)
    {

        try {
            $request->validate([
                'hour_rate' => 'required',
                'service_rate' => 'required',
                'income' => 'required'
            ]);

            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($userDetail)) {

                UserDetail::where('user_id', $user->id)->update([
                    'hourly_rate' => $request->hour_rate,
                    'services_rate' => $request->service_rate,
                    'income_per_hour' => $request->income

                ]);
            } else {
                UserDetail::create([
                    'user_id' => $user->id,
                    'hourly_rate' => $request->hour_rate,
                    'services_rate' => $request->service_rate,
                    'income_per_hour' => $request->income

                ]);
            }
            $this->UpdateYourCurrentStep($user, 13, 12);
            $data['details'] = UserDetail::select('id', 'user_id', 'hourly_rate', 'services_rate', 'income_per_hour')->where('user_id', $user->id)->first();;
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveFreelanceRate  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveProfileDetail(Request $request)
    {

        try {
            $request->validate([
                'date_of_birth' => 'required|date',
                'street_address' => 'required',
                //'apt_suite' => 'required',
                'city' => 'required',
                'state_provience' => 'required',
                'zip_postalcode' => 'required',
                'phone_number' => 'required|integer',
                //'country' => 'required|integer',
            ]);

            $user = Auth::user();
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            if (!empty($request->country)) {
                User::where('id', $user->id)->update([
                    'country_id' => $request->country
                ]);
            }
            if (!empty($userDetail)) {

                UserDetail::where('user_id', $user->id)->update([
                    'date_of_birth' => $request->date_of_birth,
                    'street_address' => $request->street_address,
                    'apt_suite' => (!empty($request->apt_suite)) ? $request->apt_suite : '',
                    'city' => $request->city,
                    'state_provience' => $request->state_provience,
                    'zip_postalcode' => $request->zip_postalcode,
                    'phone_number' => $request->phone_number

                ]);
            } else {
                UserDetail::create([
                    'user_id' => $user->id,
                    'date_of_birth' => $request->date_of_birth,
                    'street_address' => $request->street_address,
                    'apt_suite' => (!empty($request->apt_suite)) ? $request->apt_suite : '',
                    'city' => $request->city,
                    'state_provience' => $request->state_provience,
                    'zip_postalcode' => $request->zip_postalcode,
                    'phone_number' => $request->phone_number

                ]);
            }
            $this->UpdateYourCurrentStep($user, 14, 13);
            $data['details'] = User::select("id", "name", "last_name")->with('userDetails')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProfileDetail  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function saveUserExperience(Request $request)
    {

        try {
            $request->validate([
                'title' => 'required',
                'company' => 'required',
                'location' => 'required',
                'country' => 'required',
                'start_month' => 'required',
                'start_year' => 'required',
                // 'end_month' => 'required',
                // 'end_year' => 'required',
                'description' => 'required'
            ]);

            $user = Auth::user();

            if ($request->type == 'edit') {
                $request->validate([
                    'user_experience_id' => 'required',
                ]);
                $userExperience  = UserExperience::find($request->user_experience_id);
                if (empty($userExperience)) {
                    throw new \Exception('something went wrongs');
                }
                $userExperience->title = $request->title;
                $userExperience->company = $request->company;
                $userExperience->location = $request->location;
                $userExperience->country_id = $request->country;
                $userExperience->start_month = $request->start_month;
                $userExperience->start_year = $request->start_year;
                $userExperience->end_month = ($request->currently_working == 0) ? $request->end_month : NULL;
                $userExperience->end_year = ($request->currently_working == 0) ? $request->end_year : NULL;
                $userExperience->description = $request->description;
                $userExperience->currently_working = $request->currently_working;
                $userExperience->save();
            } else {
                $userExperience =  UserExperience::create([
                    'user_id' => $user->id,
                    'title' => $request->title,
                    'company' => $request->company,
                    'location' => $request->location,
                    'country_id' => $request->country,
                    'start_month' => $request->start_month,
                    'start_year' => $request->start_year,
                    'end_month' => ($request->currently_working == 0) ? $request->end_month : NULL,
                    'end_year' => ($request->currently_working == 0) ? $request->end_year : NULL,
                    'description' => $request->description,
                    'currently_working' => $request->currently_working

                ]);
            }
            $this->UpdateYourCurrentStep($user, 9, 8);
            $data['details'] = User::select("id", "name", "last_name", 'profile_image', 'country_id')->with('userExperiences')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveUserExperience  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveLanguage(Request $request)
    {

        try {
            $request->validate([
                'language' => 'required|integer',
                'language_proficiency' => 'required|integer'
            ]);
            $user = Auth::user();
            if ($request->type == 'edit') {
                $request->validate([
                    'user_language_id' => 'required',
                ]);
                $userLanguage  = UserLanguage::find($request->user_language_id);
                if (empty($userLanguage)) {
                    throw new \Exception('something went wrongs');
                }
                $userLanguage->language_id  = $request->language;
                $userLanguage->language_proficiency_id   = $request->language_proficiency;
                $userLanguage->save();
            } else {
                $user->languages()->attach($request->language, ['language_proficiency_id' => $request->language_proficiency]);
            }
            $this->UpdateYourCurrentStep($user, 11, 10);
            $data['details'] =  User::select("id", "name", "last_name", 'profile_image', 'country_id')->with('userLanguage')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveLanguage  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveUserEducation(Request $request)
    {

        try {
            $request->validate([
                'school' => 'required',
                'degree' => 'required',
                'field_of_study' => 'required',
                'start_date_attended' => 'required',
                'end_date_attended' => 'required',
                'description' => 'required'
            ]);

            $user = Auth::user();
            if ($request->type == 'edit') {
                $request->validate([
                    'user_education_id' => 'required',
                ]);
                $userEduction  = UserEducation::find($request->user_education_id);
                if (empty($userEduction)) {
                    throw new \Exception('something went wrongs');
                }
                $userEduction->user_id = $user->id;
                $userEduction->school = $request->school;
                $userEduction->degree =  $request->degree;
                $userEduction->field_of_study = $request->field_of_study;
                $userEduction->start_date_attended = $request->start_date_attended;
                $userEduction->end_date_attended = $request->end_date_attended;
                $userEduction->description = $request->description;
                $userEduction->save();
            } else {
                $userEducation =  UserEducation::create([
                    'user_id' => $user->id,
                    'school' => $request->school,
                    'degree' => $request->degree,
                    'field_of_study' => $request->field_of_study,
                    'start_date_attended' => $request->start_date_attended,
                    'end_date_attended' => $request->end_date_attended,
                    'description' => $request->description

                ]);
            }
            $this->UpdateYourCurrentStep($user, 10, 9);
            $data['details'] = User::select("id", "name", "last_name")->with('userEducation')->find($user->id);
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveUserEducation  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function saveFreelancerResume(Request $request)
    {

        try {
            $request->validate([
                'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            ]);

            // If validation passes, store the image in the 'public/user-image' folder
            $image = $request->file('resume');
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

            // Store the image in the storage folder
            $path = $image->storeAs('freelancer-resume', $imageName, 'public');
            $user = Auth::user();
            // User::where('id', $id)->update([
            //     'profile_image' => $imageName
            // ]);

            $user->resume()->updateOrCreate(
                ['user_id' => $user->id],
                ['resume_url' => $imageName]
            );
            $this->UpdateYourCurrentStep($user, 5, 4);
            $resume = $user->resume;
            if ($resume) {
                $resume->resume_path = asset('storage/freelancer-resume/' . $resume->resume_url);
            }
            $data['details'] = $resume;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {

            Log::channel('daily')->info('saveFreelancerResume  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveFourStep(Request $request)
    {

        try {

            $user = Auth::user();
            $this->UpdateYourCurrentStep($user, 5, 4);
            $userDetail = UserDetail::where('user_id', $user->id)->first();
            $data['details'] = $user;
            $data['steps'] = UserDetail::select('next_step', 'completed_steps')->where('user_id', $user->id)->first();
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveFourStep  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    public function getUserExperience()
    {

        try {
            $user = Auth::user();
            $data['details'] = User::select("id", "name", "last_name")->with('userExperiences')->find($user->id);
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getUserExperience  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getUserEducation()
    {

        try {
            $user = Auth::user();
            $data['details'] = User::select("id", "name", "last_name", 'country_id', 'profile_image')->with('userEducation')->find($user->id);
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getUserEducation  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserStepData(Request $request)
    {

        try {
            $user = Auth::user();
            // $request->validate([
            //     'user_id' => 'required',
            //     'name' => 'required',
            // ]);
            // $user = User::find($request->user_id);
            // if (empty($user)) {
            //     throw new \Exception('something went wrong');
            // }
            $action = $request->name;
            switch ($action) {
                case 'step1':

                    $details = $this->getDataWithStep('step1', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step2':
                    $details =  $this->getDataWithStep('step2', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step3':
                    $details =  $this->getDataWithStep('step3', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step4':
                    $details = $this->getDataWithStep('step4', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step5':
                    $details =   $this->getDataWithStep('step5', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step6':
                    $details =   $this->getDataWithStep('step6', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step7':
                    $details =  $this->getDataWithStep('step7', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step8':
                    $details =  $this->getDataWithStep('step8', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;
                case 'step9':
                    $details =  $this->getDataWithStep('step9', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step10':
                    $details =  $this->getDataWithStep('step10', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                default:

                    $details['step1'] =  $this->getDataWithStep('step1', $user);
                    $details['step2'] =  $this->getDataWithStep('step2', $user);
                    $details['step3'] =  $this->getDataWithStep('step3', $user);
                    $details['step4'] =  $this->getDataWithStep('step4', $user);
                    $details['step5'] =  $this->getDataWithStep('step5', $user);
                    $details['step6'] =  $this->getDataWithStep('step6', $user);
                    $details['step7'] =  $this->getDataWithStep('step7', $user);
                    $details['step8'] =  $this->getDataWithStep('step8', $user);
                    $details['step9'] =  $this->getDataWithStep('step9', $user);
                    $details['step10'] =  $this->getDataWithStep('step10', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;
            }
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getUserStepData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getDataWithStep($stepName, $user)
    {
        if ($stepName == 'step1') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')
                ->with(['resume' => function ($query) {
                    $query->select('id', 'user_id', 'resume_url');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            $resume = $userData->resume;
            if ($resume) {
                $resume->resume_path = asset('storage/freelancer-resume/' . $resume->resume_url);
            }
            return $details =  $userData;
        }

        if ($stepName == 'step2') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')->with('subCategory:id,name,category_id')
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }
        if ($stepName == 'step3') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')->with('skills:id,name')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step4') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')
                ->withAvg('ratings', 'rating_number')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'profile_headline');
                }])
                ->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step5') {
            $userData = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id')->with('userExperiences')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step6') {
            $userData = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id')->with('userEducation')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step7') {
            $data['language'] = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id')->with('userLanguage')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $data;
        }

        if ($stepName == 'step8') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'about_yourself');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step9') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'hourly_rate', 'services_rate', 'income_per_hour');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }


        if ($stepName == 'step10') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'date_of_birth', 'street_address', 'apt_suite', 'city', 'state_provience', 'zip_postalcode', 'phone_number');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            if (isset($userData->userDetails) && !empty($userData->userDetails)) {
                $userData->userDetails->country = @$userData->country_name;
            }
            return $details =  $userData;
        }
    }


    public function deleteFreelancerStepData(Request $request)
    {
        try {

            $request->validate([
                'type' => 'required|in:experience,education,language',
                'delete_id' => 'required|integer'
            ]);

            if ($request->type == 'experience') {
                $user = Auth::user(); // Fetch the user
                $experience = $user->userExperiences()->where('id', $request->delete_id)->first();
                if (empty($experience)) {
                    throw new \Exception('something went wrong');
                }
                $experience->delete();
            }

            if ($request->type == 'education') {
                $user = Auth::user(); // Fetch the user
                $education = $user->userEducation()->where('id', $request->delete_id)->first();
                if (empty($education)) {
                    throw new \Exception('something went wrong');
                }
                $education->delete();
            }

            if ($request->type == 'language') {
                $user = Auth::user(); // Fetch the user
                $language = $user->userLanguage()->where('id', $request->delete_id)->first();
                if (empty($language)) {
                    throw new \Exception('something went wrong');
                }
                $language->delete();
            }
            $data = [];
            return  $this->sendCommonResponse('false', "", $data, 'data delete successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('deleteFreelancerStepData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSingleUserExperienceEducation(Request $request)
    {

        try {

            $request->validate([
                'type' => 'required|in:experience,education',
                'get_id' => 'required|integer'
            ]);
            $user = Auth::user();
            if ($request->type == 'experience') {
                $userData  = UserExperience::where('user_id', $user->id)->where('id', $request->get_id)->first();
                if (empty($userData)) {
                    throw new \Exception('something went wrong');
                }
            }

            if ($request->type == 'education') {
                $userData  = UserEducation::where('user_id', $user->id)->where('id', $request->get_id)->first();
                if (empty($userData)) {
                    throw new \Exception('something went wrong');
                }
            }
            $data['details'] = $userData;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getSingleUserExperienceEducation  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLoggedInUserInfo()
    {

        try {
            $user = Auth::user();
            $data['details'] = $user;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getLoggedInUserInfo  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUserInfo(Request $request)
    {

        try {
            $user = Auth::user();
            $request->validate([
                'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            ]);
            $name = $request->name;
            $last_name = $request->last_name;
            $email = $request->email;
            $phone_number = $request->phone_number;
            $userData = User::find($user->id);
            if ($request->has('name') && $request->filled('name')) {
                $userData->name = $name;
            }
            if ($request->has('last_name')) {
                $userData->last_name = $last_name;
            }
            if ($request->has('name') && $request->filled('email')) {
                $userData->email = $email;
            }
            $userData->save();
            if ($user->userDetails) { // Ensure the userDetails record exists
                if ($request->has('phone_number')) {
                    $user->userDetails()->update([
                        'phone_number' =>  $phone_number
                    ]);
                }
            }
            $data['details'] = User::select("id", "name", "last_name", 'profile_image', 'country_id', 'email', 'role_id')->with('userDetails')->find($user->id);
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('updateUserInfo  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
