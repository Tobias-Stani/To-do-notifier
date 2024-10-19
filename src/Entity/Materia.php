<?php

namespace App\Entity;

use App\Repository\MateriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MateriaRepository::class)]
class Materia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'materia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cuatrimestre $cuatrimestre = null;

    /**
     * @var Collection<int, Cronometro>
     */
    #[ORM\OneToMany(targetEntity: Cronometro::class, mappedBy: 'materia')]
    private Collection $cronometro;

    #[ORM\ManyToOne(inversedBy: 'materias')]
    private ?Tarea $tarea = null;

    public function __construct()
    {
        $this->cronometro = new ArrayCollection();
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

    public function getCuatrimestre(): ?Cuatrimestre
    {
        return $this->cuatrimestre;
    }

    public function setCuatrimestre(?Cuatrimestre $cuatrimestre): static
    {
        $this->cuatrimestre = $cuatrimestre;

        return $this;
    }

    /**
     * @return Collection<int, Cronometro>
     */
    public function getCronometro(): Collection
    {
        return $this->cronometro;
    }

    public function addCronometro(Cronometro $cronometro): static
    {
        if (!$this->cronometro->contains($cronometro)) {
            $this->cronometro->add($cronometro);
            $cronometro->setMateria($this);
        }

        return $this;
    }

    public function removeCronometro(Cronometro $cronometro): static
    {
        if ($this->cronometro->removeElement($cronometro)) {
            // set the owning side to null (unless already changed)
            if ($cronometro->getMateria() === $this) {
                $cronometro->setMateria(null);
            }
        }

        return $this;
    }

    public function getTarea(): ?Tarea
    {
        return $this->tarea;
    }

    public function setTarea(?Tarea $tarea): static
    {
        $this->tarea = $tarea;

        return $this;
    }
}
