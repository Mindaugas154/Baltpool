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
    private ?int $id_redirects = null;

    #[ORM\ManyToOne(targetEntity: LinkCheckHistory::class, inversedBy: 'id')]
    private ?LinkCheckHistory $id_link_history = null;

    #[ORM\Column(length: 255)]
    private ?string $url_redirects = null;

    #[ORM\Column]
    private ?int $status_redirects = null;

    public function getId(): ?int
    {
        return $this->id_redirects;
    }

    public function getUrl(): ?string
    {
        return $this->url_redirects;
    }

    public function setUrl(string $url_redirects): void
    {
        $this->url_redirects = $url_redirects;
    }

    public function getLinkCheckHistory(): ?LinkCheckHistory
    {
        return $this->id_link_history;
    }

    public function setLinkCheckHistory(?LinkCheckHistory $linkCheckHistory): void
    {
        $this->id_link_history = $linkCheckHistory;
    }

    public function getStatus(): ?int
    {
        return $this->status_redirects;
    }

    public function setStatus(int $status_redirects): void
    {
        $this->status_redirects = $status_redirects;
    }
}
