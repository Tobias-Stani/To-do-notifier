<?php

namespace App\Entity;

use App\Repository\CuatrimestreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CuatrimestreRepository::class)]
class Cuatrimestre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column]
    private ?int $anio = null;  // Agregado el campo anio

    #[ORM\ManyToOne(inversedBy: 'cuatrimestre')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carrera $carrera = null;

    /**
     * @var Collection<int, Materia>
     */
    #[ORM\OneToMany(targetEntity: Materia::class, mappedBy: 'cuatrimestre')]
    private Collection $materia;

    public function __construct()
    {
        $this->materia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getAnio(): ?int  // Getter para anio
    {
        return $this->anio;
    }

    public function setAnio(int $anio): static  // Setter para anio
    {
        $this->anio = $anio;

        return $this;
    }

    public function getCarrera(): ?Carrera
    {
        return $this->carrera;
    }

    public function setCarrera(?Carrera $carrera): static
    {
        $this->carrera = $carrera;

        return $this;
    }

    /**
     * @return Collection<int, Materia>
     */
    public function getMateria(): Collection
    {
        return $this->materia;
    }

    public function addMaterium(Materia $materium): static
    {
        if (!$this->materia->contains($materium)) {
            $this->materia->add($materium);
            $materium->setCuatrimestre($this);
        }

        return $this;
    }

    public function removeMaterium(Materia $materium): static
    {
        if ($this->materia->removeElement($materium)) {
            // set the owning side to null (unless already changed)
            if ($materium->getCuatrimestre() === $this) {
                $materium->setCuatrimestre(null);
            }
        }

        return $this;
    }
}
