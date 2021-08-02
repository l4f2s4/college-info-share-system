<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
    private $coursename;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="courseinstructor")
     */
    private $instructor;

    /**
     * @ORM\ManyToMany(targetEntity=Student::class, inversedBy="studentInvolved")
     */
    private $coursestudent;

    /**
     * @ORM\Column(type="integer")
     */
    private $credits;

    /**
     * @ORM\ManyToMany(targetEntity=Programme::class, inversedBy="courseassigned")
     */
    private $programmecourse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cshortname;

    public function __construct()
    {
        $this->instructor = new ArrayCollection();
        $this->coursestudent = new ArrayCollection();
        $this->programmecourse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoursename(): ?string
    {
        return $this->coursename;
    }

    public function setCoursename(string $coursename): self
    {
        $this->coursename = $coursename;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInstructor(): Collection
    {
        return $this->instructor;
    }

    public function addInstructor(User $instructor): self
    {
        if (!$this->instructor->contains($instructor)) {
            $this->instructor[] = $instructor;
        }

        return $this;
    }

    public function removeInstructor(User $instructor): self
    {
        $this->instructor->removeElement($instructor);

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getCoursestudent(): Collection
    {
        return $this->coursestudent;
    }

    public function addCoursestudent(Student $coursestudent): self
    {
        if (!$this->coursestudent->contains($coursestudent)) {
            $this->coursestudent[] = $coursestudent;
        }

        return $this;
    }

    public function removeCoursestudent(Student $coursestudent): self
    {
        $this->coursestudent->removeElement($coursestudent);

        return $this;
    }

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * @return Collection|Programme[]
     */
    public function getProgrammecourse(): Collection
    {
        return $this->programmecourse;
    }

    public function addProgrammecourse(Programme $programmecourse): self
    {
        if (!$this->programmecourse->contains($programmecourse)) {
            $this->programmecourse[] = $programmecourse;
        }

        return $this;
    }

    public function removeProgrammecourse(Programme $programmecourse): self
    {
        $this->programmecourse->removeElement($programmecourse);

        return $this;
    }

    public function getCshortname(): ?string
    {
        return $this->cshortname;
    }

    public function setCshortname(string $cshortname): self
    {
        $this->cshortname = $cshortname;

        return $this;
    }
}
