<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFacture;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoiceNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $customerId;

    /**
     * @ORM\OneToMany(targetEntity=Invoicelines::class, mappedBy="invoice")
     */
    private $invoicelines;

    public function __construct()
    {
        $this->invoicelines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeInterface $dateFacture): self
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getInvoiceNumber(): ?int
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(int $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return Collection<int, Invoicelines>
     */
    public function getInvoicelines(): Collection
    {
        return $this->invoicelines;
    }

    public function addInvoiceline(Invoicelines $invoiceline): self
    {
        if (!$this->invoicelines->contains($invoiceline)) {
            $this->invoicelines[] = $invoiceline;
            $invoiceline->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceline(Invoicelines $invoiceline): self
    {
        if ($this->invoicelines->removeElement($invoiceline)) {
            // set the owning side to null (unless already changed)
            if ($invoiceline->getInvoice() === $this) {
                $invoiceline->setInvoice(null);
            }
        }

        return $this;
    }
}
