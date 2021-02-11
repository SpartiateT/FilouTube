<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Season
 *
 * @ORM\Table(name="season", indexes={@ORM\Index(name="IDX_F0E45BA95278319C", columns={"series_id"})})
 * @ORM\Entity
 */
class Season
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var \Series
     *
     * @ORM\ManyToOne(targetEntity="Series", inversedBy="seasons")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     * })
     */
    private $series;

    /**
     * @var \Episode
     *
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="season")
     * @OrderBy({"number" = "ASC"})
     */
    private $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSeries(): ?Series
    {
        return $this->series;
    }

    public function setSeries(?Series $series): self
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @return Collection|Episode[]
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function getNbEpisodes() {
        $nb = 0;
        foreach($this->getEpisodes() as $lsea) {
            $nb++;
        }
        return $nb;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes[] = $episode;
            $episode->setSeason($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getSeason() === $this) {
                $episode->setSeason(null);
            }
        }

        return $this;
    }

    public function getMoyImdbSaison() {
        $moy = null;
        $result = 0;
        foreach($this->getEpisodes() as $epi) {
            $result++;
            $moy += $epi->getImdbrating();
        }
        $moy = ($moy == null) ? null : $moy/$result;
        return $moy;
    }


}
