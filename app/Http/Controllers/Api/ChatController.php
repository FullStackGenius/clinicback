<?php

namespace App\Http\Controllers\Api;

use App\Constants\ProjectConstants;
use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\NewMessageNotification;
use App\Models\Contract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\MailService;

class ChatController extends BaseController
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function startChat($userId  = null, $contractId = null)
    {

        try {
            $authUserId = Auth::id();
            // Check if the chat already exists
            // if (!empty($userId)) {
            //     $chatData = Chat::where(function ($query) use ($authUserId, $userId) {
            //         $query->where('user_one_id', $authUserId)
            //             ->where('user_two_id', $userId);
            //     })->orWhere(function ($query) use ($authUserId, $userId) {
            //         $query->where('user_one_id', $userId)
            //             ->where('user_two_id', $authUserId);
            //     })->first();

            //     if (empty($chatData)) {
            //         //return Redirect::route('chat',$existingChat->id);
            //         $chatData = Chat::create([
            //             'user_one_id' => $authUserId,
            //             'user_two_id' => $userId
            //         ]);
            //     }
            //     $data['currentChatUser'] = User::find($userId);
            // } else if ((!empty($userId) && !empty($contractId))) {
            //     dd(!empty($userId) && !empty($contractId));
            //     $chatData = Chat::where(function ($query) use ($authUserId, $userId, $contractId) {
            //         $query->where('user_one_id', $authUserId)
            //             ->where('user_two_id', $userId)
            //             ->where('contract_id', $contractId);
            //     })->orWhere(function ($query) use ($authUserId, $userId, $contractId) {
            //         $query->where('user_one_id', $userId)
            //             ->where('user_two_id', $authUserId)
            //             ->where('contract_id', $contractId);
            //     })->first();

            //     if (empty($chatData) && !empty($userId) && !empty($contractId)) {
            //         //return Redirect::route('chat',$existingChat->id);
            //         $chatData = Chat::create([
            //             'user_one_id' => $authUserId,
            //             'user_two_id' => $userId,
            //             'contract_id' => $contractId
            //         ]);
            //     } else {
            //         $chatData = Chat::create([
            //             'user_one_id' => $authUserId,
            //             'user_two_id' => $userId,
            //         ]);
            //     }
            //     $data['currentChatUser'] = User::find($userId);
            // } else {
            //      dd(!empty($userId) && !empty($contractId));
            //     $data['currentChatUser'] = [];
            // }
            $currentChatUser = User::find($userId);
            if (!empty($userId) && !empty($contractId)) {
              
                $chatData = Chat::where(function ($query) use ($authUserId, $userId, $contractId) {
                    $query->where('user_one_id', $authUserId)
                        ->where('user_two_id', $userId)
                        ->where('contract_id', $contractId);
                })->orWhere(function ($query) use ($authUserId, $userId, $contractId) {
                    $query->where('user_one_id', $userId)
                        ->where('user_two_id', $authUserId)
                        ->where('contract_id', $contractId);
                })->first();

                if (empty($chatData)) {
                    $chatData = Chat::create([
                        'user_one_id' => $authUserId,
                        'user_two_id' => $userId,
                        'contract_id' => $contractId
                    ]);
                }
            $currentChatUser->contract_id = $contractId;
                $data['currentChatUser'] = $currentChatUser;
            } else  if ((!empty($userId))) {
                $chatData = Chat::where(function ($query) use ($authUserId, $userId) {
                    $query->where('user_one_id', $authUserId)
                        ->where('user_two_id', $userId)
                        ->where('contract_id', NULL);
                })->orWhere(function ($query) use ($authUserId, $userId) {
                    $query->where('user_one_id', $userId)
                        ->where('user_two_id', $authUserId)
                        ->where('contract_id', NULL);
                })->first();

                if (empty($chatData)) {
                    $chatData = Chat::create([
                        'user_one_id' => $authUserId,
                        'user_two_id' => $userId
                    ]);
                }
                $currentChatUser->contract_id = NULL;
                $data['currentChatUser'] = $currentChatUser;
            }
            else {
                $data['currentChatUser'] = [];
            }


            // if (!empty($authUserId) && !empty($contractId)) {
            $chats = Chat::where('user_one_id', $authUserId)
                ->orWhere('user_two_id', $authUserId)
                // ->orWhere('contract_id', $contractId)
                ->with([
                    'messages' => function ($query) {
                        $query->orderBy('created_at', 'desc')->limit(1); // Fetch the latest message
                    }
                ])
                ->withCount([
                    'messages as unread_count' => function ($query) use ($authUserId) {
                        $query->where('is_read', false)
                            ->where('sender_id', '!=', $authUserId); // Count unread messages not from the current user
                    }
                ])
                ->get();
            // } else {
            //     $chats = Chat::where('user_one_id', $authUserId)
            //         ->orWhere('user_two_id', $authUserId)
            //         ->with([
            //             'messages' => function ($query) {
            //                 $query->orderBy('created_at', 'desc')->limit(1); // Fetch the latest message
            //             }
            //         ])
            //         ->withCount([
            //             'messages as unread_count' => function ($query) use ($authUserId) {
            //                 $query->where('is_read', false)
            //                     ->where('sender_id', '!=', $authUserId); // Count unread messages not from the current user
            //             }
            //         ])
            //         ->get();
            // }
            // $chats = Chat::where('user_one_id', $authUserId)
            //     ->orWhere('user_two_id', $authUserId)
            //     ->with([
            //         'messages' => function ($query) {
            //             $query->orderBy('created_at', 'desc')->limit(1); // Fetch the latest message
            //         }
            //     ])
            //     ->withCount([
            //         'messages as unread_count' => function ($query) use ($authUserId) {
            //             $query->where('is_read', false)
            //                 ->where('sender_id', '!=', $authUserId); // Count unread messages not from the current user
            //         }
            //     ])
            //     ->get();

            // Initialize an empty array to store the chat partners
            $chatPartners = [];

            // Loop through the chats and add the other user to the list
            foreach ($chats as $chat) {
                $partnerId = ($chat->user_one_id == $authUserId) ? $chat->user_two_id : $chat->user_one_id;

                // Retrieve the partner's information
                $chatPartner = User::find($partnerId);

                // Add the partner to the list if it's not already added
                if ($chatPartner && !in_array($chatPartner, $chatPartners)) {
                    $chatPartner->chat_id = $chat->id;
                    $chatPartner->contract_id = $chat->contract_id;
                    $project = Contract::with('project')->find($chat->contract_id);
                    $chatPartner->project_title = ($project) ? $project->project->title : "";
                    $chatPartner->chat_data = $chat;
                    $chatPartners[] = $chatPartner;
                }
            }


            $data['chatPartners'] = $chatPartners;
            // Retrieve messages for the given chat ID
            if (isset($chatData) && !empty($chatData)) {
                $messages = Message::with(['user'])->where('chat_id', $chatData->id)
                    ->orderBy('id', 'asc')
                    ->get();
                // Message::where('chat_id', $chatData->id)
                //     ->update(['is_read' => true]);
                Message::where('chat_id', $chatData->id)
                    ->where('sender_id', '!=', $authUserId)  // Exclude messages sent by the current user
                    ->where('is_read', false)               // Target only unread messages
                    ->update(['is_read' => true]);


                $data['messages'] = $messages;
                $data['chatId'] = $chatData->id;
            } else {
                $data['messages'] = [];
                $data['chatId'] = "";
            }


            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            Log::channel('daily')->info('startChat  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'chat_id' => 'required|exists:chats,id',
                'message' => 'nullable|string',
                'file' => 'nullable|max:20480', // 20MB max for file uploads
            ]);

            $chatId = $request->chat_id;
            $authUserId = Auth::user();

            $filePath = null;
            $fileType = null;

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Generate a unique filename with the original extension
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store file in the desired directory
                $filePath = $file->storeAs('messages', $filename, 'public');
                $fileType = $file->getMimeType();
            }

            // Save the new message
            $message = Message::create([
                'chat_id' => $chatId,
                'sender_id' => $authUserId->id,
                'message' => (!empty($request->message)) ? $request->message : null,
                'file_path' => (!empty($filename)) ? $filename : null,
                'file_type' => (!empty($fileType)) ? $fileType : "text",
            ]);
            $messageData = Message::find($message->id);
            $chatData = Chat::find($messageData->chat_id);
            if ($chatData->user_one_id  == $authUserId->id) {
                $recevier = User::find($chatData->user_two_id);
            } else {
                $recevier = User::find($chatData->user_one_id);
            }

            if (!empty($chatData->contract_id)) {
                $chatLink =  ProjectConstants::FRONTEND_PATH . '/chat/' . $authUserId->id . "/" . $chatData->contract_id;
            } else {
                $chatLink =  ProjectConstants::FRONTEND_PATH . '/chat/' . $authUserId->id;
            }

            event(new ChatMessageSent([
                // 'from' => $authUserId->id,
                'toUserId' => $recevier->id,
                'senderName' => $authUserId->name . " " . $authUserId->last_name,
                'chatLink' => $chatLink,
                'message' => (!empty($request->message)) ? $request->message : null,
            ], $recevier->id));
            //  $this->mailService->safeSend($recevier->email,new NewMessageNotification($messageData, $recevier, $authUserId->name . " " . $authUserId->last_name, $chatId),'sendMessage mail');
            //Mail::to($recevier->email)->send(new NewMessageNotification($messageData, $recevier, $authUserId->name . " " . $authUserId->last_name, $chatId));
            $messages = Message::where('chat_id', $chatId)
                ->orderBy('created_at', 'asc')
                ->get();
            $data['messages'] = $messages;
            return  $this->sendCommonResponse('false', "", $data, '', '', ProjectConstants::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->sendCommonResponse(true, 'validation', '', $e->errors(), '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            ///  dd($e->getMessage());
            Log::channel('daily')->info('sendMessage  Api log \n: ' . $e->getMessage());
            return $this->sendCommonResponse(true, 'error', '', 'something went wrong', '', ProjectConstants::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
