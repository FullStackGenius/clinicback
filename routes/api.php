<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ForgotpasswordController;
use App\Http\Controllers\Api\FreelancerController;
use App\Http\Controllers\Api\JobContractController;
use App\Http\Controllers\Api\PageContentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RatingController;

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');

    // ProfileController controoller
    Route::post('save-freelance-exp', [ProfileController::class, 'saveFreelanceExp'])->name('save-freelance-exp');
    Route::post('save-freelance-goal', [ProfileController::class, 'saveFreelanceGoal'])->name('save-freelance-goal');
    Route::post('save-like-to-work', [ProfileController::class, 'saveHowLikeToWork'])->name('save-like-to-work');
    Route::post('save-user-subcategory', [ProfileController::class, 'saveUserSubCategory'])->name('save-user-subcategory');
    Route::post('save-user-skill', [ProfileController::class, 'saveUserSkill'])->name('save-user-skill');
    Route::post('save-profile-image', [ProfileController::class, 'saveProfileImage'])->name('save-profile-image');
    Route::post('save-profile-headline', [ProfileController::class, 'saveProfileHeadline'])->name('save-profile-headline');
    Route::post('save-about-yourself', [ProfileController::class, 'saveAboutYourSelf'])->name('save-about-yourself');
    Route::post('save-freelance-rate', [ProfileController::class, 'saveFreelanceRate'])->name('save-freelance-rate');
    Route::post('save-profile-detail', [ProfileController::class, 'saveProfileDetail'])->name('save-profile-detail');
    Route::post('save-user-experience', [ProfileController::class, 'saveUserExperience'])->name('save-user-experience');
    Route::post('save-language', [ProfileController::class, 'saveLanguage'])->name('save-language');
    Route::post('save-user-education', [ProfileController::class, 'saveUserEducation'])->name('save-user-education');
    Route::post('save-user-role', [ProfileController::class, 'saveUserRole'])->name('save-user-role');
    Route::post('save-freelancer-resume', [ProfileController::class, 'saveFreelancerResume'])->name('save-freelancer-resume');
    Route::post('save-zero-step', [ProfileController::class, 'saveZeroStep'])->name('save-zero-step');
    Route::post('save-four-step', [ProfileController::class, 'saveFourStep'])->name('save-four-step');
    Route::get('get-user-experience', [ProfileController::class, 'getUserExperience'])->name('get-user-experience');
    Route::get('get-user-education', [ProfileController::class, 'getUserEducation'])->name('get-user-education');
    Route::post('get-user-step-data', [ProfileController::class, 'getUserStepData'])->name('get-user-step-data');
    Route::post('delete-freelancer-step-data', [ProfileController::class, 'deleteFreelancerStepData'])->name('delete-freelancer-step-data');
    Route::post('get-single-freelance-experience-education', [ProfileController::class, 'getSingleUserExperienceEducation'])
    ->name('get-single-freelance-experience-education');
    Route::get('get-loggedin-user-info', [ProfileController::class, 'getLoggedInUserInfo'])->name('get-loggedin-user-info');
    Route::post('update-user-info', [ProfileController::class, 'updateUserInfo'])->name('update-user-info');
    
    //ProfileController
    Route::prefix('project')->group(function () {
        Route::post('save-project-title', [ProjectController::class, 'saveProjectTitle'])->name('save-project-title');
        Route::post('save-project-skill', [ProjectController::class, 'saveProjectSkill'])->name('save-project-skill');
        Route::post('save-project-desc', [ProjectController::class, 'saveProjectDesc'])->name('save-project-desc');
        Route::post('save-project-budget', [ProjectController::class, 'saveProjectBudget'])->name('save-project-budget');
        Route::post('save-project-work-scope', [ProjectController::class, 'saveProjectWorkScope'])->name('save-project-work-scope');
        Route::post('get-project-detail', [ProjectController::class, 'getProjectDetail'])->name('get-project-detail');
       
        Route::post('save-project-type', [ProjectController::class, 'saveProjectType'])->name('save-project-type');
        Route::post('edit-project-details', [ProjectController::class, 'editProjectDetails'])->name('edit-project-details');
         Route::get('client-project', [ProjectController::class, 'clientProjectList'])->name('client-project');
         Route::post('get-project-step-form-data', [ProjectController::class, 'getProjectStepFormData'])->name('get-project-step-form-data');
    });
    Route::prefix('jobs')->group(function () {
        Route::get('/', [JobContractController::class, 'getJobs'])->name('/');
        Route::post('response-to-proposal', [JobContractController::class, 'responseToProposal'])->name('response-to-proposal');

    });

    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    Route::Post('send-project-proposal',[FreelancerController::class,'sendProjectProposal'])->name('send-project-proposal');
    Route::get('get-freelancer-project-proposal',[FreelancerController::class,'getFreelancerProjectProposal'])->name('get-freelancer-project-proposal');
    Route::get('get-freelancer-contract',[FreelancerController::class,'getFreelancerContract'])->name('get-freelancer-contract');

    Route::post('give-rating',[RatingController::class,'giveRating'])->name('give-rating');


    Route::prefix('chat')->group(function () {
        Route::get('start/{userId?}', [ChatController::class, 'startChat'])->name('chat.start');
        Route::post('send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    });


});

Route::get('account-type', [AuthenticationController::class, 'accountType'])->name('account-type');
Route::get('resend-email-verification-link/{token}', [AuthenticationController::class, 'resendEmailVerificationLink'])->name('resend-email-verification-link');

Route::get('get-your-experience', [PageContentController::class, 'getYourExperience'])->name('get-your-experience');
Route::get('get-your-goal', [PageContentController::class, 'getYourGoal'])->name('get-your-goal');
Route::get('get-how-like-to-work', [PageContentController::class, 'getHowLikeToWork'])->name('get-how-like-to-work');
Route::get('get-country', [PageContentController::class, 'getCountryData'])->name('get-country');
Route::get('get-category', [PageContentController::class, 'getCategoryData'])->name('get-category');
Route::get('get-skills', [PageContentController::class, 'getSkillData'])->name('get-skills');
Route::get('get-scope-project-content', [PageContentController::class, 'getProjectScopeContent'])->name('get-scope-project-content');
Route::get('get-freelancers', [PageContentController::class, 'getFreelancer'])->name('get-freelancers');
Route::get('get-home-page-data', [PageContentController::class, 'getHomePageData'])->name('get-home-page-data');
Route::get('get-website-setting', [PageContentController::class, 'getWebsiteSetting'])->name('get-website-setting');
Route::get('get-freelance-by-skill', [PageContentController::class, 'getFreelanceBySkill'])->name('get-freelance-by-skill');
Route::get('freelancer-details/{id}', [PageContentController::class, 'freelancerDetail'])->name('freelancer-details');
Route::post('get-user-step-data-profile', [PageContentController::class, 'getUserStepDataProfile'])->name('get-user-step-data-profile');
Route::get('get-project-desired-account', [PageContentController::class, 'getProjectDesiredAccount'])->name('get-project-desired-account');
Route::get('get-language-page-data', [PageContentController::class, 'getLanguagePageData'])->name('get-language-page-data');
Route::get('all-project-list', [PageContentController::class, 'allProjetList'])->name('all-project-list');
Route::get('get-project-type', [PageContentController::class, 'getProjectType'])->name('get-project-type');
Route::get('get-learn-how-to-hire-page-data', [PageContentController::class, 'getLearnHowToHirePageContent'])->name('get-learn-how-to-hire-page-data');



//ForgotpasswordController
Route::post('send-forgot-password-link', [ForgotpasswordController::class, 'sendForgotPassword'])->name('send-forgot-password-link');
Route::post('change-forgot-password', [ForgotpasswordController::class, 'changeForgotPassword'])->name('change-forgot-password');
