<?php

namespace App\Entity;

use App\Repository\TacheSemaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TacheSemaineRepository::class)
 */
class TacheSemaine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=DomaineTache::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $domaineTache;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity=Jour::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $jour;

    
    private $tacheSemaines;

    /**
     * @ORM\ManyToOne(targetEntity=SemaineStage::class, inversedBy="tacheSemaines", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $semaineStage;

    public function __construct()
    {
        $this->tacheSemaines = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDomaineTache(): ?DomaineTache
    {
        return $this->domaineTache;
    }

    public function setDomaineTache(?DomaineTache $domaineTache): self
    {
        $this->domaineTache = $domaineTache;

        return $this;
    }

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(?float $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getJour(): ?Jour
    {
        return $this->jour;
    }

    public function setJour(?Jour $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getSemaineStage(): ?SemaineStage
    {
        return $this->semaineStage;
    }

    public function setSemaineStage(?SemaineStage $semaineStage): self
    {
        $this->semaineStage = $semaineStage;

        return $this;
    }

   

  
}
