<?php

namespace App\Entity\LinkCheck;

use App\Repository\LinkCheckHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkCheckHistoryRepository::class)]
class LinkCheckHistory
{
    // čia tikrai buvo galima kitaip padaryti,
    // pvz surišti duomenų bazėje pagal id kitą table kuris turės informaciją apie kiekvieną requestą sau atskirą Row
    // nuspręsta nekelti normalizacijos lygio ir daryti taip,
    // nes iš bendros patirties galiu pasakyti, kad taip būtų nereikalingai apkrauta duomenų bazė,
    // jei reikėtu išsaugoti daugiau informacijos, galima būtų pridėti naują Column ir panašiu principu išsaugoti,
    // blogiausiu atveju galima pridėti naują table kuris galės laikyti dažnai pasikartojančią informaciją,
    // ir surišti su unikaliu Id

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $url;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $dateAdd = null;

    //compressed information, multiple records separated by ":"
    #[ORM\Column(length: 255)]
    private ?string $keyword_occurrences = null;

    //compressed information, multiple records separated by ":"
    #[ORM\Column(length: 255)]
    private ?string $keywords = null;

    #[ORM\Column]
    private int $status;

    #[ORM\OneToMany(mappedBy: 'id_link_history', targetEntity: LinkCheckHistoryRedirects::class, orphanRemoval: true)]
    private Collection $redirects;

    public function __construct()
    {
        $this->redirects = new ArrayCollection();
    }

    public function getRedirects(): Collection
    {
        return $this->redirects;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getKeywordOccurrences(): ?array
    {
        if(str_contains($this->keyword_occurrences,':')){
            $keyword_occurrences = explode(':',$this->keyword_occurrences);
        } else {
            $keyword_occurrences = [$this->keyword_occurrences];
        }
        return $keyword_occurrences;
    }

    public function setKeywordOccurrences(?array $occurrences): void
    {
        $occurrences = implode(':',$occurrences);
        $this->keyword_occurrences = $occurrences;
    }

    public function getKeywords(): ?array
    {
        if(str_contains($this->keywords,':')){
            $keywords = explode(':',$this->keywords);
        } else {
            $keywords = [$this->keywords];
        }
        return $keywords;
    }

    public function setKeywords(?array $keywords): void
    {
        $keywords = implode(':',$keywords);
        $this->keywords = $keywords;
    }

    public function getDateAdd(): \DateTime
    {
        return $this->dateAdd;
    }

    public function setDateAdd(): void
    {
        $this->dateAdd = new \DateTime();
    }
}
