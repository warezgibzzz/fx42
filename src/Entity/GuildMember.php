<?php

namespace App\Entity;

use App\Repository\GuildMemberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuildMemberRepository::class)
 */
class GuildMember
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="guildMember", cascade={"persist", "remove"})
     */
    private $discordUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscordUser(): ?User
    {
        return $this->discordUser;
    }

    public function setDiscordUser(?User $discordUser): self
    {
        $this->discordUser = $discordUser;

        return $this;
    }
}
