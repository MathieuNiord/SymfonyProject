<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HeaderController extends MyAbstractController
{
    public function imageAction(): Response{
        return $this->render('templates/header.html.twig',['user' => $this->getCurrentUser()]);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu