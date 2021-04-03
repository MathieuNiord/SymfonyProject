<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{

    /**
     * @Route("disconnect", name="disconnectAction")
     */
    public function disconnect(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateurRep = $em->getRepository('App:Utilisateur');
        $user = $utilisateurRep->find($this->getParameter('id'));
        if(is_null($user)){
            throw NotFoundHttpException('vous ne pouvez pas vous déconnecter car vous n\'êtes pas authentifié');
        }
        else{
            //On ne fait rien car on ne gère pas la déconnexion.
            $this->addFlash('info', 'vous vous êtes bien déconnecté');
            return $this->render("templates/main.html.twig");
        }
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu