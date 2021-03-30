<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;
use App\Entity\Utilisateur;

class PanierController extends AbstractController
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
     * @Route ("/panier/ajout/{id_utilisateur}/{id_produit}/{quantite}",
     *     name="panier_ajout",
     *     defaults = {"quantite" : 1},
     *     requirements = {
     *     "id_utilisateur" = "[1-9]\d*",
     *     "id_produit" = "[1-9]\d*",
     *     "quantite" = "[1-9]\d*"
     *     }
     * )
     */

    public function ajoutPanierAction($user, $product, $quantity) : Response {

        $em = $this->getDoctrine()->getManager();

        $panier = new Panier();
        $panier -> setIdUtilisateur($user)
                -> setIdProduit($product)
                ->setQuantite($quantity);

        $em->persist($panier);
        $em->flush();
        dump($panier);

        return $this->redirectToRoute('panier');
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
}
