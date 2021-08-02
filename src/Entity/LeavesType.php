<?php

namespace App\Entity;

use App\Repository\LeavesTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeavesTypeRepository::class)
 */
class LeavesType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ldays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lstatus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    /**
     * @ORM\Column(type="integer")
     */
    private $lchance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lrem;

    /**
     * @ORM\ManyToMany(targetEntity=Leaves::class, mappedBy="updatetype")
     */
    private $updateleaves;

    public function __construct()
    {
        $this->updateleaves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getLdays(): ?int
    {
        return $this->ldays;
    }

    public function setLdays(int $ldays): self
    {
        $this->ldays = $ldays;

        return $this;
    }

    public function getLstatus(): ?string
    {
        return $this->lstatus;
    }

    public function setLstatus(?string $lstatus): self
    {
        $this->lstatus = $lstatus;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLchance(): ?int
    {
        return $this->lchance;
    }

    public function setLchance(int $lchance): self
    {
        $this->lchance = $lchance;

        return $this;
    }

    public function getLrem(): ?int
    {
        return $this->lrem;
    }

    public function setLrem(?int $lrem): self
    {
        $this->lrem = $lrem;

        return $this;
    }

    /**
     * @return Collection|Leaves[]
     */
    public function getUpdateleaves(): Collection
    {
        return $this->updateleaves;
    }

    public function addUpdateleafe(Leaves $updateleafe): self
    {
        if (!$this->updateleaves->contains($updateleafe)) {
            $this->updateleaves[] = $updateleafe;
            $updateleafe->addUpdatetype($this);
        }

        return $this;
    }

    public function removeUpdateleafe(Leaves $updateleafe): self
    {
        if ($this->updateleaves->removeElement($updateleafe)) {
            $updateleafe->removeUpdatetype($this);
        }

        return $this;
    }



}
