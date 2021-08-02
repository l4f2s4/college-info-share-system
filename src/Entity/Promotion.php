<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $pid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $promofrom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $promoto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $promodate;

    public function __construct()
    {
        $this->pid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getPid(): Collection
    {
        return $this->pid;
    }

    public function addPid(User $pid): self
    {
        if (!$this->pid->contains($pid)) {
            $this->pid[] = $pid;
        }

        return $this;
    }

    public function removePid(User $pid): self
    {
        $this->pid->removeElement($pid);

        return $this;
    }

    public function getPromofrom(): ?string
    {
        return $this->promofrom;
    }

    public function setPromofrom(string $promofrom): self
    {
        $this->promofrom = $promofrom;

        return $this;
    }

    public function getPromoto(): ?string
    {
        return $this->promoto;
    }

    public function setPromoto(string $promoto): self
    {
        $this->promoto = $promoto;

        return $this;
    }

    public function getPromodate(): ?string
    {
        return $this->promodate;
    }

    public function setPromodate(string $promodate): self
    {
        $this->promodate = $promodate;

        return $this;
    }
}
