<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use function array_unique;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @UniqueEntity(fields={"username"})
 */
class User implements UserInterface, TwoFactorInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=180)
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=180)
     * @Assert\Email()
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Choice({"ROLE_USER", "ROLE_ADMIN", "ROLE_SUPER_ADMIN"}, multiple=true)
     *
     * @var string[]
     */
    private array $roles = [];

    /** @ORM\Column(name="googleAuthenticatorSecret", type="string", nullable=true) */
    private ?string $googleAuthenticatorSecret;

    /** @ORM\Column(name="googleActivationSecret", type="string", nullable=true) */
    private ?string $googleActivationSecret;

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): void
    {
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): void
    {
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return (bool) $this->googleAuthenticatorSecret;
    }

    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->username;
    }

    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(string $googleAuthenticatorSecret): self
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;

        return $this;
    }

    public function getGoogleActivationSecret(): ?string
    {
        return $this->googleActivationSecret;
    }

    public function setGoogleActivationSecret(?string $googleActivationSecret): void
    {
        $this->googleActivationSecret = $googleActivationSecret;
    }
}
