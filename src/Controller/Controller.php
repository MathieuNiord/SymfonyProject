<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{

    /**
     * @Route("main",name="mainAction")
     */
    public function mainAction(): Response
    {
       return $this->render("templates/main.html.twig");
    }

    //TODO Action liée à la connexion d'un utilisateur.
    /**
     * @Route("login", name="loginAction")
     */
    public function loginAction(): Response
    {
        return $this->render("templates/main.html.twig");
    }


    //TODO Création d'un compte client (formulaire Symfony)
    /**
     * @Route("create", name="createAction")
     */
    public function createAction(): Response
    {
        return $this->render("templates/main.html.twig");
    }


    //TODO Déconnexion (redirect vers la page de bienvenue avec un message flash)
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
    //TODO Modifier son profil (similaire à la Création d'un compte mais avec les champs
    // remplis par les valeurs actuelles)
    /**
     * @Route("edit", name="editAction")
     */
    //TODO Lister les produits du magasins.
    /**
     * @Route("list", name="listAction")
     */
    //TODO Gérer son panier.
    /**
     * @Route("manage/cart", name="manageCartAction")
     */
    //TODO Gérer les utilisateurs.
    /**
     * @Route("manage/users", name="manageUsersAction")
     */
    //TODO Ajouter un produit.
    /**
     * @Route("add", name="addAction")
     */

}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu