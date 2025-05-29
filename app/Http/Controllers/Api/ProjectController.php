<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Models\Project;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\JobPostSuccessMail;
use App\Mail\ProposalRejectionMail;
use App\Models\ProjectType;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;

class ProjectController extends BaseController
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    // public function getProjectType(){
    //     try {
    //         $client = Auth::user();
    //         $data['project_type'] =   ProjectType::select('id','name','description')->where('status',ProjectConstants::STATUS_ACTIVE)->get();
    //         // $project = Project::with([
    //         //     'projectScope' => function ($query) {
    //         //         $query->select('id', 'name');
    //         //     },
    //         //     'projectDuration' => function ($query) {
    //         //         $query->select('id', 'name');
    //         //     },
    //         //     'projectExperience' => function ($query) {
    //         //         $query->select('id', 'name');
    //         //     },
    //         //     'projectSkill:name,id'
    //         // ])->where('user_id',$client->id)->get();
    //         // $data['project'] = $project;

    //         return  $this->sendCommonResponse('false',"",$data,'data fetch successfully',"",ProjectConstants::HTTP_OK);
    //     }catch (\Exception $e) {
    //         Log::channel('daily')->info('getProjectType  (project Api log) \n: ' . $e->getMessage());
    //         return $this->sendCommonResponse(true,'error','','something went wrong','',ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function saveProjectType(Request $request)
    {
        try {
            $request->validate([
                'project_type' => 'required|integer'
            ]);
            $client = Auth::user();
            $project  = Project::create([
                'project_type_id' => $request->project_type,
                'next_step' => 2,
                'project_status' => 2,
                'completed_steps' => 1,
                'user_id' => $client->id
            ]);
            $data['poject'] = Project::find($project->id);
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectType  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function saveProjectTitle(Request $request)
    {

        try {
            $request->validate([
                'project_id' => 'required|integer',
                'title' => 'required|unique:projects,title,' . $request->project_id,

            ]);
            $client = Auth::user();
            $project = Project::where('user_id', $client->id)->find($request->project_id);
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            $project->title =  $request->title;
            $project->next_step = 3;
            $project->completed_steps = 2;
            $project->save();
            $data['poject'] = Project::find($project->id);
            // $data['poject'] = Project::create([
            //     'title' => 
            // ]);
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectTitle  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveProjectSkill(Request $request)
    {

        try {
            $request->validate([
                'accounting_certifications' => 'nullable|array',
                'accounting_certifications*' => 'integer',
                'accounting_skills' => 'nullable|array',
                'accounting_skills*' => 'integer',
                'accounting_sectors' => 'nullable|array',
                'accounting_sectors*' => 'integer',
                'project_id' => 'required|integer'

            ]);
            $client = Auth::user();
            $project = Project::where('user_id', $client->id)->find($request->project_id);
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            $project->next_step = 5;
            $project->completed_steps = 4;
            $project->save();
            //  if(!empty($request->accounting_certifications)){
            $project->projectSkill()->sync($request->accounting_certifications);
            //  }
            // if(!empty($request->accounting_sectors)){
            $project->projectCategory()->sync($request->accounting_sectors);
            // }
            // if(!empty($request->accounting_skills)){
            $project->projectSubCategory()->sync($request->accounting_skills);
            // }
            $project = Project::with('projectSkill:id,name')->with('projectCategory:id,name')->with('projectSubCategory:id,name')->find($project->id);
            if ($project) {
                $project->accounting_certifications = $project->projectSkill;
                $project->accounting_sectors = $project->projectCategory;
                $project->accounting_skills = $project->projectSubCategory;
                unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            }
            $data['project'] = $project;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectSkill  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveProjectDesc(Request $request)
    {

        try {
            $request->validate([
                'description' => 'required',
                'project_id' => 'required|integer'

            ]);
            $client = Auth::user();
            $project = Project::where('user_id', $client->id)->find($request->project_id);
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            if ($project) {
                $project->description =  $request->description;
                $project->next_step = 4;
                $project->completed_steps = 3;
                $project->save();
            }

            $data['project'] = $project;

            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectDesc  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function saveProjectBudget(Request $request)
    {

        try {

            $request->validate([
                'project_id' => 'required|integer',
                //'budget_type' =>  'required|in:1,2',
                //  'budget_type' =>  'required|in:1,2',
                // 'hourly_from' => 'required_if:budget_type,1',
                // 'hourly_to' => 'required_if:budget_type,1',
                'fixed_rate' => 'required|integer|min:1',
                'hourly_from' => 'nullable|integer|min:1',
                'hourly_to'   => 'nullable|integer|min:1',
                //'fixed_rate' => 'required_if:budget_type,2',
            ], [
                'project_id.required' => 'The project ID is required.',
                'project_id.integer' => 'The project ID must be an integer.',
                'hourly_from.integer' => 'The hourly "from" value must be an integer.',
                'hourly_from.min'     => 'The hourly "from" value must be greater than zero.',
                'hourly_to.integer'   => 'The hourly "to" value must be an integer.',
                'hourly_to.min'       => 'The hourly "to" value must be greater than zero.',
                'fixed_rate.required' => 'The fixed rate is required.',
                'fixed_rate.integer' => 'The fixed rate must be an integer.',
                'fixed_rate.min' => 'The fixed rate must be greater than zero.',
                // 'budget_type.required' => 'The budget type is required.',
                // 'budget_type.in' => 'The budget type must be hourly or fixed.',
                // 'hourly_from.required_if' => 'The hourly "from" value is required when the budget  is hourly.',
                // 'hourly_to.required_if' => 'The hourly "to" value is required when the budget  is hourly.',
                // 'fixed_rate.required_if' => 'The fixed rate is required when the budget  is fixed.',
            ]);
            $client = Auth::user();
            $project = Project::where('user_id', $client->id)->where('id', $request->project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            if (!empty($project)) {
                $project->budget_type = $request->budget_type;
                // $project->hourly_from = ($request->budget_type == 1) ? $request->hourly_from : null;
                // $project->hourly_to = ($request->budget_type == 1) ? $request->hourly_to : null;
                // $project->fixed_rate = ($request->budget_type == 2) ? $request->fixed_rate : null;
                $project->hourly_from = $request->hourly_from;
                $project->hourly_to = $request->hourly_to;
                $project->fixed_rate = $request->fixed_rate;
                $project->next_step = 6;
                $project->completed_steps = 5;
                $project->save();
            }

            $data['project'] = $project;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectBudget  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function saveProjectWorkScope(Request $request)
    {

        try {

            $request->validate([
                'project_id' => 'required|integer',
                'project_scope' => 'required|integer',
                'project_duration' => 'required|integer',
                'project_experience' => 'required|integer',
            ]);
            $client = Auth::user();
            $project = Project::where('user_id', $client->id)->find($request->project_id);
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            if ($project) {
                $project->project_scope_id  = $request->project_scope;
                $project->project_duration_id = $request->project_duration;
                $project->project_experience_id = $request->project_experience;
                $project->next_step = 7;
                $project->completed_steps = 6;
                $project->save();
            }
            $project = Project::with(['projectScope', 'projectDuration', 'projectExperience'])->where('user_id', $client->id)->find($project->id);
            $data['project'] = $project;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('saveProjectWorkScope  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getProjectDetail(Request $request)
    {

        try {
            $request->validate([
                'project_id' => 'required|integer',
            ]);
            $client = Auth::user();
            $project = Project::with([
                'clientUser',
                'projectScope' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectDuration' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectExperience' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectSkill',
                'projectCategory',
                'projectSubCategory'
            ])->where('user_id', $client->id)->find($request->project_id);
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            if ($project) {
                $project->accounting_certifications = $project->projectSkill;
                $project->accounting_sectors = $project->projectCategory;
                $project->accounting_skills = $project->projectSubCategory;
                unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            }
            $data['project'] = $project;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getProjectDetail  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function editProjectDetails(Request $request)
    {

        try {
            $request->validate([
                'project_id' => 'required|integer',
                'edit_type' => 'required|in:title,description,accountingCertifications,accountingSectors,accountingSkills,projectScope,projectDuration,projectExperience,projectStatus',
                'title' => 'required_if:edit_type,title',
                'description' => 'required_if:edit_type,description',
                'accounting_certifications' => 'required_if:edit_type,accountingCertifications|array',
                'accounting_sectors' => 'required_if:edit_type,accountingSectors|array',
                'accounting_skills' => 'required_if:edit_type,accountingSkills|array',
                'project_scope' => 'required_if:edit_type,projectScope|integer',
                'project_duration' => 'required_if:edit_type,projectDuration|integer',
                'project_experience' => 'required_if:edit_type,projectExperience|integer',
                'project_status' => 'required_if:edit_type,projectStatus|integer',
            ]);
            $client = Auth::user();
            $projectId = $request->project_id;
            $project = Project::where('id', $projectId)->where('user_id', $client->id)->first();

            if (empty($project)) {
                throw new \Exception('something went wrong');
            }

            if ($request->edit_type == 'title') {
                $project->update([
                    'title' => $request->title, // Example data
                ]);
            }

            if ($request->edit_type == 'description') {
                $project->update([
                    'description' => $request->description, // Example data
                ]);
            }

            if ($request->edit_type == 'projectScope') {
                $project->update([
                    'project_scope_id' => $request->project_scope, // Example data
                ]);
            }

            if ($request->edit_type == 'projectDuration') {
                $project->update([
                    'project_duration_id' => $request->project_duration, // Example data
                ]);
            }

            if ($request->edit_type == 'projectExperience') {
                $project->update([
                    'project_experience_id' => $request->project_experience, // Example data
                ]);
            }

            if ($request->edit_type == 'projectStatus') {
                $project->update([
                    'project_status' => $request->project_status, // Example data
                ]);
                $projectData =  Project::with('clientUser')->find($project->id);
                if ($request->project_status == 3) {
                    $this->mailService->safeSend($projectData->clientUser->email, new JobPostSuccessMail($projectData), 'editProjectDetails status');
                    //  Mail::to($projectData->clientUser->email)->send(new JobPostSuccessMail($projectData));
                }
            }

            if ($request->edit_type == 'accountingCertifications') {
                //dd($request->accounting_certifications);
                $project->projectSkill()->sync($request->accounting_certifications);
            }

            if ($request->edit_type == 'accountingSectors') {
                $project->projectCategory()->sync($request->accounting_sectors);
            }

            if ($request->edit_type == 'accountingSkills') {
                $project->projectSubCategory()->sync($request->accounting_skills);
            }
            $project = Project::with(['clientUser', 'projectSkill', 'projectCategory', 'projectSubCategory', 'projectScope', 'projectDuration', 'projectExperience'])->find($projectId);
            if ($project) {
                $project->accounting_certifications = $project->projectSkill;
                $project->accounting_sectors = $project->projectCategory;
                $project->accounting_skills = $project->projectSubCategory;
                unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            }
            $data['project'] = $project;
            return  $this->sendCommonResponse('false', "", $data, 'data save successfully', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('editProjectDetails  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function clientProjectList(Request $request)
    {
        try {
            $user = Auth::user();
            $status = $request->get_status;
            if ($status == "all" || $status == "") {
                $projects = Project::with(['clientUser', 'projectSkill', 'projectCategory', 'projectSubCategory', 'projectScope', 'projectDuration', 'projectExperience'])->where('user_id', $user->id)->orderBy('id', 'desc')->paginate($request->per_page);
            } else {
                $projects = Project::with(['clientUser', 'projectSkill', 'projectCategory', 'projectSubCategory', 'projectScope', 'projectDuration', 'projectExperience'])->where('user_id', $user->id)->orderBy('id', 'desc')->where('project_status', $status)->paginate($request->per_page);
            }


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


    public function getProjectStepFormData(Request $request)
    {

        try {
            $user = Auth::user();
            $action = $request->name;
            $project_id = $request->project_id;
            switch ($action) {
                case 'step1':

                    $details = $this->getProjectDataWithStep('step1', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step2':
                    $details =  $this->getProjectDataWithStep('step2', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step3':
                    $details =  $this->getProjectDataWithStep('step3', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step4':
                    $details = $this->getProjectDataWithStep('step4', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step5':
                    $details =   $this->getProjectDataWithStep('step5', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step6':
                    $details =   $this->getProjectDataWithStep('step6', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                case 'step7':
                    $details =  $this->getProjectDataWithStep('step7', $user, $project_id);
                    $data['details'] =  $details;
                    return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
                    break;

                default:

                    $details['step1'] =  $this->getProjectDataWithStep('step1', $user, $project_id);
                    $details['step2'] =  $this->getProjectDataWithStep('step2', $user, $project_id);
                    $details['step3'] =  $this->getProjectDataWithStep('step3', $user, $project_id);
                    $details['step4'] =  $this->getProjectDataWithStep('step4', $user, $project_id);
                    $details['step5'] =  $this->getProjectDataWithStep('step5', $user, $project_id);
                    $details['step6'] =  $this->getProjectDataWithStep('step6', $user, $project_id);
                    $details['step7'] =  $this->getProjectDataWithStep('step7', $user, $project_id);
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




    public function getProjectDataWithStep($stepName, $user, $project_id)
    {
        if ($stepName == 'step1') {
            $project = Project::select('id', 'project_type_id', 'budget_type', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }
        if ($stepName == 'step2') {
            $project = Project::select('id', 'project_type_id', 'title', 'budget_type', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }

        if ($stepName == 'step3') {
            $project = Project::select('id', 'project_type_id', 'description', 'budget_type', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }
        if ($stepName == 'step4') {
            $project = Project::with(['projectSkill', 'projectCategory', 'projectSubCategory'])->select('id', 'project_type_id', 'budget_type', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if ($project) {
                $project->accounting_certifications = $project->projectSkill;
                $project->accounting_sectors = $project->projectCategory;
                $project->accounting_skills = $project->projectSubCategory;
                unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            }
            return $details =  $project;
        }

        if ($stepName == 'step5') {
            $project = Project::select('id', 'budget_type', 'hourly_from', 'hourly_to', 'fixed_rate', 'project_type_id', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }


        if ($stepName == 'step6') {
            $project = Project::with(['projectScope', 'projectDuration', 'projectExperience'])->select('id', 'project_scope_id', 'project_type_id', 'project_duration_id', 'project_experience_id', 'budget_type', 'project_status', 'next_step', 'completed_steps')
                ->where('user_id', $user->id)->where('id', $project_id)->first();
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }

        if ($stepName == 'step7') {
            $project = Project::with([
                'clientUser',
                'projectScope' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectDuration' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectExperience' => function ($query) {
                    $query->select('id', 'name');
                },
                'projectSkill:name,id'
            ])->where('user_id', $user->id)->find($project_id);
            if ($project) {
                $project->accounting_certifications = $project->projectSkill;
                $project->accounting_sectors = $project->projectCategory;
                $project->accounting_skills = $project->projectSubCategory;
                unset($project->projectSkill, $project->projectCategory, $project->projectSubCategory);
            }
            if (empty($project)) {
                throw new \Exception('something went wrong');
            }
            return $details =  $project;
        }
    }

    public function projectProposal(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $projectId =  $request->project_id;
            $status = $request->get_status;
            if ($status == "all" || $status == "") {
                $proposal = Proposal::with(['freelancerUser', 'project'])
                    ->whereHas('project', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->where('project_id', $projectId)
                    ->orderBy('id', 'desc')
                    ->paginate($request->per_page);
            } else {
                $proposal = Proposal::with(['freelancerUser', 'project'])
                    ->whereHas('project', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->where('project_id', $projectId)
                    ->where('status', $status)
                    ->orderBy('id', 'desc')
                    ->paginate($request->per_page);
            }


            $data['proposal'] = $proposal;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rejectProjectProposal(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $proposalId =  $request->proposal_id;
            $projectId = $request->project_id;
            Proposal::where('id', $proposalId)
                ->where('project_id', $projectId)
                ->update(['status' => 'rejected']);
            $proposal = Proposal::with(['freelancerUser', 'project'])->find($proposalId);
            $this->mailService->safeSend($proposal->freelancerUser->email, new ProposalRejectionMail($proposal->freelancerUser->name, $proposal->project->title), 'rejectProjectProposal mail');
            //Mail::to($proposal->freelancerUser->email)->send(new ProposalRejectionMail($proposal->freelancerUser->name,$proposal->project->title));
            $data['proposal'] = $proposal;
            return  $this->sendCommonResponse('false', "", $data, '', "", ProjectConstants::HTTP_OK);
        } catch (\Exception $e) {
            return $this->sendCommonResponse(true, 'error', '',  $e->getMessage(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteYourJobProject(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'project_id' => 'required|integer'
            ]);

            $client = Auth::user();
            $project  = Project::whereIn('project_status', [1, 2, 3])->where('user_id', $client->id)->where('id', $request->project_id)->first();
            $projectId = $project->id;
            // Delete proposals
            DB::table('proposals')
                ->where('project_id', $projectId)
                ->delete();

            // Delete from pivot tables
            DB::table('project_categories')
                ->where('project_id', $projectId)
                ->delete();

            DB::table('project_skills')
                ->where('project_id', $projectId)
                ->delete();

            DB::table('project_sub_categories')
                ->where('project_id', $projectId)
                ->delete();

            if (empty($project)) {
                throw new \Exception('Project not found');
            }

            $project->delete();
            DB::commit();

            return  $this->sendCommonResponse(false, "", [], 'Project and all related data deleted successfully.', "", ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('deleteYourJobProject  (project Api log) \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
