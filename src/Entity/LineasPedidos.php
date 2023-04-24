<?php

namespace App\Entity;

use App\Repository\LineasPedidosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LineasPedidosRepository::class)]
class LineasPedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lineasPedidos')]
    #[ORM\JoinColumn(name:'id_producto', nullable: false, referencedColumnName:'id')]
    private ?Productos $id_producto = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio = null;

    #[ORM\ManyToOne(inversedBy: 'lineasPedidos')]
    #[ORM\JoinColumn(name:'id_pedido', nullable: false, referencedColumnName:'id')]
    private ?Pedidos $id_pedido = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProducto(): ?Productos
    {
        return $this->id_producto;
    }

    public function setIdProducto(?Productos $id_producto): self
    {
        $this->id_producto = $id_producto;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getIdPedido(): ?Pedidos
    {
        return $this->id_pedido;
    }

    public function setIdPedido(?Pedidos $id_pedido): self
    {
        $this->id_pedido = $id_pedido;

        return $this;
    }
}
