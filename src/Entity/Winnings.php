<?php

namespace App\Entity;

use App\Repository\WinningsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WinningsRepository::class)]
class Winnings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $profit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfit(): ?int
    {
        return $this->profit;
    }

    public function setProfit(int $profit): self
    {
        $this->profit = $profit;

        return $this;
    }
}
