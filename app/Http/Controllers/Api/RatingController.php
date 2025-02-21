<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RatingController extends BaseController
{


    public function giveRating(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'contract_id' => 'required|exists:contracts,id',
                'review' => 'nullable',
                'rating_number' => 'required|integer|between:1,5',
            ]);

            $rating = Rating::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'contract_id' => $request->contract_id,
                ],
                [
                    'review' => $request->review,
                    'rating_number' => $request->rating_number,
                ]
            );
            $rating = Rating::find($rating->id);
            return  $this->sendCommonResponse('false', "", $rating, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('giveRatingToFreelancer  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
