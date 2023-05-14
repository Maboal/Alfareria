<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $estado = null;

    #[ORM\OneToMany(mappedBy: 'id_pedido', targetEntity: LineasPedidos::class, cascade: ['persist', 'remove'])]
    private Collection $lineasPedidos;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(name:'id_usuario', nullable: false, referencedColumnName:'id')]
    private ?Usuarios $id_usuario = null;

    public function __construct()
    {
        $this->lineasPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return Collection<int, LineasPedidos>
     */
    public function getLineasPedidos(): Collection
    {
        return $this->lineasPedidos;
    }

    public function addLineasPedido(LineasPedidos $lineasPedido): self
    {
        if (!$this->lineasPedidos->contains($lineasPedido)) {
            $this->lineasPedidos->add($lineasPedido);
            $lineasPedido->setIdPedido($this);
        }

        return $this;
    }

    public function removeLineasPedido(LineasPedidos $lineasPedido): self
    {
        if ($this->lineasPedidos->removeElement($lineasPedido)) {
            // set the owning side to null (unless already changed)
            if ($lineasPedido->getIdPedido() === $this) {
                $lineasPedido->setIdPedido(null);
            }
        }

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
