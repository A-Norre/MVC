<?php

namespace App\Entity;

use App\Repository\LossesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LossesRepository::class)]
class Losses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $loss = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoss(): ?int
    {
        return $this->loss;
    }

    public function setLoss(int $loss): self
    {
        $this->loss = $loss;

        return $this;
    }
}
