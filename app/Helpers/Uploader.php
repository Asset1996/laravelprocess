<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PassLogs;
use Exception;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    public $errors = [];
    /**
     * Saves document from base64 string.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool|array
     */
    public function saveBase64(string $base64){

        $base64File = $base64;
        $fileData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));
        $fileName = uniqid() . '.jpeg';
        $filePath = '/public/passphotos/' . $fileName;

        try{
            Storage::disk('pass_photos')->put($fileName, $fileData);
        }catch(Exception $e){
            $this->errors[] = $e->getMessage();
            return False;
        }

        $fileSize = Storage::disk('pass_photos')->size($fileName);

        return [
            'fileName' => $fileName,
            'filePath' => $filePath,
            'fileSize' => $fileSize,
        ];
    }

    public function getFirstError(){
        return $this->errors[0];
    }
}
