<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Receipt;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Receipt_image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ReceiptController extends Controller
{

    public function __construct()
    {
        //
    }

    public function uploadReceipt(Request $request)
    {
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

    public function checkCost(Request $request, $id)
    {
        $receipt = Receipt::where('id', $id)->first();
        if ($receipt->cost == $request->cost) {
            return $this->responseRequestSuccess('this cost is matched');
        }
        return $this->responseRequestError('does\'t match');
    }

    protected function uploadFiles($request)
    {
        $uploadedImages = [];
        if ($request->hasFile('file_name')) {
            $images = $request->file('file_name');
            foreach ($images as $key => $image) {
                $uploadedImages[] = $this->uploadFile($image, $key);
            }
        }
        return $uploadedImages;
    }

    protected function uploadFile($image, $key)
    {
        $original_filename = $image->getClientOriginalName();
        $original_filename_arr = explode('.', $original_filename);
        $file_ext = end($original_filename_arr);
        $destination_path = './upload/user/';
        $uploadedFileName = 'U-' . time() . '-' . $key . '.' . $file_ext;
        if ($image->move($destination_path, $uploadedFileName)) {
            $destination_path = 'http://localhost:8000/upload/user/';
            return [$uploadedFileName, $destination_path];
        }
    }

    public function updateReceipt(Request $request, $id)
    {
        $receipt = Receipt::where('id', $id)->where('status_id', Status::$WAITING)->first();
        $receipt->accountant_id = $request->get('accountant_id');
        $receipt->status_id = $request->get('status_id');
        if ($receipt->save()) {
            return $this->responseRequestSuccess($receipt);
        }
    }

    public function getReceipts(Request $request)
    {
        $receipts = Receipt::all();
        return $this->responseRequestSuccess($receipts);
    }

    public function getActivityReceipt(Receipt $receipt)
    {
        $receipt_activity = Receipt::with('activity')
            ->orderBy('created_at', 'asc')
            ->get();
        return $this->responseRequestSuccess($receipt_activity);

    }

    public function test (Request $request, $id)
    {
        $receipt_image = Receipt_image::where('receipt_id', $id)->get();
        return $this->responseRequestSuccess($receipt_image);
    }

    public function showReceipt(Request $request, $id)
    {
        //SELECT receipts.*, user_activity.user_id, users.first, users.last, user_activity.activity_id, activities.name
        //FROM `user_activity`
        //JOIN receipts ON user_activity.activity_id = receipts.activity_id
        //JOIN activities ON user_activity.activity_id = activities.id
        //JOIN users ON user_activity.user_id = users.id
        //WHERE user_activity.user_id = 1

        $query = DB::table('user_activity')
            ->join('receipts', 'user_activity.activity_id', '=', 'receipts.activity_id')
            ->join('activities', 'user_activity.activity_id', '=', 'activities.id')
            ->join('users', 'user_activity.user_id', '=', 'users.id')
            ->select('receipts.*', 'user_activity.user_id', 'users.first', 'users.last', 'user_activity.activity_id', 'activities.name')
            ->where('user_activity.user_id', '=', $id)
            ->get();
        return $this->responseRequestSuccess($query);

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
