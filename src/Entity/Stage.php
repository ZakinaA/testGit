<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
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
    private $nomEntreprise;

    /**
     * @ORM\Column(type="string", length=14)
     *
     * @Assert\Length(min = 14, minMessage = "Le Siret doit comporter au minimum 14 caractères")
     * @Assert\Length(max = 14, maxMessage = "Le Siret doit comporter au maximum 14 caractères")
     */
     
    private $siret;

    /**
     * @ORM\Column(type="string", length=5)
     *   
     * @Assert\NotBlank()
     * 
     * @Assert\Length(
     * min = 5,
     * max = 5,
     * minMessage = "Le code NAF doit comporter au minimum 5 caractères",
     * maxMessage = "Le code NAF doit comporter au maximum 5 caractères"
     * )
     */
    private $codeNaf;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $numRue;

    /**
     * @ORM\Column(type="string", length=5)
     *    
     * @Assert\NotBlank()
     * 
     * @Assert\Length(
     * min = 5,
     * max = 5,
     * minMessage = "Le code postal doit comporter au minimum 5 caractères",
     * maxMessage = "Le code postal doit comporter au maximum 5 caractères"
     * )
     * 
     */
    private $copos;


    /**
     * @ORM\Column(type="string", length=60)
     */
    private $rue;


    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomTuteur;

    /**
     * @ORM\Column(type="string", length=15)
     *    
     * @Assert\NotBlank()
     * 
     * @Assert\Length(
     * min = 10,
     * max = 10,
     * minMessage = "Le téléphone doit comporter au minimum 10 caractères",
     * maxMessage = "Le téléphone doit comporter au maximum 10 caractères"
     * )
     */
    private $telTuteur;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\Email(
     *  message = "L'adresse mail renseignée n'est pas une adresse mail valide"
     * )
     */
    private $mailTuteur;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sujet;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $service;

    /**
     * @ORM\Column(type="date")
     * 
     * @Assert\Expression(
     *  "this.getDateDebut() < this.getDateFin()",
     * message = "La date de début ne peut pas être supérieure à la date de fin"
     * ) 
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     *     
     * @Assert\Expression(
     *      "this.getDateDebut() < this.getDateFin()",
     *      message = "La date de fin ne peut pas être antérieure à la date de début"
     * )
     */
    private $dateFin;

     /**
     * @ORM\Column(type="integer")
     *    
     * @Assert\NotBlank()
     * 
     * @Assert\Range(
     * min = 3,
     * max = 8,
     * minMessage = "La durée du stage doit être d'au moins de 3 semaines",
     * maxMessage = "La durée du stage ne peut pas dépasser 8 semaines"
     * )
     */
    private $duree;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horLun;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horMar;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horMer;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horJeu;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horVen;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $horSam;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="stages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="stages")
     */
    private $enseignant;

    /**
     * @ORM\OneToMany(targetEntity=SemaineStage::class, mappedBy="stage")
     */
    private $semaineStages;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomDirecteur;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $telDirecteur;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $mailDirecteur;

    public function __construct()
    {
        $this->semaineStages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCodeNaf(): ?string
    {
        return $this->codeNaf;
    }

    public function setCodeNaf(string $codeNaf): self
    {
        $this->codeNaf = $codeNaf;

        return $this;
    }

    public function getNumRue(): ?string
    {
        return $this->numRue;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setNumRue(string $numRue): self
    {
        $this->numRue = $numRue;

        return $this;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }
    public function getCopos(): ?string
    {
        return $this->copos;
    }

    public function setCopos(string $copos): self
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

    public function getNomTuteur(): ?string
    {
        return $this->nomTuteur;
    }

    public function setNomTuteur(string $nomTuteur): self
    {
        $this->nomTuteur = $nomTuteur;

        return $this;
    }

    public function getTelTuteur(): ?string
    {
        return $this->telTuteur;
    }

    public function setTelTuteur(string $telTuteur): self
    {
        $this->telTuteur = $telTuteur;

        return $this;
    }

    public function getMailTuteur(): ?string
    {
        return $this->mailTuteur;
    }

    public function setMailTuteur(string $mailTuteur): self
    {
        $this->mailTuteur = $mailTuteur;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getHorLun(): ?string
    {
        return $this->horLun;
    }

    public function setHorLun(string $horLun): self
    {
        $this->horLun = $horLun;

        return $this;
    }

    public function getHorMar(): ?string
    {
        return $this->horMar;
    }

    public function setHorMar(?string $horMar): self
    {
        $this->horMar = $horMar;

        return $this;
    }

    public function getHorMer(): ?string
    {
        return $this->horMer;
    }

    public function setHorMer(?string $horMer): self
    {
        $this->horMer = $horMer;

        return $this;
    }

    public function getHorJeu(): ?string
    {
        return $this->horJeu;
    }

    public function setHorJeu(?string $horJeu): self
    {
        $this->horJeu = $horJeu;

        return $this;
    }

    public function getHorVen(): ?string
    {
        return $this->horVen;
    }

    public function setHorVen(?string $horVen): self
    {
        $this->horVen = $horVen;

        return $this;
    }

    public function getHorSam(): ?string
    {
        return $this->horSam;
    }

    public function setHorSam(?string $horSam): self
    {
        $this->horSam = $horSam;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection|SemaineStage[]
     */
    public function getSemaineStages(): Collection
    {
        return $this->semaineStages;
    }

    public function addSemaineStage(SemaineStage $semaineStage): self
    {
        if (!$this->semaineStages->contains($semaineStage)) {
            $this->semaineStages[] = $semaineStage;
            $semaineStage->setStage($this);
        }

        return $this;
    }

    public function removeSemaineStage(SemaineStage $semaineStage): self
    {
        if ($this->semaineStages->removeElement($semaineStage)) {
            // set the owning side to null (unless already changed)
            if ($semaineStage->getStage() === $this) {
                $semaineStage->setStage(null);
            }
        }

        return $this;
    }

    public function getNomDirecteur(): ?string
    {
        return $this->nomDirecteur;
    }

    public function setNomDirecteur(string $nomDirecteur): self
    {
        $this->nomDirecteur = $nomDirecteur;

        return $this;
    }

    public function getTelDirecteur(): ?string
    {
        return $this->telDirecteur;
    }

    public function setTelDirecteur(?string $telDirecteur): self
    {
        $this->telDirecteur = $telDirecteur;

        return $this;
    }

    public function getMailDirecteur(): ?string
    {
        return $this->mailDirecteur;
    }

    public function setMailDirecteur(string $mailDirecteur): self
    {
        $this->mailDirecteur = $mailDirecteur;

        return $this;
    }   

}
