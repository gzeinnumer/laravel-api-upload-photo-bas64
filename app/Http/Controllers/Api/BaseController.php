<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\ApiResponse;
use App\Models\API\Log\TransLogApi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BaseController extends Controller
{

    private function validasiExtensionImage($file)
    {
        $data = explode(";", $file);
        $originalExtension = explode("/", $data[0]);
        if ($originalExtension[1] == 'jpeg' || $originalExtension[1] == 'png' || $originalExtension == 'jpg') {
            return $originalExtension[1];
        } else {
            $response = array(
                'data' => [],
                'message' => 'Extension file bukan termasuk image.',
                'status' => '405',
            );
            return response()->json($response);
        }
    }

    private function validasiFolder($date, $module)
    {
        $filename = public_path('storage/' . $module);
        if (!file_exists($filename)) {
            mkdir(public_path('storage/' . $module));
        }
    }

    public function processUpload($file, $module)
    {
        $date = date("YmdHis");
        $extension = $this->validasiExtensionImage($file);
        $image = str_replace('data:image/' . $extension . ';base64,', '', $file);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(40) . '.' . '' . $extension . '';
        $this->validasiFolder($date, $module);
        File::put(public_path('storage/' . $module) . '/' . $imageName, base64_decode($image));
        return array(
            'fotoName' => 'storage/' . $module . '/' . $imageName,
            'uploaded' => true,
            'directory' => 'storage/' . $module,
        );
    }
}
