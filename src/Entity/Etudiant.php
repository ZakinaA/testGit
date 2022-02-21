<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtudiantRepository::class)
 */
class Etudiant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     */
    private $dateNaiss;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $numRue;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $rue;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $copos;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cheminPhoto;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="etudiants")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=Specialite::class, inversedBy="etudiants")
     */
    private $specialite;

    /**
     * @ORM\OneToMany(targetEntity=RP::class, mappedBy="etudiant")
     */
    private $rPs;

    /**
     * @ORM\OneToMany(targetEntity=Stage::class, mappedBy="etudiant", orphanRemoval=true)
     */
    private $stages;

    public function __construct()
    {
        $this->rPs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(?\DateTimeInterface $dateNaiss): self
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNumRue(): ?string
    {
        return $this->numRue;
    }

    public function setNumRue(?string $numRue): self
    {
        $this->numRue = $numRue;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCopos(): ?string
    {
        return $this->copos;
    }

    public function setCopos(?string $copos): self
    {
        $this->copos = $copos;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCheminPhoto(): ?string
    {
        return $this->cheminPhoto;
    }

    public function setCheminPhoto(?string $cheminPhoto): self
    {
        $this->cheminPhoto = $cheminPhoto;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection|RP[]
     */
    public function getRPs(): Collection
    {
        return $this->rPs;
    }

    public function addRP(RP $rP): self
    {
        if (!$this->rPs->contains($rP)) {
            $this->rPs[] = $rP;
            $rP->setEtudiant($this);
        }

        return $this;
    }

    public function removeRP(RP $rP): self
    {
        if ($this->rPs->removeElement($rP)) {
            // set the owning side to null (unless already changed)
            if ($rP->getEtudiant() === $this) {
                $rP->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getNbActivites()
    {
        $lesActEnreg = array();
        foreach ($this->rPs as $rp) {
            foreach ($rp->getActivites() as $rpAct) {

                if (array_key_exists($rpAct->getActivite()->getId(), $lesActEnreg)) {
                    $lesActEnreg[$rpAct->getActivite()->getId()] = $lesActEnreg[$rpAct->getActivite()->getId()] + 1 ;
                }
                else
                {
                    $lesActEnreg[$rpAct->getActivite()->getId()] = 1;
                }
            }
           
        }
        return count($lesActEnreg);
    }


     /**
     * @return Collection|Stage[]
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages[] = $stage;
            $stage->setEtudiant($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getEtudiant() === $this) {
                $stage->setEtudiant(null);
            }
        }

        return $this;
    }


    public static function getNbActivitesDifferentes()
    {
        $tabAct = array();
        $i=0;

        foreach ($this->rPs as $rp)
        {
            foreach ($rp->getActivites() as $act)
            {
                if (array_key_exists($act->getId(), $tabAct))
                {

                }
                else
                {
                    array_push($act->getId());
                   $i++;
                }

            }
        }
        return $i;
    }
    
}
