<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\Selectable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route ("create", name="createProductAction")
     * @param Request $request
     * @return Response
     */

    public function createProductAction(Request $request): Response
    {
        $user = $this->getCurrentUser();
        if(is_null($user) || !$user->isAdmin()){
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

            dump($product);

            $this->addFlash('info', 'Le produit a bien été ajouté');

            return $this->redirectToRoute('produit_liste');
        }

        if ($form->isSubmitted()) $this->addFlash('info', 'échec de l\'ajout');

        $args = array('product_form' => $form->createView());

        return $this->render('ajoutProduit.html.twig', $args);
    }


    /**
     * @Route ("delete/{id}", name="deleteProductAction")
     * @return Response
     */

    public function deleteProductAction($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productRepository = $em->getRepository('App:Produit');
        $panierRepository = $em->getRepository('App:Panier');

        /** @var Produit $product **/
        $product = $productRepository->find($id);

        if (!is_null($product)) {

            $this->addFlash('info', 'Le produit ' . $product->getLibelle() . ' a bien été supprimé');

            $paniers = $panierRepository->findBy(['produit' => $product]);

            $em->remove($paniers);
            $em->remove($product);
            $em->flush();

            dump($product);

        }

        else {
            $this->addFlash('info', 'Le produit n\'existe pas ou plus');
        }

        return $this->redirectToRoute('produit_liste');
    }


    // - Magasin (Affichage + Achat produits) -
    /**
     * @Route ("shop", name="shopProductsAction")
     * @return Response
     */

    public function shopProductsAction() : Response {

        $em = $this->getDoctrine()->getManager();
        $panierRepository = $this->getRep('App:Panier');

        $products = $this->getRep('App:Produit')->findAll();
        $args = array('products' => $products);

        return $this->render('magasin.html.twig', $args);
    }

    // - Magasin (Affichage + Achat produits) -
    /**
     * @Route (name="shopRequestAction")
     * @param Request $request
     * @return Response
     */

    public function shopRequestAction(Request $request) : Response {

        $em = $this->getDoctrine()->getManager();
        $produitRepository = $this->getRep('App:Produit');

        /** @var Utilisateur $user */
        $user = $this->getCurrentUser();

        $products = $request->request->all();
        dump($products);
        foreach ($products as $key => $value)
        {
            /** @var Produit $product */
            $product = $produitRepository->find($key);

            if(!is_null($product) && $value>0 && $product->getQuantite()>=$value){

                $product->setQuantite($product->getQuantite()-$value);

                $panier = new Panier();
                $panier->setProduit($product)->setQuantite($value)->setUtilisateur($user);
                $em->persist($panier);
                $em->flush();
                dump($panier);
            }
        }
        $this->addFlash('info',"Ajout effectué");
        return $this->redirectToRoute('cartListAction');
    }

}
