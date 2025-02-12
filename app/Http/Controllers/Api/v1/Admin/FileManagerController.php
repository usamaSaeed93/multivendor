<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Helpers\CResponse;
use App\Helpers\Util\StringUtil;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;


class FileManagerController extends Controller
{


    public function index(Request $request): array
    {
        $folder_path = $request->query('path', base64_encode('public'));

        $file = Storage::files(base64_decode($folder_path));
        $directories = Storage::directories(base64_decode($folder_path));

        $folders = self::formatFiles($directories, 'folder');
        $files = self::formatFiles($file, 'file');

        return array_merge($folders, $files);
    }



     public function store(Request $request)
    {
        if($request->hasFile('file')) {
            try {
                $dir_path = 'uploaded_files';
                $zip_file = $request->file('file');
                $zip = new ZipArchive();
                $file_new_path = $zip_file->storeAs('', 'temp_zip', 'public');
                $zipFile = $zip->open(Storage::disk('public')->path($file_new_path));
                if ($zipFile === TRUE) {
                    $zip->extractTo(Storage::disk('public')->path($dir_path));
                    $zip->close();
                }
                Storage::disk('public')->delete($file_new_path);
                return CResponse::success();

            }catch(Exception){
                return CResponse::error();
            }

        }else{
            return CResponse::error("Zip is missing");
        }

//        return array_merge($folders, $files);
    }





    ///===================== Helper Functions =======================================================//


    public static function formatFiles($files, $type): array
    {
        $data = [];
        foreach ($files as $file) {
            $name = self::get_file_name($file);
            $ext = self::get_file_ext($name);
            $path = '';
            if ($type == 'file') {
                $path = $file;
            }
            if ($type == 'folder') {
                $path = $file;
            }
            if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif', 'bmp', 'tif', 'tiff']) || $type == 'folder')
                $data[] = [
                    'name' => $name,
                    'path' => $path,
                    'storage_path' => self::get_storage_path($file),
                    'url'=>ResourceUtil::getImagePath(self::get_storage_path($file)),
                    'type' => $type
                ];
        }
        return $data;
    }

    private static function get_file_name($path): bool|string
    {
        $temp = explode('/', $path);
        return end($temp);
    }

    private static function get_file_ext($name): bool|string
    {
        $temp = explode('.', $name);
        return end($temp);
    }

    private static function get_storage_path($full_path): bool|string
    {
        $temp = explode('/', $full_path, 2);
        return end($temp);
    }


}

