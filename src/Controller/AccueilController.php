<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends MyAbstractController
{
    /**
     * @Route("", name="accueil")
     */
    public function accueilAction():Response{
        return $this->render('accueil.html.twig',['user' => $this->getCurrentUser()]);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu