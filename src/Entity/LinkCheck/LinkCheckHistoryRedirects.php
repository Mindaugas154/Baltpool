<?php

namespace App\Entity\LinkCheck;

use App\Repository\LinkCheckerHistoryRedirectsRepository;
use App\Repository\LinkCheckHistoryRedirectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkCheckHistoryRedirectsRepository::class)]
class LinkCheckHistoryRedirects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: LinkCheckHistory::class, inversedBy: 'id')]
    private ?LinkCheckHistory $id_link_history;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $times = null;

    public function __construct()
    {
        $this->id_link_history = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, LinkCheckHistory>
     */
    public function getOneToMany(): Collection
    {
        return $this->id_link_history;
    }

    public function addOneToMany(LinkCheckHistory $oneToMany): static
    {
        if (!$this->id_link_history->contains($oneToMany)) {
            $this->id_link_history->add($oneToMany);
            $oneToMany->setIdLinkHistory($this);
        }

        return $this;
    }

    public function removeOneToMany(LinkCheckHistory $oneToMany): static
    {
        if ($this->id_link_history->removeElement($oneToMany)) {
            // set the owning side to null (unless already changed)
            if ($oneToMany->getIdLinkHistory() === $this) {
                $oneToMany->setIdLinkHistory(null);
            }
        }

        return $this;
    }

    public function getTimes(): ?int
    {
        return $this->times;
    }

    public function setTimes(int $times): static
    {
        $this->times = $times;

        return $this;
    }
}
