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

    #[ORM\ManyToOne(inversedBy: 'materias')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cuatrimestre $cuatrimestre = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dailyGoal = null;

    /**
     * @var Collection<int, Tarea>
     */
    #[ORM\OneToMany(targetEntity: Tarea::class, mappedBy: 'materia')]
    private Collection $tareas;

    /**
     * @var Collection<int, Cronometro>
     */
    #[ORM\OneToMany(targetEntity: Cronometro::class, mappedBy: 'materia')]
    private Collection $cronometros;

    #[ORM\Column(nullable: true)]
    private ?int $dailyStudyGoalHours = null;

    public function __construct()
    {
        $this->tareas = new ArrayCollection();
        $this->cronometros = new ArrayCollection();
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
     * @return Collection<int, Tarea>
     */
    public function getTareas(): Collection
    {
        return $this->tareas;
    }

    public function addTarea(Tarea $tarea): static
    {
        if (!$this->tareas->contains($tarea)) {
            $this->tareas->add($tarea);
            $tarea->setMateria($this);
        }

        return $this;
    }

    public function removeTarea(Tarea $tarea): static
    {
        if ($this->tareas->removeElement($tarea)) {
            // set the owning side to null (unless already changed)
            if ($tarea->getMateria() === $this) {
                $tarea->setMateria(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cronometro>
     */
    public function getCronometros(): Collection
    {
        return $this->cronometros;
    }

    public function addCronometro(Cronometro $cronometro): static
    {
        if (!$this->cronometros->contains($cronometro)) {
            $this->cronometros->add($cronometro);
            $cronometro->setMateria($this); // Cambiado a setMateria()
        }

        return $this;
    }

    public function removeCronometro(Cronometro $cronometro): static
    {
        if ($this->cronometros->removeElement($cronometro)) {
            // set the owning side to null (unless already changed)
            if ($cronometro->getMateria() === $this) { // Cambiado a getMateria()
                $cronometro->setMateria(null);
            }
        }

        return $this;
    }

    public function getDailyGoal(): ?int
    {
        return $this->dailyGoal;
    }
    
    public function setDailyGoal(?int $dailyGoal): self
    {
        $this->dailyGoal = $dailyGoal;
    
        return $this;
    }

    public function getDailyStudyGoalHours(): ?int
    {
        return $this->dailyStudyGoalHours;
    }

    public function setDailyStudyGoalHours(?int $dailyStudyGoalHours): static
    {
        $this->dailyStudyGoalHours = $dailyStudyGoalHours;

        return $this;
    }
}
