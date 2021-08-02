<?php

namespace App\Entity;

use App\Repository\LeavesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeavesRepository::class)
 */
class Leaves
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lfrom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lto;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lreason;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="lemployee")
     */
    private $lemp;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $approvedby;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $approveddate;

    /**
     * @ORM\ManyToMany(targetEntity=LeavesType::class, inversedBy="updateleaves")
     */
    private $updatetype;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $approvedimage;



    public function __construct()
    {
        $this->lemp = new ArrayCollection();
        $this->updateleaves = new ArrayCollection();
        $this->updatetype = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLfrom(): ?\DateTime
    {
        return $this->lfrom;
    }

    public function setLfrom(\DateTime $lfrom): self
    {
        $this->lfrom = $lfrom;

        return $this;
    }

    public function getLto(): ?\DateTime
    {
        return $this->lto;
    }

    public function setLto(\DateTime $lto): self
    {
        $this->lto = $lto;

        return $this;
    }
    public function setStatus(String $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLreason(): ?string
    {
        return $this->lreason;
    }

    public function setLreason(string $lreason): self
    {
        $this->lreason = $lreason;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getLemp(): Collection
    {
        return $this->lemp;
    }

    public function addLemp(User $lemp): self
    {
        if (!$this->lemp->contains($lemp)) {
            $this->lemp[] = $lemp;
        }

        return $this;
    }

    public function removeLemp(User $lemp): self
    {
        $this->lemp->removeElement($lemp);

        return $this;
    }
    public function getApprovedby(): ?string
    {
        return $this->approvedby;
    }

    public function setApprovedby(?string $approvedby): self
    {
        $this->approvedby = $approvedby;

        return $this;
    }

    public function getApproveddate(): ?string
    {
        return $this->approveddate;
    }

    public function setApproveddate(string $approveddate): self
    {
        $this->approveddate = $approveddate;

        return $this;
    }

    /**
     * @return Collection|LeavesType[]
     */
    public function getUpdatetype(): Collection
    {
        return $this->updatetype;
    }

    public function addUpdatetype(LeavesType $updatetype): self
    {
        if (!$this->updatetype->contains($updatetype)) {
            $this->updatetype[] = $updatetype;
        }

        return $this;
    }

    public function removeUpdatetype(LeavesType $updatetype): self
    {
        $this->updatetype->removeElement($updatetype);

        return $this;
    }

    public function getApprovedimage(): ?string
    {
        return $this->approvedimage;
    }

    public function setApprovedimage(?string $approvedimage): self
    {
        $this->approvedimage = $approvedimage;

        return $this;
    }


}
