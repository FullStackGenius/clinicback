<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Category;
use App\Models\Country;
use App\Models\HowLikeToWork;
use App\Models\ProjectDuration;
use App\Models\ProjectExperience;
use App\Models\ProjectScope;
use App\Models\Skill;
use App\Models\YourExperience;
use App\Models\YourGoal;
use App\Constants\ProjectConstants;
use App\Models\Language;
use App\Models\LanguageProficiency;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Proposal;
use App\Models\ResourceCategory;
use App\Models\ResourceData;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WebsitePageContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PageContentController extends BaseController
{
    public function getYourExperience()
    {
        try {
            $data['yourExperiences'] = YourExperience::select(['id', 'name', 'description', 'icon_image'])->where('status', ProjectConstants::STATUS_ACTIVE)->get()
                ->map(function ($item) {
                    $item->icon_image_path = asset('storage/your-experience/' . $item->icon_image);
                    return $item;
                });
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getYourExperience  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getYourGoal()
    {
        try {
            $data['yourGoals'] = YourGoal::select(['id', 'name', 'icon_image', 'description'])->where('status', ProjectConstants::STATUS_ACTIVE)->get()
                ->map(function ($item) {
                    $item->icon_image_path = asset('storage/your-goal/' . $item->icon_image);
                    return $item;
                });;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getYourGoal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getHowLikeToWork()
    {
        try {
            $data['howLikeToWorks'] = HowLikeToWork::select(['id', 'name', 'icon_image', 'description'])->where('status', ProjectConstants::STATUS_ACTIVE)->get()
                ->map(function ($item) {
                    $item->icon_image_path = asset('storage/how-to-like-work/' . $item->icon_image);
                    return $item;
                });
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getHowLikeToWork  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCountryData()
    {
        try {
            $data['countries'] = Country::where('status', ProjectConstants::STATUS_ACTIVE)->select(['id', 'name'])->get();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getCountryData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getCategoryData()
    {
        try {
            $data['categories'] = Category::select(['id', 'name', 'slug'])->where('category_status', ProjectConstants::STATUS_ACTIVE)->with('subCategories:id,name,category_id,slug')->get();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getCategoryData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLanguagePageData()
    {
        try {
            $data['language'] = Language::where('status', ProjectConstants::STATUS_ACTIVE)->select(['id', 'name',])->get();
            $data['LanguageProficiency'] = LanguageProficiency::select(['id', 'name',])->get();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getLanguagePageData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSkillData()
    {
        try {
            $data['skills'] = Skill::select(['id', 'name', 'skill_slug'])->where('status', ProjectConstants::STATUS_ACTIVE)->get();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getSkillData  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProjectScopeContent()
    {
        try {
            $data  = [

                'projectScope' => ProjectScope::select('id', 'name', 'description')->where('status', ProjectConstants::STATUS_ACTIVE)->get(),
                'projectDuration' => ProjectDuration::select('id', 'name', 'description')->where('status', ProjectConstants::STATUS_ACTIVE)->get(),
                'projectExperience' => ProjectExperience::select('id', 'name', 'description')->where('status', ProjectConstants::STATUS_ACTIVE)->get()

            ];
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getProjectScopeContent  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getFreelancer(Request $request)
    {

        try {
            Log::info('Request Params:', request()->all());
            $skills = $request->q; // Single input parameter
            $howLikeToWorkId = $request->job_type;
            if ($request->job_type == "part-time") {
                $howLikeToWorkId = 2;
            }
            if ($request->job_type == "full-time") {
                $howLikeToWorkId = 1;
            }
            $perPage = $request->get('per_page', 10); // Optional: Set a default of 10 items per page if not provided
            $sortOrder = $request->get('sort_by', 'newest');
            $data['freelancers'] = User::select('id', 'name', 'last_name', 'email', 'role_id', 'country_id', 'profile_image')
                ->with([
                    'userDetails' => function ($query) {
                        $query->select(
                            "your_experience_id",
                            "your_goal_id",
                            "user_id",
                            "about_yourself",
                            "street_address",
                            "state_provience",
                            "city",
                            "zip_postalcode",
                            "phone_number",
                            "apt_suite",
                            "date_of_birth",
                            "hourly_rate",
                            "services_rate",
                            "income_per_hour",
                            "created_at",
                            "updated_at",
                            "next_step",
                            "completed_steps",
                            DB::raw('LEFT(profile_headline, 30) as profile_headline')
                        );
                    },
                    'skills' => function ($query) {
                        $query->select('skills.id', 'skills.name', 'skills.skill_slug')->take(3);; // Select necessary skill fields
                    },
                    'subCategory' => function ($query) {
                        $query->select('sub_categories.id', 'sub_categories.name', 'sub_categories.slug', 'sub_categories.category_id')->take(3);; // Select necessary subcategory fields
                    },
                    'ratings' => function ($query) {
                        // Calculate the average rating and group by user_id
                        $query->selectRaw('rating_to, AVG(rating_number) as average_rating')
                            ->groupBy('rating_to');  // This is the critical part to avoid the SQL error
                    },
                    'getHowLikeToWork',
                    'subCategory.getCategory'
                ])
                ->where('user_status', ProjectConstants::STATUS_ACTIVE)
                ->where('role_id', ProjectConstants::ROLE_FREELANCE)
                ->when($skills, function ($query, $skills) {
                    $skillArray = explode(',', $skills); // Convert the skills string to an array

                    return $query->where(function ($query) use ($skillArray) {
                        // Filter by skills
                        $query->whereHas('skills', function ($query) use ($skillArray) {
                            $query->where(function ($q) use ($skillArray) {
                                foreach ($skillArray as $skill) {
                                    $q->orWhere('skill_slug', 'like', '%' . $skill . '%') // Match by skill slug
                                        ->orWhere('name', 'like', '%' . $skill . '%');      // Match by skill name
                                }
                            });
                        })
                            // Filter by subcategories
                            ->orWhereHas('subCategory', function ($query) use ($skillArray) {
                                $query->where(function ($q) use ($skillArray) {
                                    foreach ($skillArray as $subcat) {
                                        $q->orWhere('slug', 'like', '%' . $subcat . '%') // Match by subcategory slug
                                            ->orWhere('name', 'like', '%' . $subcat . '%'); // Match by subcategory name
                                    }
                                });
                            })
                            // Filter by categories
                            ->orWhereHas('subCategory.getCategory', function ($query) use ($skillArray) {
                                $query->where(function ($q) use ($skillArray) {
                                    foreach ($skillArray as $category) {
                                        $q->orWhere('slug', 'like', '%' . $category . '%') // Match by category slug
                                            ->orWhere('name', 'like', '%' . $category . '%'); // Match by category name
                                    }
                                });
                            })
                            // Filter by user name
                            ->orWhere(function ($query) use ($skillArray) {
                                foreach ($skillArray as $name) {
                                    $query->orWhere('name', 'like', '%' . $name . '%')       // Match by first name
                                        ->orWhere('last_name', 'like', '%' . $name . '%'); // Match by last name
                                }
                            });
                    });
                })
                ->when($howLikeToWorkId, function ($query, $howLikeToWorkId) {
                    return $query->whereHas('getHowLikeToWork', function ($query) use ($howLikeToWorkId) {
                        $query->where('how_like_to_work_id', $howLikeToWorkId);
                    });
                })
                ->when($sortOrder, function ($query, $sortOrder) {
                    if ($sortOrder === 'newest') {
                        $query->orderBy('id', 'desc');
                    } elseif ($sortOrder === 'oldest') {
                        $query->orderBy('id', 'asc');
                    } else {
                        $query->orderBy('id', 'asc');
                    }
                })
                ->withCount(['contracts as completed_jobs_count' => function ($query) {
                    $query->where('status', 'completed'); // Only count contracts with 'complete' status
                }])
                ->paginate($perPage); // Use paginate to get paginated results

            // Return paginated data
            return $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getFreelanceBySkill: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getHomePageData()
    {

        try {
            $data['category_subcategory_data_under_search_section'] =  Category::select(['id', 'name', 'slug'])->where('category_status', ProjectConstants::STATUS_ACTIVE)->with('subCategories:id,name,category_id,slug')->get();
            $data['testimonials_section'] = Testimonial::where('status', ProjectConstants::STATUS_ACTIVE)
                ->get();
            $data['real_accountants_section'] = User::with([
                'userDetails'
            ])->where('role_id', ProjectConstants::ROLE_FREELANCE)
                ->where('user_status', ProjectConstants::STATUS_ACTIVE)
                ->take(8)->orderBy('id','desc')->get();
            // $data['real_accountants_section'] = User::with([
            //     'userDetails',
            //     'ratings' => function ($query) {
            //         $query->selectRaw('user_id, AVG(rating_number) as average_rating')
            //             ->groupBy('user_id');  // This is the critical part to avoid the SQL error
            //     }
            // ])->where('role_id', ProjectConstants::ROLE_FREELANCE)
            //     ->where('user_status', ProjectConstants::STATUS_ACTIVE)
            //     ->take(8)->get();
            $data['get_help_today_section_skill'] = Skill::select(['id', 'name', 'skill_slug'])->where('status', ProjectConstants::STATUS_ACTIVE)->get();
            $data['account_section_first'] = WebsitePageContent::where('section_name', 'home-account-section')->first();
            $data['account_section_first'] = WebsitePageContent::where('section_name', 'home-account-section')->first();
            $data['flexible_section_section'] = WebsitePageContent::select('title', 'content', 'content_image')->where('section_name', 'flexible-account-section')->first();
            $data['contract_section_section'] = WebsitePageContent::select('title', 'content')->where('section_name', 'contract-section-data')->get();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getHomePageData: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getWebsiteSetting()
    {
        try {
            $setting = Setting::select('id', 'website_logo', 'facebook_link', 'instagram_link', 'twitter_link', 'linkedin_link')->find(1);
            if ($setting) {
                $setting->website_logo_path = asset('storage/website-logo/' . $setting->website_logo);
            }
            $data['settings'] = $setting;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getWebsiteSetting: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function getFreelanceBySkill(Request $request)
    {
        try {
            $skills = $request->q; // Single input parameter
            $perPage = $request->get('per_page', 10); // Optional: Set a default of 10 items per page if not provided

            $data['freelancers'] = User::select('id', 'name', 'last_name', 'email', 'role_id', 'country_id')
                ->with([
                    'userDetails',
                    'skills' => function ($query) {
                        $query->select('skills.id', 'skills.name'); // Select necessary skill fields
                    },
                    'subCategory' => function ($query) {
                        $query->select('sub_categories.id', 'sub_categories.name', 'sub_categories.slug'); // Select necessary subcategory fields
                    },
                    'ratings' => function ($query) {
                        // Calculate the average rating and group by user_id
                        $query->selectRaw('user_id, AVG(rating_number) as average_rating')
                            ->groupBy('user_id');  // This is the critical part to avoid the SQL error
                    },
                ])
                ->where('user_status', ProjectConstants::STATUS_ACTIVE)
                ->where('role_id', ProjectConstants::ROLE_FREELANCE)
                ->when($skills, function ($query, $skills) {
                    $skillArray = explode(',', $skills); // Convert the skills string to an array

                    return $query->where(function ($query) use ($skillArray) {
                        // Filter by skills
                        $query->whereHas('skills', function ($query) use ($skillArray) {
                            $query->where(function ($q) use ($skillArray) {
                                foreach ($skillArray as $skill) {
                                    $q->orWhere('skill_slug', 'like', '%' . $skill . '%') // Match by skill slug
                                        ->orWhere('name', 'like', '%' . $skill . '%');      // Match by skill name
                                }
                            });
                        })
                            // Filter by subcategories
                            ->orWhereHas('subCategory', function ($query) use ($skillArray) {
                                $query->where(function ($q) use ($skillArray) {
                                    foreach ($skillArray as $subcat) {
                                        $q->orWhere('slug', 'like', '%' . $subcat . '%') // Match by subcategory slug
                                            ->orWhere('name', 'like', '%' . $subcat . '%'); // Match by subcategory name
                                    }
                                });
                            })
                            // Filter by categories
                            ->orWhereHas('subCategory.getCategory', function ($query) use ($skillArray) {
                                $query->where(function ($q) use ($skillArray) {
                                    foreach ($skillArray as $category) {
                                        $q->orWhere('slug', 'like', '%' . $category . '%') // Match by category slug
                                            ->orWhere('name', 'like', '%' . $category . '%'); // Match by category name
                                    }
                                });
                            })
                            // Filter by user name
                            ->orWhere(function ($query) use ($skillArray) {
                                foreach ($skillArray as $name) {
                                    $query->orWhere('name', 'like', '%' . $name . '%')       // Match by first name
                                        ->orWhere('last_name', 'like', '%' . $name . '%'); // Match by last name
                                }
                            });
                    });
                })
                // ->withCount(['contracts as completed_jobs_count' => function ($query) {
                //     $query->where('status', 'complete'); // Only count contracts with 'complete' status
                // }])
                ->paginate($perPage); // Use paginate to get paginated results

            // Return paginated data
            return $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getFreelanceBySkill: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }





    public function freelancerDetail($id)
    {
        try {
            $freelancer = User::with(['ratings' => function ($query) {
                $query->select('user_id', DB::raw('AVG(rating_number) as overall_rating'))
                    ->groupBy('user_id');
            }])->where('role_id', ProjectConstants::ROLE_FREELANCE)->where('id', $id)->first();
            // $skills = $request->skill; 
            // $data['freelancers'] = User::select('id', 'name', 'email')->with([
            //     'userDetails',
            //     'skills' => function ($query) {
            //         $query->select('skills.id', 'skills.name'); // 'user_id' is the foreign key
            //     }
            // ])
            // ->where('user_status',ProjectConstants::STATUS_ACTIVE)
            // ->where('role_id',ProjectConstants::ROLE_FREELANCE)
            // ->when($skills, function ($query, $skills) {
            //     return $query->whereHas('skills', function ($query) use ($skills) {
            //         // Adjust the condition if you want an exact match or a partial search.
            //         return $query->whereIn('name', explode(',', $skills)); // Assuming skills are stored in a 'skill_name' field
            //     });
            // })
            // ->get();
            $data['freelancer'] = $freelancer;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in freelancerDetail: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getUserStepDataProfile(Request $request)
    {

        try {
            //$user = Auth::user();
            $request->validate([
                'user_id' => 'required',
                'name' => 'required',
            ]);
            $user = User::find($request->user_id);
            if (empty($user)) {
                throw new \Exception('something went wrong');
            }
            $action = $request->name;
            switch ($action) {
                case 'step1':

                    $details = $this->getDataWithStepProfile('step1', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step2':
                    $details =  $this->getDataWithStepProfile('step2', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step3':
                    $details =  $this->getDataWithStepProfile('step3', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step4':
                    $details = $this->getDataWithStepProfile('step4', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step5':
                    $details =   $this->getDataWithStepProfile('step5', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step6':
                    $details =   $this->getDataWithStepProfile('step6', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step7':
                    $details =  $this->getDataWithStepProfile('step7', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step8':
                    $details =  $this->getDataWithStepProfile('step8', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;
                case 'step9':
                    $details =  $this->getDataWithStepProfile('step9', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step10':
                    $details =  $this->getDataWithStepProfile('step10', $user);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                default:

                    $details['step1'] =  $this->getDataWithStepProfile('step1', $user);
                    $details['step2'] =  $this->getDataWithStepProfile('step2', $user);
                    $details['step3'] =  $this->getDataWithStepProfile('step3', $user);
                    $details['step4'] =  $this->getDataWithStepProfile('step4', $user);
                    $details['step5'] =  $this->getDataWithStepProfile('step5', $user);
                    $details['step6'] =  $this->getDataWithStepProfile('step6', $user);
                    $details['step7'] =  $this->getDataWithStepProfile('step7', $user);
                    $details['step8'] =  $this->getDataWithStepProfile('step8', $user);
                    $details['step9'] =  $this->getDataWithStepProfile('step9', $user);
                    $details['step10'] =  $this->getDataWithStepProfile('step10', $user);
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


    public function getDataWithStepProfile($stepName, $user)
    {
        if ($stepName == 'step1') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')
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
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')->with('subCategory:id,name,category_id')
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }
        if ($stepName == 'step3') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')->with('skills:id,name')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step4') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')
                ->withAvg('ratings', 'rating_number')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'profile_headline');
                }])
                ->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step5') {
            $userData = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')->with('userExperiences')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step6') {
            $userData = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')->with('userEducation')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step7') {
            $data['language'] = User::select("id", "name", 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')->with('userLanguage')
                ->withAvg('ratings', 'rating_number')->find($user->id);
            return $details =  $data;
        }

        if ($stepName == 'step8') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'about_yourself');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }

        if ($stepName == 'step9') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id','star_rating')
                ->with(['userDetails' => function ($query) {
                    $query->select('id', 'user_id', 'hourly_rate', 'services_rate', 'income_per_hour');
                }])
                ->withAvg('ratings', 'rating_number')
                ->find($user->id);
            return $details =  $userData;
        }


        if ($stepName == 'step10') {
            $userData = User::select('id', 'name', 'last_name', 'profile_image', 'country_id', 'role_id' ,'star_rating')
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


    public function getProjectDesiredAccount()
    {
        try {

            $data['accounting_sectors'] = Category::select(['id', 'name', 'slug'])->where('category_status', ProjectConstants::STATUS_ACTIVE)->get();
            $data['accounting_skills'] = SubCategory::select(['id', 'name', 'slug'])->where('subcategory_status', ProjectConstants::STATUS_ACTIVE)->get();
            $data['accounting_certifications'] = Skill::select(['id', 'name', 'skill_slug'])->where('status', ProjectConstants::STATUS_ACTIVE)->get();

            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getProjectDesiredAccount  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allProjetList(Request $request)
    {
        try {

            $projects = Project::with(['clientUser', 'projectSkill', 'projectCategory', 'projectSubCategory', 'projectScope', 'projectDuration', 'projectExperience', 'projectProposal' => function ($query) {
                $query->select('id', 'project_id', 'freelancer_id'); // Include project_id to maintain relationship
            }])->where('project_status', 3)->orderBy('id', 'desc')->paginate($request->per_page);
            // $projects = $projects->map(function ($project) {
            //     $project->accounting_certifications = $project->projectSkill;
            //     $project->accounting_sectors = $project->projectCategory;
            //     $project->accounting_skills = $project->projectSubCategory;
            //     unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            //     return $project;
            // });
            $data['projects'] = $projects;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProjectType()
    {
        try {

            $data['project_type'] =   ProjectType::select('id', 'name', 'description')->where('status', ProjectConstants::STATUS_ACTIVE)->get();
            return  $this->sendCommonResponse('false', "", $data, 'data fetch successfully', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getProjectType  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getLearnHowToHirePageContent()
    {

        try {

            $data['learn_how_to_hire'] = WebsitePageContent::select('content_image', 'content')->where('section_name', 'learn-how-to-hire')->first();
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getLearnHowToHirePageContent: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFreelancerProjectProposalData(Request $request)
    {

        try {
            $freelanceId = $request->freelancer_id;
            $proposals =  Proposal::select(['project_id', 'freelancer_id'])->where('freelancer_id', $freelanceId)->get();
            $data['proposals'] = $proposals;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getFreelancerProjectProposal  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getResources(Request $request)
    {

        try {

            // $search = $request->q;
            // $data['resource'] = ResourceData::with('resourceCategory')->paginate($request->per_page);
            $search = $request->q;
            $category = $request->category;
            $sortBy = $request->sort_by; // Fixed variable name

            $query = ResourceData::with('resourceCategory');

            // Search logic (only if $search has value)
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhereHas('resourceCategory', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                });
            }
            // Filter by category if provided
            if (!empty($category)) {
                $query->where('resource_category_id', $category);
            }

            // Sorting (e.g., by title, created_at, etc.)
            if (!empty($sortBy)) {
                $query->orderBy('id', $sortBy); // Change 'asc' to 'desc' if needed
            }
            $data['resource'] = $query->paginate($request->per_page);
            $data['category'] =  ResourceCategory::where('status', ProjectConstants::STATUS_ACTIVE)->get();
            return $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getFreelanceBySkill: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getResourcesDetail(Request $request)
    {

        try {
            $resourceId = $request->id;
            $resourceData = ResourceData::with('resourceCategory')->find($resourceId);
            $randomResources = ResourceData::with('resourceCategory')
                ->where('id', '!=', $resourceId) // Exclude current resource
                ->inRandomOrder() // Randomize the order
                ->limit(3) // Get only 3 records
                ->get();

            $data['resource'] = (!empty($resourceData)) ? $resourceData : [];
            $data['resrandomResourcesource'] = (!empty($randomResources)) ? $randomResources : [];
            return $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error in getFreelanceBySkill: ' . $e->getMessage());
            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'Something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
