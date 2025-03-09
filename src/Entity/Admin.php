<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    // Hash le mot de passe lors de la modification
    public function setPassword(string $password): static
    {
        // Si un mot de passe est fourni, on le hache avant de le stocker
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    // Vérifier si le mot de passe correspond au mot de passe haché
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
