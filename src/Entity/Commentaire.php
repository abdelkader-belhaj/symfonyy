<?php
// src/Entity/Commentaire.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\Column(type: 'integer')]
    private int $note;

   

    #[ORM\ManyToOne(targetEntity: Activity::class, inversedBy: "commentaires", cascade: ["remove"])]
#[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
private ?Activity $activity = null;   // je utiliser la suppresion avec la methode en cascade qui je (SGBD) lorsque je supprimer la table mere l'efnate supprimer directement 


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;
        return $this;
    }
}
