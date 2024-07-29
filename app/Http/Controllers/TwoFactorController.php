<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwofactorRequest;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function store(TwofactorRequest $request){

        $user = User::query()->where('email','=',$request->input('email'))->first();

        if($request->input('two_factor_code') == $user->two_factor_code){

            $user->resetTwoFactorCode();

            return response()->json([
                'two_factor_code' => true
            ]);
        }else{

          return response()->json([
            'two_factor_code' => false
          ]);
        }

    }// Vérification du two_factor_code et retour de la reponse json

    public function resend(Request $request){

      $user = User::query()->where('email','=',$request->input('email'))->first();
      $user->generateTwoFacteurCode();
      $user->notify(new TwoFactorCode());

      return response()->json([
        'message' => 'le code a de nouveau été envoyé !!!'
      ]);
    }// renvoi du code a deux facteurs
}
