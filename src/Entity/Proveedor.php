<?php

namespace App\Entity;

use App\Repository\ProveedorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProveedorRepository::class)]
class Proveedor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Factura>
     */
    #[ORM\OneToMany(targetEntity: Factura::class, mappedBy: 'proveedor')]
    private Collection $facturas_relacionadas;

    public function __construct()
    {
        $this->facturas_relacionadas = new ArrayCollection();
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
     * @return Collection<int, Factura>
     */
    public function getFacturasRelacionadas(): Collection
    {
        return $this->facturas_relacionadas;
    }

    public function addFacturasRelacionada(Factura $facturasRelacionada): static
    {
        if (!$this->facturas_relacionadas->contains($facturasRelacionada)) {
            $this->facturas_relacionadas->add($facturasRelacionada);
            $facturasRelacionada->setProveedor($this);
        }

        return $this;
    }

    public function removeFacturasRelacionada(Factura $facturasRelacionada): static
    {
        if ($this->facturas_relacionadas->removeElement($facturasRelacionada)) {
            // set the owning side to null (unless already changed)
            if ($facturasRelacionada->getProveedor() === $this) {
                $facturasRelacionada->setProveedor(null);
            }
        }

        return $this;
    }
}
