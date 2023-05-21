<?php

namespace App\Entity;

use App\Repository\PlayerTransferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerTransferRepository::class)]
class PlayerTransfer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: "transfers")]
    #[ORM\JoinColumn(nullable: false)]

    private Player $player;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: "purchasedPlayers")]
    #[ORM\JoinColumn(nullable: false)]
    private Team $buyer;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn(nullable: false)]

    private Team $seller;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $transferDate;

    public function __construct(Player $player, Team $buyer, Team $seller)
    {
        $this->player = $player;
        $this->buyer = $buyer;
        $this->seller = $seller;
        $this->transferDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBuyer(): Team
    {
        return $this->buyer;
    }

    public function getSeller(): Team
    {
        return $this->seller;
    }

    public function getTransferDate(): \DateTimeInterface
    {
        return $this->transferDate;
    }
}
