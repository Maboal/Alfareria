<?php

namespace App\Entity;

use App\Repository\DireccionesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DireccionesRepository::class)]
class Direcciones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $calle = null;

    #[ORM\Column(length: 50)]
    private ?string $ciudad = null;

    #[ORM\Column(length: 10)]
    private ?string $codigo_postal = null;

    #[ORM\ManyToOne(inversedBy: 'direcciones')]
    #[ORM\JoinColumn(name:'id_usuario', nullable: false, referencedColumnName:'id')]
    private ?Usuarios $id_usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): self
    {
        $this->calle = $calle;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigo_postal;
    }

    public function setCodigoPostal(string $codigo_postal): self
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    public function getIdUsuario(): ?Usuarios
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?Usuarios $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }
}
