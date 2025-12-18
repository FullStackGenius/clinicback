<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Contract;
use App\Models\RatingReply;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Auth;

class RatingController extends BaseController
{


    public function giveRating(Request $request)
    {
        try {
            $request->validate([
                // 'user_id' => 'required|exists:users,id',
                'contract_id' => 'required|exists:contracts,id',
                'review' => 'nullable',
                'rating_number' => 'required|integer|between:1,5',
            ]);

            $rating = Rating::updateOrCreate(
                [
                    'rating_by' => Auth::user()->id,
                    'rating_to' => Contract::find($request->contract_id)->freelancer_id,
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

    public function getRating(Request $request)
    {
        try {
            $request->validate([
                'contract_id' => 'required|exists:contracts,id',
            ]);
            $contract_id = $request->contract_id;
            $rating = Rating::where('contract_id', $contract_id)->first();
            return  $this->sendCommonResponse('false', "", $rating, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getRating  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function getFreelancerReviewRating(Request $request)
    // {

    //     try {
    //         $freelancerId =  $request->freelancer_id;
    //         $allRating = Rating::with(['ratingToUser', 'ratingbyUser', 'contractDetail', 'contractDetail.project'])->where('rating_to', $freelancerId)->get();
    //         return  $this->sendCommonResponse('false', "", $allRating, '', '', ProjectConstants::HTTP_OK);
    //     } catch (\Exception $e) {
    //         Log::channel('daily')->info('get Freelancer Review Rating \n: ' . $e->getMessage());
    //         return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function getFreelancerReviewRating(Request $request)
    {
        try {

            $freelancerId = $request->freelancer_id;

            // Get all ratings for freelancer
            $allRating = Rating::with([
                'ratingToUser',
                'ratingbyUser',
                'contractDetail',
                'contractDetail.project',
                'ratingReply',
                'ratingReply.replyBy',
                'ratingReply.replyTo'
            ])
                ->where('rating_to', $freelancerId)
                ->get();

            // Total rating count
            $totalReviews = $allRating->count();

            // Average rating (Ex: 4.9)
            $averageRating = $totalReviews > 0
                ? round($allRating->avg('rating_number'), 1)
                : 0;

            // Get rating count per star using Eloquent
            $ratingData = Rating::where('rating_to', $freelancerId)
                ->select('rating_number')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('rating_number')
                ->orderBy('rating_number', 'DESC')
                ->get()
                ->mapWithKeys(function ($item) use ($totalReviews) {
                    return [
                        $item->rating_number => [
                            'count' => $item->total,
                            'percent' => $totalReviews > 0
                                ? round(($item->total / $totalReviews) * 100)
                                : 0
                        ]
                    ];
                });

            // Ensure 1–5 stars always present
            for ($i = 1; $i <= 5; $i++) {
                if (!isset($ratingData[$i])) {
                    $ratingData[$i] = ['count' => 0, 'percent' => 0];
                }
            }

            //krsort($ratingData); // 5★ → 1★

            // Prepare response format
            $response = [
                'average_rating' => $averageRating,
                'total_reviews' => $totalReviews,
                'rating_summary' => $ratingData,  // 5★ → 1★ percentages
                'reviews' => $allRating          // full review list
            ];

            return $this->sendCommonResponse(
                false,
                "",
                $response,
                "",
                "",
                ProjectConstants::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::channel('daily')->info('get Freelancer Review Rating Error: ' . $e->getMessage());

            return $this->sendCommonResponse(
                true,
                'error',
                '',
                'something went wrong',
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function ratingDistribution($userId)
    {
        // Count total reviews
        $total = Rating::where('rating_to', $userId)->count();

        // Return 0 for all if no ratings
        if ($total === 0) {
            return response()->json([
                'average' => 0,
                'total' => 0,
                'distribution' => [
                    5 => 0,
                    4 => 0,
                    3 => 0,
                    2 => 0,
                    1 => 0,
                ]
            ]);
        }

        // Calculate average rating
        $avg = Rating::where('rating_to', $userId)->avg('rating_number');

        // Count per rating
        $ratings = Rating::where('rating_to', $userId)
            ->selectRaw('rating_number, COUNT(*) as count')
            ->groupBy('rating_number')
            ->pluck('count', 'rating_number');

        // Build distribution (5 to 1)
        $distribution = [];
        foreach ([5, 4, 3, 2, 1] as $star) {
            $count = $ratings[$star] ?? 0;
            $distribution[$star] = round(($count / $total) * 100);
        }

        return [
            'average' => round($avg, 1),
            'total' => $total,
            'distribution' => $distribution
        ];
    }

    // public function writeReply(Request $request)
    // {
    //     try {
    //         //$request->validate([
    //         // 'user_id' => 'required|exists:users,id',
    //         // 'contract_id' => 'required|exists:contracts,id',
    //         //  'reply_message' => 'nullable',
    //         //  'rating_number' => 'required|integer|between:1,5',
    //         //]);
    //         // rating_id: 1, reply_by: 311, reply_to: 312, reply_message: "rtgfewrtgwetret"
    //         // $rating = Rating::updateOrCreate(
    //         //     [
    //         //         'rating_by' => Auth::user()->id,
    //         //         'rating_id' => $request->contract_id,
    //         //          'reply_to' => $request->contract_id,

    //         //     ],
    //         //     [
    //         //         'review' => $request->review,
    //         //         'rating_number' => $request->rating_number,
    //         //     ]
    //         // );
    //         //$rating = Rating::find($rating->id);
    //         return  $this->sendCommonResponse('false', "", $request->all(), '', '', ProjectConstants::HTTP_OK);
    //     } catch (ValidationException $e) {
    //         return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     } catch (\Exception $e) {
    //         Log::channel('daily')->info('giveRatingToFreelancer  Api log \n: ' . $e->getMessage());
    //         return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    public function writeReply(Request $request)
    {
        try {

            $request->validate([
                'rating_id'      => 'required|integer',
                'reply_by'       => 'required|integer',
                'reply_to'       => 'required|integer',
                'reply_message'  => 'required|string'
            ]);

            $reply = RatingReply::updateOrCreate(
                // Conditions (to check existing record)
                [
                    'rating_id' => $request->rating_id,
                    'reply_by'  => $request->reply_by,
                    'reply_to'  => $request->reply_to,
                ],
                // Data to insert/update
                [
                    'comments' => $request->reply_message,
                ]
            );

            return $this->sendCommonResponse(
                false,
                'Reply saved successfully',
                $reply,
                '',
                '',
                ProjectConstants::HTTP_OK
            );
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(
                true,
                'validation',
                '',
                $e->errors(),
                '',
                ProjectConstants::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $e) {
            Log::channel('daily')->error('writeReply API error: ' . $e->getMessage());

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


    public function getRatingReply(Request $request)
    {
        try {
            $request->validate([
                'reply_by' => 'required',
                'reply_to' => 'required',
                'rating_id' => 'required'
            ]);
            $reply_by = $request->reply_by;
            $reply_to = $request->reply_to;
            $rating_id = $request->rating_id;
            $rating = RatingReply::where('reply_to', $reply_to)->where('reply_by', $reply_by)->where('rating_id', $rating_id)->first();
            return  $this->sendCommonResponse('false', "", $rating, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::channel('daily')->info('getRating  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
