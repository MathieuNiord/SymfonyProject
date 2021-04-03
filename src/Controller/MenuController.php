<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    public function menuAction(): Response{
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('App:Utilisateur');

        $user = $userRep->find($this->getParameter('id'));

        $args = array('user' => $user);
        return $this->render('templates/header.html.twig',$args);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu