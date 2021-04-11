<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyAbstractController extends AbstractController
{

    protected function getNbProducts(): int
    {
        $em = $this->getDoctrine()->getManager();
        $userRep= $em->getRepository('App:Produit');
        return count($userRep->findAll());
    }

    protected function getRep($className)
    {
        return $this->getDoctrine()->getManager()->getRepository($className);
    }

    protected function getCurrentUser()
    {
        $em = $this->getDoctrine()->getManager();
        $userRep = $em->getRepository('App:Utilisateur');
        return $userRep->find($this->getParameter('id'));
    }

    protected function isGuest(): bool
    {
        return is_null($this->getCurrentUser());
    }

    protected function isClient(): bool
    {
        return !is_null($this->getCurrentUser()) && !$this->getCurrentUser()->getIsAdmin();
    }

    protected function isAdmin(): bool
    {
        return !is_null($this->getCurrentUser()) && $this->getCurrentUser()->getIsAdmin();
    }
}