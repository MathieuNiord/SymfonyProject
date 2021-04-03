<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HeaderController extends AbstractController
{
    private function getRep($className){
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository($className);
    }


    public function imageAction(): Response{
        $userRep = $this->getRep('App:Utilisateur');
        $user = $userRep->find($this->getParameter('id'));
        return $this->render('templates/header.html.twig',array('user' => $user));
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu