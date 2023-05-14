<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductosRepository::class)]
class Productos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagen_url = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categoria = null;

    #[ORM\OneToMany(mappedBy: 'id_producto', targetEntity: LineasPedidos::class, cascade: ['persist', 'remove'])]
    private Collection $lineasPedidos;

    public function __construct()
    {
        $this->lineasPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getImagenUrl(): ?string
    {
        return $this->imagen_url;
    }

    public function setImagenUrl(?string $imagen_url): self
    {
        $this->imagen_url = $imagen_url;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(?string $categoria): self
    {
        $this->categoria = $categoria;

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
            $lineasPedido->setIdProducto($this);
        }

        return $this;
    }

    public function removeLineasPedido(LineasPedidos $lineasPedido): self
    {
        if ($this->lineasPedidos->removeElement($lineasPedido)) {
            // set the owning side to null (unless already changed)
            if ($lineasPedido->getIdProducto() === $this) {
                $lineasPedido->setIdProducto(null);
            }
        }

        return $this;
    }
}
