<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @ORM\Table(name="im2021_utilisateurs",options={"comment"="Table des utilisateurs du site"})
 */
class Utilisateur
{
    /**
     * Utilisateur constructor
     */
    public function __construct()
    {
        $this->nom = null;
        $this->prenom = null;
        $this->anniversaire = null;
        $this->isadmin = false;
        $this->paniers = new ArrayCollection();
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="pk")
     */
    private $id;

    /**
     * @ORM\Column(
     *     type="string", length=30, name="identifiant",
     *     options={"comment" = "sert de login (doit être unique)"})
     * @Assert\NotBlank(message = "Le champ identifiant ne peut pas être vide")
     * @Assert\Length(max="32")
     */
    private $identifiant;

    /**
     * @ORM\Column(
     *     type="string", length=64, name="motdepasse",
     *     options={"comment" = "mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer"})
     * @Assert\NotBlank(message = "Le champ mot de passe ne peut pas être vide")
     * @Assert\Length(max="64")
     */
    private $motDePasse;

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default"=null})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default"=null})
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default"=null})
     * @Assert\Range(
     *     min="1920", minMessage="êtes vous toujours vivant ?",
     *     max="2009", maxMessage="Vous savez lire/parler ?")
     */
    private $aniversaire;

    /**
     * @ORM\Column(type="boolean", name="isadmin", options={"default"=false , "comment"="type booléen"})
     */
    private $isAdmin;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="utilisateur")
     */
    private $paniers;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAniversaire(): ?\DateTimeInterface
    {
        return $this->aniversaire;
    }

    public function setAniversaire(?\DateTimeInterface $aniversaire): self
    {
        $this->aniversaire = $aniversaire;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUtilisateur($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUtilisateur() === $this) {
                $panier->setUtilisateur(null);
            }
        }

        return $this;
    }
}
