<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyAbstractController extends AbstractController
{
    protected function getCurrentUser()
    {
        $em = $this->getDoctrine()->getManager();
        $userRep= $em->getRepository('App:Utilisateur');
        return $userRep->find($this->getParameter('id'));
    }

    protected function getNbProduct(): int
    {
        $em = $this->getDoctrine()->getManager();
        $userRep= $em->getRepository('App:Produit');
        return count($userRep->findAll());
    }

    protected function getRep($className)
    {
        return $this->getDoctrine()->getManager()->getRepository($className);
    }

}