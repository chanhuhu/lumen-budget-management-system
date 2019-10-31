<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Models\Receipt_image;
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
            'activity_id' => 'required',
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
                'activity_id' => $request->get('activity_id')
            ];
            $receipt->fill($form);

            if ($receipt->save()) {
                $img_url = $this->downloadPhotos($receipt->id, $request);
                $receipt['img_url'] = $img_url->filename;
                return $this->responseRequestSuccess($receipt);

            }

        }
    }

    public function downloadPhotos($receipt_id, Request $request)
    {
        foreach ($request->files as $file) {
            $original_filename = $file->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './upload/user/';
            $image = 'U-' . time() . '.' . $file_ext;
            if ($request->file('image')->move($destination_path, $image)) {
                $photo = new Receipt_image();
                $filename = '/upload/user/' . $image;
                $photo->receipt_id = $receipt_id;
                $photo->filename = $filename;
                $photo->save();
                return $photo;
            }
        }

    }

    public function updateReceipt(Request $request, $id)
    {
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
