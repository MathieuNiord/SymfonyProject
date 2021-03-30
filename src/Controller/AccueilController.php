<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("", name="accueil")
     */
    public function accueilAction():Response{

        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('App:Utilisateur');

        $id = $this->getParameter('id');

        $user = $userRep->find($id);



        return $this->render('accueil.html.twig',['user' => $user]);
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu