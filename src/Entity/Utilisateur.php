<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @Table (name="im2021_utilisateurs")
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 *
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
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, options={"comment"="sert de login (doit être unique)"})
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=64, name="mot_de_passe",
     *      options={"comment"="mot de passe crypté : il faut une taille assez grande pour ne pas le tronquer"})
     */
    private $motdepasse;

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default" = null})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default" = null})
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true, options={"default" = null})
     */
    private $anniversaire;

    /**
     * @ORM\Column(type="boolean", name="est_admin",
     *     options={"comment"="type booléen", "default" = false })
     */
    private $isadmin;

    /**
     * @ORM\OneToMany(targetEntity=Panier::class, mappedBy="idUtilisateur")
     * @ORM\Column(nullable=true, options={"default" = null})
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

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): self
    {
        $this->motdepasse = $motdepasse;

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

    public function getAnniversaire(): ?\DateTimeInterface
    {
        return $this->anniversaire;
    }

    public function setAnniversaire(?\DateTimeInterface $anniversaire): self
    {
        $this->anniversaire = $anniversaire;

        return $this;
    }

    public function getIsadmin(): ?bool
    {
        return $this->isadmin;
    }

    public function setIsadmin(bool $isadmin): self
    {
        $this->isadmin = $isadmin;

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
            $panier->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getIdUtilisateur() === $this) {
                $panier->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
