<?php

namespace App\Http\Controllers;

use App\AuthToken;
use App\Dir;
use App\Http\Requests\StoreUserRequest;
use App\User;
use App\UserAccess;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
      return response()->json(User::all(), 200);
    }

    public function login (Request $request)
    {
        if($user = User::where(['login' => $request->login])->first() and Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "Autorisation réussie",
                'token' => $user->createToken()
            ])->setStatusCode(200, 'Autorisation réussie');
        }
        return response()->json([
            'message' => "Erreur d'autorisation"
        ])->setStatusCode(401, 'Erreur d\'autorisation');
    }

    public function logout()
    {
        $user = User::find(Auth::id());
        $user->api_token = null;
        $user->save();
        return response()->json([
            'message' => "Avec succès"
        ])->setStatusCode(200, "Avec succès");
    }

    public function devices()
    {
        return response()->json(AuthToken::all())->setStatusCode(200, "Avec succès");
    }

    public function device_destroy(AuthToken $auth_token)
    {
        $auth_token->delete();
        return response()->json(['message' => "Suppression réussie"])->setStatusCode(200, "Suppression réussie");
    }

    public function store(StoreUserRequest $request)
    {
        return response()->json([
            "message" => "Créé par l'utilisateur",
            'password' => User::createUser($request)
        ])->setStatusCode(201, "Créé par l'utilisateur");
    }

    public function access(User $user, $folder_id)
    {
        $folder = Dir::find(($folder_id == 'root') ? 1 : $folder_id);

        if (!$folder) {
            throw new HttpResponseException(response()->json([
                'message' => "Model not found"
            ])->setStatusCode(404, "Model not found"));
        }

        UserAccess::create([
            'user_id' => $user->id,
            'folder_id' => $folder->id
        ]);

        return response()->json([
            'message' => "Avec succès"
        ])->setStatusCode(200,"Avec succès");
    }

    public function auth_user(){
        return Auth::user();
    }

    public function destroy(User $user){
        if($user->id == 1) return;
        $user->delete();
        return response()->json([
            'message' => 'User delete success'
        ], 200);
    }
}
