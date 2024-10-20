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
    private Collection $cuatrimestres;

    public function __construct()
    {
        $this->cuatrimestres = new ArrayCollection();
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
    public function getCuatrimestres(): Collection // Cambio aquí: cuatrimestres (plural)
    {
        return $this->cuatrimestres;
    }

    public function addCuatrimestre(Cuatrimestre $cuatrimestre): static
    {
        if (!$this->cuatrimestres->contains($cuatrimestre)) {
            $this->cuatrimestres->add($cuatrimestre);
            $cuatrimestre->setCarrera($this);
        }

        return $this;
    }

    public function removeCuatrimestre(Cuatrimestre $cuatrimestre): static
    {
        if ($this->cuatrimestres->removeElement($cuatrimestre)) {
            if ($cuatrimestre->getCarrera() === $this) {
                $cuatrimestre->setCarrera(null);
            }
        }

        return $this;
    }
}
