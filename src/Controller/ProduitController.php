<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function indexAction(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    /**
     * @Route ("produit/ajout", name="produit_ajout")
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

            return $this->render('templates/main.html.twig');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('product_form' => $form->createView());

        return $this->render('produit/ajoutProduit.html.twig', $args);
    }
}
