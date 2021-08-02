<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
        /**
     * Date/Time of the last activity
     *
     * @var \Datetime
     * @ORM\Column(name="last_activity_at", type="datetime", nullable=true)
     */
    protected $lastActivityAt;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $DoB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $DoM;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

     /**
     * @ORM\Column(type="integer")
     */
    private $phoneno;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deptcom;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Religion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maritalstatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\ManyToMany(targetEntity=Leaves::class, mappedBy="lemp")
     */
    private $lemployee;

    /**
    *
    *
    *
     * @ORM\Column(type="string",nullable=true)
     *
     *
     *
     */
    private $userimage;

    /**
     * @ORM\ManyToMany(targetEntity=Course::class, mappedBy="instructor")
     */
    private $courseinstructor;
    public function __construct()
    {
        $this->department = new ArrayCollection();
        $this->lemployee = new ArrayCollection();
        $this->courseinstructor = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

 /** Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */

    public function getRoles()
    {
      //  $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getDoB(): ?string
    {
        return $this->DoB;
    }

    public function setDoB(string $DoB): self
    {
        $this->DoB = $DoB;

        return $this;
    }

    public function getDoM(): ?string
    {
        return $this->DoM;
    }

    public function setDoM(string $DoM): self
    {
        $this->DoM = $DoM;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getDeptcom(): ?string
    {
        return $this->deptcom;
    }

    public function setDeptcom(?string $deptcom): self
    {
        $this->deptcom = $deptcom;

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

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getReligion(): ?string
    {
        return $this->Religion;
    }

    public function setReligion(?string $Religion): self
    {
        $this->Religion = $Religion;

        return $this;
    }

    public function getMaritalstatus(): ?string
    {
        return $this->maritalstatus;
    }

    public function setMaritalstatus(?string $maritalstatus): self
    {
        $this->maritalstatus = $maritalstatus;

        return $this;
    }
 public function getId(): ?int
                 {
                    return $this->id;
                  }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection|Leaves[]
     */
    public function getLemployee(): Collection
    {
        return $this->lemployee;
    }

    public function addLemployee(Leaves $lemployee): self
    {
        if (!$this->lemployee->contains($lemployee)) {
            $this->lemployee[] = $lemployee;
            $lemployee->addLemp($this);
        }

        return $this;
    }

    public function removeLemployee(Leaves $lemployee): self
    {
        if ($this->lemployee->removeElement($lemployee)) {
            $lemployee->removeLemp($this);
        }

        return $this;
    }

    public function getUserimage()
    {
        return $this->userimage;
    }

    public function setUserimage($userimage)
    {
        $this->userimage = $userimage;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCourseinstructor(): Collection
    {
        return $this->courseinstructor;
    }

    public function addCourseinstructor(Course $courseinstructor): self
    {
        if (!$this->courseinstructor->contains($courseinstructor)) {
            $this->courseinstructor[] = $courseinstructor;
            $courseinstructor->addInstructor($this);
        }

        return $this;
    }

    public function removeCourseinstructor(Course $courseinstructor): self
    {
        if ($this->courseinstructor->removeElement($courseinstructor)) {
            $courseinstructor->removeInstructor($this);
        }

        return $this;
    }
    /**
 * @param \Datetime $lastActivityAt
 */
public function setLastActivityAt($lastActivityAt)
{
    $this->lastActivityAt = $lastActivityAt;
}

/**
 * @return \Datetime
 */
public function getLastActivityAt()
{
    return $this->lastActivityAt;
}

/**
 * @return Bool Whether the user is active or not
 */
public function isActiveNow()
{
    // Delay during wich the user will be considered as still active
    $delay = new \DateTime('2 minutes ago');

    return ( $this->getLastActivityAt() > $delay );
}

}
