<?php

namespace App\Entity;

use App\Repository\DomaineTacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomaineTacheRepository::class)
 */
class DomaineTache
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=90)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=TacheSemaine::class, mappedBy="domaineTache", orphanRemoval=true)
     */
    private $tacheSemaines;

    /**
     * @ORM\OneToMany(targetEntity=TacheSemaine::class, mappedBy="domaineTache", orphanRemoval=true)
     */
    private $yes;

    public function __construct()
    {
        $this->tacheSemaines = new ArrayCollection();
        $this->yes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|TacheSemaine[]
     */
    public function getTacheSemaines(): Collection
    {
        return $this->tacheSemaines;
    }

    public function addTacheSemaine(TacheSemaine $tacheSemaine): self
    {
        if (!$this->tacheSemaines->contains($tacheSemaine)) {
            $this->tacheSemaines[] = $tacheSemaine;
            $tacheSemaine->setDomaineTache($this);
        }

        return $this;
    }

    public function removeTacheSemaine(TacheSemaine $tacheSemaine): self
    {
        if ($this->tacheSemaines->removeElement($tacheSemaine)) {
            // set the owning side to null (unless already changed)
            if ($tacheSemaine->getDomaineTache() === $this) {
                $tacheSemaine->setDomaineTache(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TacheSemaine[]
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(TacheSemaine $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setDomaineTache($this);
        }

        return $this;
    }

    public function removeYe(TacheSemaine $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getDomaineTache() === $this) {
                $ye->setDomaineTache(null);
            }
        }

        return $this;
    }

   
}
