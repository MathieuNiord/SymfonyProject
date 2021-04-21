<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
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

    // - Suppression d'un article dans le panier (enregistrement de la table) avec son id -
    /**
     * @Route ("delete",name="panier_delete")
     * @param Request $request
     * @return Response
     */

    public function cartDeleteAction(Request $request) : Response {
        if($this->isClient()){
            $user = $this->getCurrentUser();
            $em = $this->getDoctrine()->getManager();
            $panierRepository = $em->getRepository('App:Panier');


            //cas où l'on vide le panier sans rajouter les quantités dans les produits
            if(!is_null($request->request->get('acheter'))){
                $em->remove($user->getPaniers());
                $em->flush();
            }
            //cas où l'on vide le panier en rajoutant les quantités dans les produits
            elseif(!is_null($request->request->get('vider'))){
                foreach($user->getPaniers() as $panier){
                    $produit = $panier->getProduit();
                    $produit->setQuantite($produit->getQuantite()+$panier->getQuantite());
                    $em->remove($panier);
                    $em->flush();
                }
            }
            else {
                foreach ($request->request->all() as $key => $value) {
                    /** @var Panier $panier */
                    $panier = $panierRepository->find($key);
                    $produit = $panier->getProduit();
                    $produit->setQuantite($produit->getQuantite() + $panier->getQuantite());
                    $em->remove($panier);
                    $em->flush();
                }
                $this->addFlash('info', 'vous avez retiré un article de votre panier');

            }
            return $this->redirectToRoute('panier_list');
        }
        else throw new NotFoundHttpException("Vous devez être un client");

    }

    // - Liste les paniers de l'utilisateur actuel -
    /**
     * @Route ("list", name ="panier_list")
     */
    public function cartListAction() : Response {
        if(!$this->isClient()){
            throw new NotFoundHttpException("Vous n'êtes pas un client, vous ne n'avez pas de panier ");
        }
        return $this->render('panier.html.twig', ['user'=>$this->getCurrentUser()]);
    }


    // - Magasin (Affichage + Achat produits) -
    /**
     * @Route (name="produit_addcart")
     * @param Request $request
     * @return Response
     */

    public function addCartAction(Request $request) : Response {
        if($this->isClient()){
            /** @var Utilisateur $user */
            $user = $this->getCurrentUser();

            $em = $this->getDoctrine()->getManager();
            $produitRepository = $this->getRep('App:Produit');
            $panierRepository = $this->getRep('App:Panier');


            foreach ($request->request->all() as $key => $value)
            {
                /** @var Produit $product */
                $product = $produitRepository->find($key);

                if(!is_null($product) && $value>0 && $product->getQuantite()>=$value){

                    $product->setQuantite($product->getQuantite()-$value);
                    /** @var Panier $panier */
                    $paniers = $panierRepository->findBy(array('utilisateur' => $user, 'produit' => $product));
                    if(empty($paniers)){
                        $panier = new Panier();
                        $panier->setProduit($product)
                            ->setQuantite($value)
                            ->setUtilisateur($user);
                    }
                    else{
                        $panier =$paniers[0]; // il y a qu'un seul panier
                        $panier->setQuantite($panier->getQuantite()+$value);
                    }
                    $em->persist($panier);
                    $em->flush();
                }
            }
        } else throw new NotFoundHttpException('Vous devez être client');

        $this->addFlash('info',"Ajout effectué");
        return $this->redirectToRoute('panier_list');
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu