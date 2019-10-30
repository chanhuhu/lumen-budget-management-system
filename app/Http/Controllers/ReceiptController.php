<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ReceiptController extends Controller
{

    public function __construct()
    {
        //
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'remark' => 'required',
            'cost' => 'required',
            'date' => 'required',
            'files' => 'required'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseRequestError($errors, 400);
        } else {
            $receipt = new Receipt();
            $form = [
                'remark' => $request->get('remark'),
                'cost' => $request->get('cost'),
                'date' => $request->get('date'),
            ];
            $receipt->fill($form);
            $receipt->save();
            return $this->responseRequestSuccess($receipt);
//            if ($receipt->save()) {
//                $user = (object)['image' => ""];
//                if ($request->has('flies')) {
//                    $original_filename = $request->file('image')->getClientOriginalName();
//                    $original_filename_arr = explode('.', $original_filename);
//                    $file_ext = end($original_filename_arr);
//                    $destination_path = './upload/user/';
//                    $image = 'U-' . time() . '.' . $file_ext;
//                    if ($request->file('image')->move($destination_path, $image)) {
//                        $user->image = '/upload/user/' . $image;
//                        return $this->responseRequestSuccess($user);
//                    } else {
//                        return $this->responseRequestError('Cannot upload file');
//                    }
//
//                } else {
//                    return $this->responseRequestError('File not found');
//                }
//            } else {
//                return $this->responseRequestError('Cannot create receipt');
//            }
        }
    }

    public function getReceipts(Request $request)
    {
        $receipts = Receipt::all();
        return $this->responseRequestSuccess($receipts);
    }


    public function createActivity(Request $request)
    {
        $activity = new Activity();
        $activity->name = $request->name;
        $activity->save();
        return $this->responseRequestSuccess($activity);
    }

    public function getActivities(Request $request)
    {
        $activities = Activity::all();
        return $this->responseRequestSuccess($activities);
    }

    protected function responseRequestError($message = 'Bad request', $statusCode = 200)
    {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestSuccess($ret)
    {
        return response()->json(['status' => 'success', 'data' => $ret], 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

}
