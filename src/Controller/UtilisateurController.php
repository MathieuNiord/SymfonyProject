<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Service\MyService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Utilisateur;
/**
 * Class UtilisateurController
 * @package App\Controller
 * @Route("user/")
 */
class UtilisateurController extends MyAbstractController
{

    /**
     * @Route ("list", name = "utilisateur_list")
     * @param MyService $service
     * @return Response
     */
    public function userListAction(MyService $service) : Response {
        if($this->isAdmin()){

            $userRepository = $this->getRep('App:Utilisateur');
            $users = $userRepository->findAll();

            $longueur = $service->computeTotalLenOfUserName($users);//Service

            $this->addFlash('info', "la longueur totale de tous les noms des utilisateurs
            est égal à $longueur");

            return $this->render('listeUtilisateurs.html.twig',
                ['utilisateurs'=>$users, 'currUser' => $this->getCurrentUser()]);
        }
        throw new NotFoundHttpException("Vous n'êtes pas admin, vous ne pouvez pas éditer les utilisateurs");

    }


    /**
     * @Route("createoredit/{id}",name="utilisateur_createoredit", defaults={"id":"O"},
     *     requirements = {"id" = "[0-9]\d*"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function createOrEditUserAction(Request $request,$id): Response
    {
        $em = $this->getDoctrine()->getManager();

        if($id!=0){
            $idCurrentUser = $this->getCurrentUser()->getId();
            if($idCurrentUser!=$id){
                throw new NotFoundHttpException("Vous ne pouvez pas éditer un autre client !");
            }
            $user = $this->getRep('App:Utilisateur')->find($id);
            $form = $this->createForm(UtilisateurType::class,$user);
        }
        else {
            $form = $this->createForm(UtilisateurType::class);
        }

        $form->add('send', SubmitType::class, ['label' => 'Création d\'un compte utilisateur']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Utilisateur $user */

            $user = $form->getData();

            $user->setMotdepasse(sha1($user->getMotdepasse()));

            $em->persist($user);
            $em->flush();
            dump($user);
            if($id=="0"){
                $this->addFlash('info', 'L\'utilisateur a bien été créé');
            }
            else{
                $this->addFlash('info', 'L\'utilisateur a bien été édité');
            }

            return $this->render('templates/main.html.twig');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('myform' => $form->createView());

        return $this->render('ajoutUtilisateur.html.twig', $args);
    }

    // - Suppression d'un utilisateur
    /**
     * @Route ("delete",name="utilisateur_delete")
     * @param Request $request
     * @return Response
     */

    public function deleteUserAction(Request $request) : Response {

        if($this->isAdmin()){

            $em = $this->getDoctrine()->getManager();

            $userRep = $em->getRepository('App:Utilisateur');

            foreach ($request->request->all() as $key => $value) {

                $user = $userRep->find($key);
                foreach($user->getPaniers() as $panier){

                    $produit = $panier->getProduit();
                    $produit->setQuantite($produit->getQuantite()+$panier->getQuantite());

                    $em->remove($panier);
                    $em->flush();

                }
                $em->remove($user);
                $em->flush();

                $this->addFlash('info',"utilisateur supprimé");
            }
            return $this->redirectToRoute('utilisateur_list');
        }
        else throw new NotFoundHttpException("Vous devez être admin");
    }


    /**
     * @Route("connect", name="utilisateur_connect")
     */
    public function connectUserAction(): Response
    {
        if($this->isGuest())$this->addFlash('info', 'Vous pourrez bientôt vous connecter');
        else throw new NotFoundHttpException('vous êtes déjà connecté');
        return $this->redirectToRoute('accueil');
    }
    /**
     * @Route("disconnect", name="utilisateur_disconnect")
     */
    public function disconnectUserAction(): Response
    {
        $user = $this->getCurrentUser();

        if(is_null($user)){
            throw new NotFoundHttpException('vous ne pouvez pas vous déconnecter car vous n\'êtes pas authentifié');
        }
        $this->addFlash('info', 'vous vous êtes bien déconnecté');
        return $this->redirectToRoute('accueil');
    }
}


//AUTEURS : Fréjoux Gaëtan && Niord Mathieu