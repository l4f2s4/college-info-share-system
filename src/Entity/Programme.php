<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgrammeRepository::class)
 */
class Programme
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
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="deptprogramme")
     * @ORM\JoinColumn(nullable=true)
     */
    private $programmedept;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="programmecourse")
     */
    private $courseassigned;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pshortname;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="studentpro")
     */
    private $studentprogramme;

    public function __construct()
    {
        $this->courseassigned = new ArrayCollection();
        $this->studentprogramme = new ArrayCollection();
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

    public function getProgrammedept(): ?Department
    {
        return $this->programmedept;
    }

    public function setProgrammedept(?Department $programmedept): self
    {
        $this->programmedept = $programmedept;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourseassigned(): Collection
    {
        return $this->courseassigned;
    }

    public function addCourseassigned(Course $courseassigned): self
    {
        if (!$this->courseassigned->contains($courseassigned)) {
            $this->courseassigned[] = $courseassigned;
            $courseassigned->addProgrammecourse($this);
        }

        return $this;
    }

    public function removeCourseassigned(Course $courseassigned): self
    {
        if ($this->courseassigned->removeElement($courseassigned)) {
            $courseassigned->removeProgrammecourse($this);
        }

        return $this;
    }

    public function getPshortname(): ?string
    {
        return $this->pshortname;
    }

    public function setPshortname(string $pshortname): self
    {
        $this->pshortname = $pshortname;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudentprogramme(): Collection
    {
        return $this->studentprogramme;
    }

    public function addStudentprogramme(Student $studentprogramme): self
    {
        if (!$this->studentprogramme->contains($studentprogramme)) {
            $this->studentprogramme[] = $studentprogramme;
            $studentprogramme->setStudentpro($this);
        }

        return $this;
    }

    public function removeStudentprogramme(Student $studentprogramme): self
    {
        if ($this->studentprogramme->removeElement($studentprogramme)) {
            // set the owning side to null (unless already changed)
            if ($studentprogramme->getStudentpro() === $this) {
                $studentprogramme->setStudentpro(null);
            }
        }

        return $this;
    }
}
