<?php

namespace App\Helpers;

class Token {
    public function generateToken($user, $id){
        $token = $user->createToken($id)->plainTextToken;
        return $token;
    }
}
