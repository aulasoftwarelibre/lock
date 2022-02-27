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
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[UniqueEntity(fields: ['site', 'account'])]
#[ORM\Entity(repositoryClass: SecretRepository::class)]
#[Vich\Uploadable]
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

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt;


    /**
     * @Vich\UploadableField(
     *     mapping="avatars",
     *     fileNameProperty="image.name",
     *     size="image.size",
     *     mimeType="image.mimeType",
     *     originalName="image.originalName"
     * )
     */
    #[Vich\UploadableField(
        mapping: 'codes',
        fileNameProperty: 'image.name',
        size: 'image.size',
        mimeType: 'image.mimeType',
        originalName: 'image.originalName',
    )]
    private File|UploadedFile|null $imageFile = null;

    #[ORM\Embedded(class: 'Vich\UploaderBundle\Entity\File')]
    private EmbeddedFile $image;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->image         = new EmbeddedFile();
        $this->updatedAt     = new DateTimeImmutable();
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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     */
    public function setImageFile(?File $image = null): void
    {
        $this->imageFile = $image;

        if ($image === null) {
            return;
        }

        // It is required that at least one field changes if you are using doctrine
        // otherwise the event listeners won't be called and the file is lost
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(EmbeddedFile $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): EmbeddedFile
    {
        return $this->image;
    }
}
