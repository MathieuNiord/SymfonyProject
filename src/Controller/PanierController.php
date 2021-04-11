<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;

/**
 * Class PanierController
 * @package App\Controller
 * @Route("cart/")
 */
class PanierController extends MyAbstractController
{

    // - Ajout d'un produit dans le panier pour un utilisateur donné -

    /**
     * @Route ("/add/{id}/{quantity}",
     *     name="cartAddAction",
     *     defaults = {"quantity" : 1},
     *     requirements = {
     *     "id" = "[1-9]\d*",
     *     "quantity" = "[1-9]\d*"
     *     }
     * )

     * @param $id
     * @param $quantity
     * @return Response
     */

    public function cartAddAction($id, $quantity) : Response {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getCurrentUser();

        if (!is_null($user) && !$user->isAdmin())
        {

            $produit = $em->getRepository('App:Produit')->find($id);

            if(!is_null($produit) && ($produit->getQuantite() >= $quantity))
            {
                $produit->setQuantite($produit->getQuantite() - $quantity);

                $panier = new Panier();
                $panier->setUtilisateur($user)
                    ->setProduit($id)
                    ->setQuantite($quantity);

                $em->persist($panier);
                $em->flush();

                dump($panier);
                dump($produit);
            }
            return $this->redirectToRoute('cartListAction');
        }

        else throw new NotFoundHttpException("Erreur !");
    }

    // - Suppression d'un article dans le panier (enregistrement de la table) avec son id -
    /**
     * @Route ("/delete/{id}",
     *     name="cartDeleteAction",
     *     requirements = {
     *     "id" = "[1-9]\d*"
     *     }
     * )
     */

    public function cartDeleteAction($id) : Response {

        $em = $this->getDoctrine()->getManager();
        $panierRepository = $em->getRepository('App:Panier');
        $cart = $panierRepository->find($id);

        if(!is_null($cart) && $cart->getUtilisateur()->getId()==$this->getParameter('id'))
        {
            $cart->getProduit()->setQuantite($cart->getProduit()->getQuantite()+  $cart->getQuantite());
            $em->remove($cart);
            $em->flush();
        }
        else throw new NotFoundHttpException("Vous n'avez pas les droits de modifier ce panier");
        return $this->redirectToRoute('cartListAction');
    }

    // - Liste les paniers de l'utilisateur actuel -
    /**
     * @Route ("list", name = "cartListAction")
     */
    public function cartListAction() : Response {
        $user = $this->getCurrentUser();
        if(is_null($user || $user->isAdmin())){
            throw new NotFoundHttpException("Vous n'êtes pas un client, vous ne n'avez pas de panier ");
        }
        return $this->render('panier.html.twig', ['user'=>$this->getCurrentUser()]);
    }
}
