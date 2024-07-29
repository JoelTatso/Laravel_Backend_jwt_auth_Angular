<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUserRequest;
use App\Http\Requests\loginRequest;
use App\Http\Requests\SetPasswordRequest;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(createUserRequest $request){

        try {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => 201,
                'message' => 'Utilisateur enregistré avec succès'
            ]);

        } catch (Exception $e) {

            return response()->json($e);

        }

    }//Enregister un utilisateur

    public function login(loginRequest $request){
      try {

            $crudentials = $request->validated();

            if(Auth::attempt($crudentials)){

                $user = User::query()->find(Auth::user()->id);
                $token = $user->getToken('my_secret_key');

                return response()->json([
                "status" => true,
                "message" => "Connexion réussite !!!",
                "token" => $token
                ]);

        }else{

            return response()->json([
                "status" => false,
                "message" => "Veillez vérifier vos informations de connexion !!!",
                "token" => null
            ]);

        }

      } catch (Exception $e) {
        return response()->json($e);
      }
    }//Connecter un utilisateur

    public function getUser(){

      try {

        if(Auth::user()){

          return response()->json([
            "user" => Auth::user()
          ]);

        }else{

          return response()->json([
            "message" => "Aucun utilisateur connecté !!!"
          ]);

        }

      } catch ( Exception $e) {

        return response()->json($e);

      }
    }//recupérer l'utilisateur connecté

    public function logout(){
      try {

          Auth::user()->tokens()->delete();
          Auth::logout();

          return response()->json(["message" => "Utilisateur déconnecté"]);

      } catch (Exception $e) {

          return response()->json($e);

      }
    }//Déconnexion

    public function generateTwoFacteurUserCode(Request $request){

        $user = User::query()->where('email','=',$request->input('email'))->first();
        $user->generateTwoFacteurCode();
        $user->notify(new TwoFactorCode());

        return response()->json([
            'message' => 'le code a été généré vérifiez votre boîte mail'
        ]);


    }//génère le code d'authentification à deux facteur

    public function emailExist(Request $request){

        $user = User::query()->where('email', '=', $request->input('email'))->first();

        if($user){
            return response()->json([
                'status' => true,
            ]);
        }else{
            return response()->json([
                'status' => false
            ]);
        }
    }//vérifier l'email de l'utilisateur

    public function setPassword(SetPasswordRequest $request){
        $user = User::query()->where('email','=',$request->input('email'))->first();
        $user->setUserPassword($request->input('password'));
        return response()->json([
            "status" => true
        ]);
    }

}
