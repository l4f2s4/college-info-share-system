<?php

namespace App\Entity;

use App\Repository\ThemsettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThemsettingRepository::class)
 */
class Themsetting
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $websitename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lightlogo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $favicon;

    public function getId(): ?int
    {
        return $this->id;
    }
   public function setId(int $id):self
   {
    $this->id= $id;
    return $this;
   }
    public function getWebsitename(): ?string
    {
        return $this->websitename;
    }

    public function setWebsitename(string $websitename): self
    {
        $this->websitename = $websitename;

        return $this;
    }

    public function getLightlogo()
    {
        return $this->lightlogo;
    }

    public function setLightlogo($lightlogo)
    {
        $this->lightlogo = $lightlogo;

        return $this;
    }

    public function getFavicon()
    {
        return $this->favicon;
    }

    public function setFavicon($favicon)
    {
        $this->favicon = $favicon;

        return $this;
    }
}
