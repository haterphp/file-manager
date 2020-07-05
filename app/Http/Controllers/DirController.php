<?php

namespace App\Http\Controllers;

use App\ChildrenDir;
use App\Dir;
use App\Http\Requests\StoreDirRequest;
use App\Http\Resources\FolderResource;
use App\UserAccess;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirController extends Controller
{
    public function store(StoreDirRequest $request, $folder_id)
    {
        $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

        if (!$folder) {
            throw new HttpResponseException(response()->json([
                'message' => "Model not found"
            ])->setStatusCode(404, "Model not found"));
        }

        if (!UserAccess::where(['folder_id' => $folder->id])->get()->pluck('user_id')->contains(Auth::id()) && Auth::id() != 1) {
            return response()->json([
                'message' => "Erreur d'accès"
            ])->setStatusCode(403, "Erreur d'accès");
        }

        $new_folder = Dir::create([
            'name' => $request->name
        ]);

        ChildrenDir::create([
            'type' => 'folder',
            'parent' => $folder->id,
            'child_dir' => $new_folder->id,
            'user_id' => Auth::user()->id
        ]);

        UserAccess::create([
            'folder_id' => $new_folder->id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => "Création réussie"
        ])->setStatusCode(201, "Création réussie");
    }

    public function view($folder_id)
    {
        $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

        if (!$folder) {
            throw new HttpResponseException(response()->json([
                'message' => "Model not found"
            ])->setStatusCode(404, "Model not found"));
        }

        if (!UserAccess::where(['folder_id' => $folder->id])->get()->pluck('user_id')->contains(Auth::id()) && Auth::id() != 1) {
            return response()->json([
                'message' => "Erreur d'accès"
            ])->setStatusCode(403, "Erreur d'accès");
        }

        $response = collect([]);

        $response->push(...$folder->children_dirs()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'path' => $item->path(),
                'name' => $item->name,
                'type' => 'folder'
            ];
        }));

        $response->push(...$folder->children_files()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'path' => $item->path(),
                'type' => 'file',
                'name' => $item->name,
                'url' => $item->url
            ];
        }));

        return response()->json($response)->setStatusCode(201, "Téléchargement réussi");
    }

    public function destroy(Dir $dir)
    {
        if (!UserAccess::where(['folder_id' => $dir->id])->get()->pluck('user_id')->contains(Auth::id()) && Auth::id() != 1) {
            return response()->json([
                'message' => "Erreur d'accès"
            ])->setStatusCode(403, "Erreur d'accès");
        }

        $dir->delete();
        return response()->json(['message' => "Suppression d'un répertoire"])->setStatusCode(200, "Suppression d'un répertoire");
    }

    public function get_path($folder_id){

        $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

        if (!$folder) {
            throw new HttpResponseException(response()->json([
                'message' => "Model not found"
            ])->setStatusCode(404, "Model not found"));
        }

        return response()->json([
            'path' => $folder->find_path($folder->id)
        ], 200);
    }

    public function get_name($folder_id){
      $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

      if (!$folder) {
          throw new HttpResponseException(response()->json([
              'message' => "Model not found"
          ])->setStatusCode(404, "Model not found"));
      }

      return response()->json([
          'name' => $folder->name
      ], 200);
    }
}
