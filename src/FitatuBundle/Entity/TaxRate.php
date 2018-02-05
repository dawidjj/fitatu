<?php

namespace FitatuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxRate
 *
 * @ORM\Table(name="tax_rate")
 * @ORM\Entity(repositoryClass="FitatuBundle\Repository\TaxRateRepository")
 */
class TaxRate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float", nullable=false, unique=true)
     */
    private $value;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="id")
     */
    private $product;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return TaxRate
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add product
     *
     * @param \FitatuBundle\Entity\Product $product
     *
     * @return TaxRate
     */
    public function addProduct(\FitatuBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \FitatuBundle\Entity\Product $product
     */
    public function removeProduct(\FitatuBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return TaxRate
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    public function __toString() : string
    {
        return $this->getValue();
    }
}
