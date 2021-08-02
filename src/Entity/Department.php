<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="programmedept", orphanRemoval=true)
     */
    private $deptprogramme;

    public function __construct()
    {
        $this->deptprogramme = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Programme[]
     */
    public function getDeptprogramme(): Collection
    {
        return $this->deptprogramme;
    }

    public function addDeptprogramme(Programme $deptprogramme): self
    {
        if (!$this->deptprogramme->contains($deptprogramme)) {
            $this->deptprogramme[] = $deptprogramme;
            $deptprogramme->setProgrammedept($this);
        }

        return $this;
    }

    public function removeDeptprogramme(Programme $deptprogramme): self
    {
        if ($this->deptprogramme->removeElement($deptprogramme)) {
            // set the owning side to null (unless already changed)
            if ($deptprogramme->getProgrammedept() === $this) {
                $deptprogramme->setProgrammedept(null);
            }
        }

        return $this;
    }
}
