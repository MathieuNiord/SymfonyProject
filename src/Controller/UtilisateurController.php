<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\User\User;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function indexAction(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }

    // - Ajout d'un nouvel utilisateur -
    /**
     * @Route ("/utilisateur/liste/{nom}-{prenom}/{mdp}/{admin}{anniversaire}",
     *     name="utilisateur_ajout",
     *     defaults = {"anniversaire" : '01-01-2021'},
     *     requirements = {
     *     "nom" = "^[-]+",
     *     "prenom" = "^[-]+"
     *     }
     * )
     */
    /*
    public function AjoutUtilisateurAction($name, $firstName, $pswd, $isAdmin = false, $birtday = null) : Response {

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('App:Utilisateur');

        $user = new Utilisateur();
        $user ->setNom($name)
              ->setPrenom($firstName)
              ->setMotdepasse($pswd)
              ->setAnniversaire($birtday)
              ->setIsadmin($isAdmin);

        $em->persist($user);
        $em->flush();

        dump($user);

        return $this->redirectToRoute('templates/utilisateur/index');
    }
    */
    /**
     * @Route("create",name="createAction")
     */
    public function filmEditBisAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $filmRepository = $em->getRepository('App:Utilisateur');

        $form = $this->createForm(UtilisateurType::class);
        $form->add('send', SubmitType::class, ['label' => 'créer l\'utilisateur']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Utilisateur $user */
            $user = $form->getData();
            $user->setMotdepasse(sha1($user->getMotdepasse()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('info', 'ajout confirmé');
            return $this->render('templates/main.html.twig');
        }

        if ($form->isSubmitted())
            $this->addFlash('info', 'échec de l\'ajout');
        $args = array('myform' => $form->createView());
        return $this->render('utilisateur/ajoutUtilisateur.html.twig', $args);
    }










    // - Affichage du panier d'un utilisateur par son id -
    /**
     * @Route ("/utilisateur/liste/{id}",
     *     name="utilisateur_liste_panier",
     *     requirements = {
     *     "id" = "[1-9]\d*",
     *     }
     * )
     */

    public function listePanierUtilisateurAction($id) : Response {

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('App:Utilisateur');
        $user = $userRepository->find($id);

        $carts = $userRepository->find($user->getPanier());
        dump($carts);

        return $this->redirectToRoute('templates/utilisateur/panier', $carts);
    }

}
