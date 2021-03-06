<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="im2021_panier")
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{

    /**
     * Panier constructor
     */
    public function __construct()
    {
        $this->quantite=0;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $quantite;

    /**
     * @var Utilisateur
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="paniers")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="pk")
     */
    private $utilisateur;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="paniers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
}

//AUTEURS : Fréjoux Gaëtan && Niord Mathieu