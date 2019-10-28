<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Receipt;
use App\Models\Receipt_image;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{

    public function __construct()
    {
        //
    }

    public function uploadImage(Request $request)
    {
//        $receipt = (object)[];
        $receipt = new Receipt();
        $receipt->activity_id = $request->activity_id;
        $receipt->remark = $request->remark;
        $receipt->cost = $request->remark;
        $receipt->date = $request->date;
        if ($receipt->save()) {
            $this->responseRequestSuccess($receipt);
        }
        if ($request->hasFile('filename')) {
            $original_filename = $request->file('filename')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './upload/receipt/';
            $filename = 'R-' . time() . '.' . $file_ext;
            if ($request->file('filename')->move($destination_path, $filename)) {
//                $receipt->filename = '/upload/receipt/' . $filename;
//                $receipt->receipt_id = '';
                $receipt->save();
                return $this->responseRequestSuccess($receipt);
            } else {
                return $this->responseRequestError('Cannot upload file');
            }
        } else {
            return $this->responseRequestError('File not found');
        }
    }

    public function uploadSubmit(Request $request)
    {
        $product = Receipt_image::create($request->all());
        foreach ($request->photos as $photo) {
            $filename = $photo->store('photos');
            Receipt_image::create([
                'product_id' => $product->id,
                'filename' => $filename
            ]);
        }
        //test
        return 'Upload successful!';
    }

    public function createActivity(Request $request)
    {
        $activity = new Activity();
        $activity->name = $request->name;
        if ($activity->save()) {
            $this->responseRequestSuccess($activity);
        }
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
