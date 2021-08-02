<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
     * @ORM\ManyToMany(targetEntity=Department::class)
     */
    private $department;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $adduser;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deadline;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    public function __construct()
    {
        $this->department = new ArrayCollection();
        $this->adduser = new ArrayCollection();
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
     * @return Collection|Department[]
     */
    public function getDepartment(): Collection
    {
        return $this->department;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->department->contains($department)) {
            $this->department[] = $department;
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        $this->department->removeElement($department);

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAdduser(): Collection
    {
        return $this->adduser;
    }

    public function addAdduser(User $adduser): self
    {
        if (!$this->adduser->contains($adduser)) {
            $this->adduser[] = $adduser;
        }

        return $this;
    }

    public function removeAdduser(User $adduser): self
    {
        $this->adduser->removeElement($adduser);

        return $this;
    }
    public function getStartdate(): ?string
    {
        return $this->startdate;
    }

    public function setStartdate(string $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getDeadline(): ?string
    {
        return $this->deadline;
    }

    public function setDeadline(string $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

}
