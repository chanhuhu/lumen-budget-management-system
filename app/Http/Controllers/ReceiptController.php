<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Receipt_image;
use Illuminate\Support\Facades\Validator;


class ReceiptController extends Controller
{

    public function __construct()
    {
        //
    }

    public function uploadReceipt(Request $request)
    {


        $this->responseRequestSuccess($request->file('file_name'));
        $validator = Validator::make($request->all(), [
            'file_name.*' => 'image|mimes:jpg,jpeg,png,gif,bmp',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->responseRequestError($errors, 400);
        }

        $receipt = new Receipt();
        $form = [
            'activity_id' => $request->get('activity_id'),
            'date' => $request->get('date'),
            'cost' => $request->get('cost'),
            'remark' => $request->get('remark'),
        ];
        $receipt->fill($form);
        $receipt->save();
        $images = $this->uploadFiles($request);

        foreach ($images as $image_file) {
            list($file_name, $des) = $image_file;
            $image = new Receipt_image();
            $form_image = [
                'receipt_id' => $receipt->id,
                'file_name' => $des . $file_name
            ];
            $image->fill($form_image);
            $image->save();
        }
        return $this->responseRequestSuccess($receipt);
    }

    protected function uploadFiles($request)
    {
        $uploadedImages = [];
        if ($request->hasFile('file_name')) {
            $images = $request->file('file_name');
            foreach ($images as $image) {
                $uploadedImages[] = $this->uploadFile($image);
            }
        }
        return $uploadedImages;
    }

    protected function uploadFile($image)
    {
        $original_filename = $image->getClientOriginalName();
        $original_filename_arr = explode('.', $original_filename);
        $file_ext = end($original_filename_arr);
        $destination_path = './upload/user/';
        $uploadedFileName = 'U-' . time() . '.' . $file_ext;
        if ($image->move($destination_path, $uploadedFileName)) {
            return [$uploadedFileName, $destination_path];
        }
    }

    public function updateReceipt(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        $receipt->approver_id = $request->approver_id;
        if ($receipt->save()) {
            $this->responseRequestSuccess($receipt);
        } else {
            return $this->responseRequestError('The credentials provided are invalid.', 500);
        }
    }

    public function getReceipts(Request $request)
    {
        $receipts = Receipt::all();
        return $this->responseRequestSuccess($receipts);
    }


    public function createActivity(Request $request)
    {
        $activity = Activity::where('name', $request->name)->first();
        if (!empty($activity)) {
            $user = User::find($request->get('user_id'))->activities()->attach($activity->id);
            $activity['user_id'] = $request->get('user_id');
            return $this->responseRequestSuccess($activity);
        } else {
            $activity = new Activity();
            $activity->name = $request->name;
            if ($activity->save()) {
                $user = User::find($request->get('user_id'))->activities()->attach($activity->id);
                $activity['user_id'] = $request->get('user_id');
                return $this->responseRequestSuccess($activity);
            } else {
                return $this->responseRequestError('The credentials provided are invalid.', 500);
            }
        }
    }

    public function getActivities(Request $request)
    {
        $activities = Activity::all();
        return $this->responseRequestSuccess($activities);
    }

    public function showUserActivities($id)
    {
        $user = User::find($id)->activities;
        return $this->responseRequestSuccess($user);
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
