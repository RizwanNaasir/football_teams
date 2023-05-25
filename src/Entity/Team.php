<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id = 0;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    private string $country;

    #[ORM\Column(type: "float")]
    private float|int $moneyBalance;

    #[ORM\OneToMany(mappedBy: "team", targetEntity: Player::class, cascade: ['persist'])]
    private Collection $players;
    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getMoneyBalance(): ?float
    {
        return $this->moneyBalance;
    }

    public function setMoneyBalance(float|int $moneyBalance): self
    {
        $this->moneyBalance = $moneyBalance;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * Buy a player by creating a player transfer record
     *
     * @param Player $player
     */
    public function buyPlayer(Player $player): void
    {
        if (!$this->players->contains($player)) {
            $player->setTeam($this);
            $this->players->add($player);
        }
    }

    /**
     * Sell a player by creating a player transfer record
     *
     * @param Player $player
     */
    public function sellPlayer(Player $player): void
    {
        if ($this->players->contains($player)) {
            $player->setTeam(null);
            $this->players->removeElement($player);
        }
    }
}
