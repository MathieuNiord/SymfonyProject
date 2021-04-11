<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends MyAbstractController
{
    /**
     * @Route("", name="accueilAction")
     */
    public function accueilAction():Response{
        return $this->render('accueil.html.twig',['user' => $this->getCurrentUser()]);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu