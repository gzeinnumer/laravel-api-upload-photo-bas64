<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhotoController extends BaseController
{
    public function uploadPhoto(Request $request)
    {
        try {
            $photos = $request->photos;
            
            DB::beginTransaction();

            $res = array();
                
            foreach ($photos as $photo) {
                if($photo['path']!= " ") {
                    $photoConfirmation = $this->processUpload($photo['path'], 'photo');
                    $res[] = $photoConfirmation['fotoName'];
                }
            }
            DB::commit();
            return response()->json(['pesan' => $res, 'status' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['pesan' => $th->getMessage(), 'status' => true]);
        }
    }
}
