<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\User\User;

/**
 * Class UtilisateurController
 * @package App\Controller
 * @Route("user/")
 */
class UtilisateurController extends MyAbstractController
{

    /**
     * @Route (
     *     "liste",
     *     name = "utilisateur_liste"
     * )
     */
    public function editUtilisateurAction() : Response {

       $user = $this->getCurrentUser();

        if(!is_null($user) && $user->getIsAdmin()){
            $userRepository = $em->getRep('App:Utilisateur');
            $users = $userRepository->findAll();
            return $this->render('listeUtilisateurs.html.twig', ['utilisateurs'=>$users]);
        }
        throw new NotFoundHttpException("Vous n'êtes pas admin, vous ne pouvez pas éditer les utilisateurs");

    }


    /**
     * @Route("user/create",name="utilisateur_creation")
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

        return $this->render('ajoutUtilisateur.html.twig', $args);
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

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('App:Utilisateur');

        /** @var Utilisateur $currentUser */
        $currentUser = $userRepository->find($this->getParameter('id'));

        $args = array('user' => $currentUser);

        if (!is_null($currentUser) && $currentUser->getIsadmin()) {

            /** @var Utilisateur $user */
            $user = $userRepository->find($id);

            if (!is_null($user)) {
                $em->remove($user);
                $em->flush();

                $this->addFlash("info", "Utilisateur " . $user->getNom() . " supprimé");
                return $this->redirectToRoute('utilisateur_liste');
            }

            else {
                $this->addFlash("info", "L'utilisateur que vous tentez de supprimer n'existe pas ou plus");
                return $this->render('accueil.html.twig', $args);
            }
        }

        else if (!is_null($currentUser)) {
            $this->addFlash("info", "Vous n'avez pas les droits");
            return $this->render('accueil.html.twig', $args);
        }

        else {
            $this->addFlash("info", "Vous n'avez pas les droits");
            return $this->render('accueil.html.twig', $args);
        }
    }

    /**
     * @Route("disconnect", name="disconnectAction")
     */
    public function disconnect(): Response
    {
        $user = $this->getCurrentUser();

        if(is_null($user)){
            throw new NotFoundHttpException('vous ne pouvez pas vous déconnecter car vous n\'êtes pas authentifié');
        }
        $this->addFlash('info', 'vous vous êtes bien déconnecté');
        return $this->render("templates/main.html.twig");
    }
}
