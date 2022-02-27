<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SecretRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['site', 'account'])]
#[ORM\Entity(repositoryClass: SecretRepository::class)]
 *
class Secret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $site = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $account = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $password = null;

    /** @var Collection<int, OrganizationUnit> */
    #[ORM\ManyToMany(targetEntity: OrganizationUnit::class)]
    private Collection $organizations;
    /**
     * @ORM\ManyToMany(targetEntity=OrganizationUnit::class)
     *
     * @var Collection<int, OrganizationUnit>
     */
    private Collection $organizations;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, OrganizationUnit>
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(OrganizationUnit $organization): self
    {
        if (! $this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
        }

        return $this;
    }

    public function removeOrganization(OrganizationUnit $organization): self
    {
        $this->organizations->removeElement($organization);

        return $this;
    }
}
