<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HeaderController extends AbstractController
{
    public function imageAction(): Response{
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('App:Utilisateur');

        $user = $userRep->find($this->getParameter('id'));

        $type = 0; // 0 = visiteur, 1 = client, 2 = admin
        if(!is_null($user)){
            if($user->getIsAdmin()) $type = 2;
            else $type = 1;
        }
        $args = array('type' => $type);
        return $this->render('templates/header.html.twig',$args);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu