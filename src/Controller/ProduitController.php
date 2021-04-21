<?php

namespace App\Controller;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;

/**
 * Class ProduitController
 * @package App\Controller
 * @Route("product/")
 */
class ProduitController extends MyAbstractController
{

    /**
     * @Route ("create", name="produit_create")
     * @param Request $request
     * @return Response
     */

    public function createProductAction(Request $request): Response
    {
        $user = $this->getCurrentUser();
        if(is_null($user) || !$user->getIsAdmin()){
            throw new NotFoundHttpException("Vous devez être administrateur pour ajouter un produit dans le magasin");
        }

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProduitType::class);
        $form->add('send', SubmitType::class, ['label' => 'Création d\' un produit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Produit $product */
            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            $this->addFlash('info', 'Le produit a bien été ajouté');

            return $this->redirectToRoute('accueil');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('product_form' => $form->createView());

        return $this->render('ajoutProduit.html.twig', $args);
    }

    // - Magasin (Affichage + Achat produits) -
    /**
     * @Route ("shop", name="produit_shop")
     * @return Response
     */

    public function shopProductsAction() : Response {

        $em = $this->getDoctrine()->getManager();
        $panierRepository = $this->getRep('App:Panier');

        $products = $this->getRep('App:Produit')->findAll();
        $args = array('products' => $products);

        return $this->render('magasin.html.twig', $args);
    }

}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu
