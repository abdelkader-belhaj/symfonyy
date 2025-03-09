<?php
// src/Entity/Activity.php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;
    

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageFileName = null;

 
   

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isApproved = false;
    
    

#[ORM\OneToMany(mappedBy: 'activity', targetEntity: Commentaire::class, orphanRemoval: true, cascade: ["remove"])]
    private Collection $commentaires;

  
#[ORM\Column(length: 255, nullable: true)]
private ?string $typesActivity = null;


#[ORM\Column(type: 'datetime')]
private \DateTimeInterface $createdAt;



    public function __construct()
    {
        $this->createdAt = new \DateTime(); // Date actuelle

        $this->commentaires = new ArrayCollection();
        
    }

    // --- Getters et Setters ---


    

    public function getTypesActivity(): ?string
    {
        return $this->typesActivity;
    }

    public function setTypesActivity(?string $typesActivity): static
    {
        $this->typesActivity = $typesActivity;

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;
        return $this;
    }


    /**
     * Déplace le fichier uploadé dans le dossier spécifié et met à jour le nom du fichier.
     *
     * @param string $uploadDir Le chemin absolu vers le dossier de destination (ex: public/uploads)
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function uploadImage(string $uploadDir, \Symfony\Component\HttpFoundation\File\UploadedFile $file): void
    {
        $newFilename = uniqid() . '.' . $file->guessExtension();
        // Déplacement du fichier dans le dossier $uploadDir
        $file->move($uploadDir, $newFilename);
        $this->setImageFileName($newFilename);
    }

   

   

    public function getCommentaires(): Collection
{
    return $this->commentaires;
}



public function addCommentaire(Commentaire $commentaire): self
{
    if (!$this->commentaires->contains($commentaire)) {
        $this->commentaires->add($commentaire);
        $commentaire->setActivity($this);
    }

    return $this;
}

public function removeCommentaire(Commentaire $commentaire): self
{
    if ($this->commentaires->removeElement($commentaire)) {
        // Set the owning side to null (unless already changed)
        if ($commentaire->getActivity() === $this) {
            $commentaire->setActivity(null);
        }
    }

    return $this;
}



public function isApproved(): bool
{
    return $this->isApproved;
}

public function setApproved(bool $isApproved): self
{
    $this->isApproved = $isApproved;
    return $this;
}



public function getCreatedAt(): \DateTimeInterface
{
    return $this->createdAt;
}


}
