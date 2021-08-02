<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
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
     * @ORM\Column(type="string", length=10)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $citizenship;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $regno;

    /**
     * @ORM\Column(type="integer")
     */
    private $phoneno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $YoS;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="coursestudent")
     */
    private $studentInvolved;

    /**
     * @ORM\ManyToOne(targetEntity=Programme::class, inversedBy="studentprogramme")
     */
    private $studentpro;


    public function __construct()
    {
        $this->department = new ArrayCollection();
        $this->studentInvolved = new ArrayCollection();
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCitizenship(): ?string
    {
        return $this->citizenship;
    }

    public function setCitizenship(string $citizenship): self
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    public function getRegno(): ?string
    {
        return $this->regno;
    }

    public function setRegno(string $regno): self
    {
        $this->regno = $regno;

        return $this;
    }

    public function getPhoneno(): ?int
    {
        return $this->phoneno;
    }

    public function setPhoneno(int $phoneno): self
    {
        $this->phoneno = $phoneno;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getYoS(): ?string
    {
        return $this->YoS;
    }

    public function setYoS(string $YoS): self
    {
        $this->YoS = $YoS;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getStudentInvolved(): Collection
    {
        return $this->studentInvolved;
    }

    public function addStudentInvolved(Course $studentInvolved): self
    {
        if (!$this->studentInvolved->contains($studentInvolved)) {
            $this->studentInvolved[] = $studentInvolved;
            $studentInvolved->addCoursestudent($this);
        }

        return $this;
    }

    public function removeStudentInvolved(Course $studentInvolved): self
    {
        if ($this->studentInvolved->removeElement($studentInvolved)) {
            $studentInvolved->removeCoursestudent($this);
        }

        return $this;
    }

    public function getStudentpro(): ?Programme
    {
        return $this->studentpro;
    }

    public function setStudentpro(?Programme $studentpro): self
    {
        $this->studentpro = $studentpro;

        return $this;
    }
}
