<?php

namespace App\Service;

class MyService
{

    public function computeTotalLenOfUserName($listUser): int
    {
        $ans = 0;
        foreach ($listUser as $user) {
            $ans += strlen($user->getNom());
        }
        return $ans;
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu