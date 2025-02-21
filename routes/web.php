<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\HowLikeToWorkController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WebsitePageContentController;
use App\Http\Controllers\YourExperienceController;
use App\Http\Controllers\YourGoalController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect('/login');
});
Route::get('/test', function () {
    $id = 12345;
    $encoded = encodeId($id);
    $decoded = decodeId($encoded);
    
    echo "Encoded: $encoded, Decoded: $decoded";
});
Route::get('/error', function () {
    return view('errors.custom');
})->name('error.page');
Route::get('/read-log', [LogController::class, 'readLog'])
    ->middleware('auth')
    ->name('read.log');
Route::get('/api-routes', [LogController::class, 'showApiRoutes'])->middleware('auth')->name('api-routes');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/update-profile-image', [ProfileController::class, 'updateProfileImage'])->name('update-profile-image');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('skill', SkillController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubCategoryController::class);
    Route::resource('your-experience', YourExperienceController::class);
    Route::resource('your-goal', YourGoalController::class);
    Route::resource('how-to-like-work', HowLikeToWorkController::class);
    Route::resource('freelancer', FreelancerController::class);
    Route::get('change-status/{id}', [FreelancerController::class, 'changeStatus'])->name('change-status');
    Route::resource('settings', SettingController::class);
    Route::resource('language', LanguageController::class);
    Route::resource('country', CountryController::class);

    Route::resource('client', ClientController::class);
    Route::prefix('home')->group(function () {
        Route::resource('testimonials', TestimonialController::class);
        Route::get('account-section', [WebsitePageContentController::class, 'accountSectionContent'])->name('account-section');
        Route::post('account-section', [WebsitePageContentController::class, 'accountSectionContentStore'])->name('account-section.store');
        Route::get('flexible-section', [WebsitePageContentController::class, 'flexibleSectionContent'])->name('flexible-section');
        Route::post('flexible-section', [WebsitePageContentController::class, 'flexibleSectionContentStore'])->name('flexible-section.store');
        Route::get('learn-how-to-hire', [WebsitePageContentController::class, 'learnHowToHireContent'])->name('learn-how-to-hire');
        Route::post('learn-how-to-hire', [WebsitePageContentController::class, 'learnHowToHireContentStore'])->name('learn-how-to-hire.store');
        Route::get('contract-section', [WebsitePageContentController::class, 'contractSectionContent'])->name('contract-section');
        Route::get('contract-section/{id}/edit', [WebsitePageContentController::class, 'contractSectionContentEdit'])->name('contract-section.edit');
        Route::put('contract-section/{id}', [WebsitePageContentController::class, 'contractSectionContentUpdate'])->name('contract-section.update');
    });

    Route::prefix('jobs')->group(function () {
        Route::get('/{userId?}', [JobController::class, 'index'])->name('jobs.index');
        Route::get('show/{id}', [JobController::class, 'show'])->name('jobs.show');
        Route::post('assign-job', [JobController::class, 'assignJobToFreeklancer'])->name('jobs.assign-job');
        Route::post('contract-detail-ajax', [JobController::class, 'getContractDetailAjax'])->name('jobs.contract-detail-ajax');
    });
    Route::get('job-proposal/{jobId}', [JobController::class, 'jobProposal'])->name('job-proposal');
    Route::get('/test-mail', function () {
        Mail::raw('This is a test email!', function ($message) {
            $message->to('arun@theobsidians.com') // Replace with your email address
                ->subject('Test Email');
        });

        return "Test email sent successfully!";
    });
});


require __DIR__ . '/auth.php';
