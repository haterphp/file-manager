<?php

namespace App\Http\Controllers;

use App\ChildrenDir;
use App\Dir;
use App\File;
use App\Http\Requests\StoreFileRequest;
use App\UserAccess;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function store(StoreFileRequest $request, $folder_id)
    {

        $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

        if (!$folder) {
            throw new HttpResponseException(response()->json([
                'message' => "Model not found"
            ])->setStatusCode(404, "Model not found"));
        }

        if(!UserAccess::where(['folder_id' => $folder->id])->get()->pluck('user_id')->contains(Auth::id()) && Auth::id() != 1){
            return response()->json([
                'message' => "Erreur d'accès"
            ])->setStatusCode(403, "Erreur d'accès");
        }

        $file = $request->file('file')->store('public');

        $new_file = File::create([
            'url' => $file,
            'name' => str_replace('public/', "", $file)
        ]);

        ChildrenDir::create([
            'parent' => $folder->id,
            'child_file' => $new_file->id,
            'type' => 'file',
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => "Téléchargement réussi"
        ])->setStatusCode(201, "Téléchargement réussi");
    }

    public function destroy(File $file)
    {

        Storage::delete($file->url);
        $file->delete();
        return response()->json([
            'message' => "Avec succès"
        ])->setStatusCode(200, "Avec succès");
    }

}
