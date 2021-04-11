<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends MyAbstractController
{

    public function menuAction(): Response{
        return $this->render('templates/menuList.html.twig',
            array('user' =>$this->getCurrentUser(), 'nbProduits' => $this->getNbProducts()));
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu