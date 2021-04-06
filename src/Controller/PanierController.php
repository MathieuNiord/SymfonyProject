<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;
class PanierController extends MyAbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function indexAction(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    // - Ajout d'un produit dans le panier pour un utilisateur donné -

    /**
     * @Route ("/panier/ajout/{id_user}/{id_produit}/{quantite}",
     *     name="panier_ajout",
     *     defaults = {"quantite" : 1},
     *     requirements = {
     *     "id_produit" = "[1-9]\d*",
     *     "quantite" = "[1-9]\d*"
     *     }
     * )
     * @param $user
     * @param $product
     * @param $quantity
     * @return Response
     */

    public function ajoutPanierAction($user, $product, $quantity) : Response {

        $em = $this->getDoctrine()->getManager();

        if (!is_null($user) && !is_null($product)) {
            $panier = new Panier();
            $panier->setUtilisateur($user)
                ->setProduit($product)
                ->setQuantite($quantity);

            $em->persist($panier);
            $em->flush();
            dump($panier);

            return $this->redirectToRoute('panier_liste');
        }

        else return $this->render('main.html.twig');
    }

    // - Suppression d'un article dans le panier (enregistrement de la table) avec son id -
    /**
     * @Route ("/panier/suppression/{id}",
     *     name="panier_suppression",
     *     requirements = {
     *     "id" = "[1-9]\d*"
     *     }
     * )
     */

    public function suppressionPanierAction($id) : Response {

        $em = $this->getDoctrine()->getManager();
        $panierRepository = $em->getRepository('App:Panier');
        $panier = $panierRepository->find($id);


        $em->remove($panier);
        $em->flush();
        dump($panier);

        return $this->redirectToRoute('panier');
    }

    // - Liste les paniers de l'utilisateur actuel -
    /**
     * @Route ("/listepanier", name = "panier_liste")
     */
    public function listePanierAction() : Response {
        $user = $this->getCurrentUser();
        if(is_null($user) || $user->getIsAdmin()){
            throw new NotFoundHttpException('Vous êtes admin, vous ne pouvez pas avoir de panier');
        }
        return $this->render('panier.html.twig', ['user'=>$this->getCurrentUser()]);
    }
}
