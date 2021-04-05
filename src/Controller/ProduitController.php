<?php

namespace App\Controller;

use Doctrine\Common\Collections\Selectable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;

class ProduitController extends MyAbstractController
{

    /**
     * @Route("/produit", name="produit")
     */
    public function indexAction(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }


    /**
     * @Route ("product/add", name="produit_ajout")
     * @param Request $request
     * @return Response
     */

    public function createProductAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProduitType::class);
        $form->add('send', SubmitType::class, ['label' => 'Création d\' un produit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Produit $product */
            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            dump($product);

            $this->addFlash('info', 'Le produit a bien été ajouté');

            return $this->redirectToRoute('produit_liste');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('product_form' => $form->createView());

        return $this->render('ajoutProduit.html.twig', $args);
    }


    /**
     * @Route ("product/delete/{id}", name="produit_suppression")
     * @return Response
     */

    public function deleteProductAction($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('App:Produit');

        /** @var Produit $product **/
        $product = $productRepository->find($id);

        if (!is_null($product)) {

            $em->remove($product);
            $em->flush();

            dump($product);

            $this->addFlash('info', 'Le produit ' . $product->getLibelle() . ' a bien été supprimé');
        }

        else {
            $this->addFlash('info', 'Le produit n\'existe pas ou plus');
        }

        return $this->redirectToRoute('produit_liste');
    }

    /**
     * @Route ("product/list", name="produit_liste")
     * @return Response
     */

    public function listProductAction(): Response {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('App:Produit');
        $products = $productRepository->findAll();

        $args = array('products' => $products);

        return $this->render('listeProduits.html.twig', $args);
    }


    // - Magasin (Affichage + Achat produits) -

    /**
     * @Route ("/shop", name="produit_magasin")
     * @param Request $request
     * @return Response
     */

    public function shopAction(Request $request) : Response {

        $em = $this->getDoctrine()->getManager();
        $panierRepository = $this->getRep('App:Panier');

        $products = $this->getRep('App:Produit')->findAll();
        $args = array('products' => $products);

        return $this->render('magasin.html.twig', $args);
    }
}
