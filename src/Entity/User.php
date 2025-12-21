<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

use function array_unique;

#[UniqueEntity(fields: ['username'])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, TwoFactorInterface, Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 180)]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string|null $username = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 180)]
    #[Assert\Email]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string|null $email = null;

    /** @var string[] */
    #[Assert\Choice(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], multiple: true)]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(name: 'googleAuthenticatorSecret', type: 'string', nullable: true)]
    private string|null $googleAuthenticatorSecret;

    #[ORM\Column(name: 'googleActivationSecret', type: 'string', nullable: true)]
    private string|null $googleActivationSecret;

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getId(): int|null
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

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /** @return string[] */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** @param string[] $roles */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /** @see UserInterface */
    public function getPassword(): void
    {
    }

    /** @see UserInterface */
    public function getSalt(): void
    {
    }

    /** @see UserInterface */
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

    public function isGoogleAuthenticatorActivated(): bool
    {
        return ! (bool) $this->googleActivationSecret;
    }

    public function getGoogleAuthenticatorSecret(): string|null
    {
        return $this->googleAuthenticatorSecret;
    }

    public function setGoogleAuthenticatorSecret(string $googleAuthenticatorSecret): self
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;

        return $this;
    }

    public function getGoogleActivationSecret(): string|null
    {
        return $this->googleActivationSecret;
    }

    public function setGoogleActivationSecret(string|null $googleActivationSecret): void
    {
        $this->googleActivationSecret = $googleActivationSecret;
    }

    public function __serialize(): array
    {
        $vars = get_object_vars($this);
        unset($vars['imageFile']);
        return $vars;
    }

    public function __unserialize(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
