<?php

namespace App\Controller;

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

    /**
     * @Route (
     *     "user/list",
     *     name = "utilisateur_liste"
     * )
     */
    public function listeUtilisateurAction() : Response {

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('App:Utilisateur');
        $user = $userRepository->findAll();

        $args = array(
            'utilisateurs' => $user
        );

        return $this->render('Utilisateur/liste.html.twig', $args);
    }


    /**
     * @Route("user/create",name="utilisateur_create")
     * @param Request $request
     * @return Response
     */
    public function createUserAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UtilisateurType::class);
        $form->add('send', SubmitType::class, ['label' => 'Création d\'un compte utilisateur']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Utilisateur $user */

            $user = $form->getData();

            $user->setMotdepasse(sha1($user->getMotdepasse()));

            $em->persist($user);
            $em->flush();
            dump($user);

            $this->addFlash('info', 'L\'utilisateur a bien été créé');

            return $this->render('templates/main.html.twig');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('myform' => $form->createView());

        return $this->render('utilisateur/ajoutUtilisateur.html.twig', $args);
    }

    // - Suppression d'un utilisateur à partir de son id -

    /**
     * @Route ("user/delete/{id}",
     *     name="utilisateur_suppression",
     *     requirements = {"id" = "[0-9]\d*"}
     * )
     * @param $id
     * @return Response
     */

    public function suppressionUtilisateurAction($id) : Response {

        if ($this->getParameter('id') == 0) {

            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository('App:Utilisateur');

            ///** @var Utilisateur $user */

            $user = $userRepository->find($id);

            dump($user);

            if ($user->getIsadmin() != 0) {

                $this->addFlash("info", "Vous ne pouvez pas supprimer un administrateur");
                return $this->render('templates/main.html.twig');
            }

            else {

                $em->remove($user);
                $em->flush();

                $this->addFlash("info", "Utilisateur " . $user->getNom() . " supprimé");
                return $this->redirectToRoute('utilisateur_liste');
            }
        }

        else {
            $this->addFlash("info", "Vous n'avez pas les droits");
            return $this->render('templates/main.html.twig');
        }
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

        return $this->redirectToRoute('templates/utilisateur/panier');
    }

}
