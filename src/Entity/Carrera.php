<?php

namespace App\Entity;

use App\Repository\CarreraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarreraRepository::class)]
class Carrera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Cuatrimestre>
     */
    #[ORM\OneToMany(targetEntity: Cuatrimestre::class, mappedBy: 'carrera', orphanRemoval: true)]
    private Collection $cuatrimestre;

    public function __construct()
    {
        $this->cuatrimestre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Cuatrimestre>
     */
    public function getCuatrimestre(): Collection
    {
        return $this->cuatrimestre;
    }

    public function addCuatrimestre(Cuatrimestre $cuatrimestre): static
    {
        if (!$this->cuatrimestre->contains($cuatrimestre)) {
            $this->cuatrimestre->add($cuatrimestre);
            $cuatrimestre->setCarrera($this);
        }

        return $this;
    }

    public function removeCuatrimestre(Cuatrimestre $cuatrimestre): static
    {
        if ($this->cuatrimestre->removeElement($cuatrimestre)) {
            // set the owning side to null (unless already changed)
            if ($cuatrimestre->getCarrera() === $this) {
                $cuatrimestre->setCarrera(null);
            }
        }

        return $this;
    }
}
