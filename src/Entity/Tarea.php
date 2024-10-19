<?php

namespace App\Entity;

use App\Repository\TareaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TareaRepository::class)]
class Tarea
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    /**
     * @var Collection<int, Materia>
     */
    #[ORM\OneToMany(targetEntity: Materia::class, mappedBy: 'tarea')]
    private Collection $materias;

    public function __construct()
    {
        $this->materias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * @return Collection<int, Materia>
     */
    public function getMaterias(): Collection
    {
        return $this->materias;
    }

    public function addMateria(Materia $materia): static
    {
        if (!$this->materias->contains($materia)) {
            $this->materias->add($materia);
            $materia->setTarea($this);
        }

        return $this;
    }

    public function removeMateria(Materia $materia): static
    {
        if ($this->materias->removeElement($materia)) {
            // set the owning side to null (unless already changed)
            if ($materia->getTarea() === $this) {
                $materia->setTarea(null);
            }
        }

        return $this;
    }
}
