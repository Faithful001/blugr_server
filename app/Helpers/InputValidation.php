<?php

namespace App\Helpers;

function inputFieldValidation($user_name, $email, $password)
    {
        if (!$user_name || !$email || !$password) {
            throw new \Exception("All fields are required");
        }
        if (Str::length($password) < 8) {
            throw new \Exception("Password length must be more than 8 characters");
        }
    }
