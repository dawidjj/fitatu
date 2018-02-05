<?php

namespace FitatuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="FitatuBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=true, length=50)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="id")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="TaxRate", inversedBy="id")
     * @ORM\JoinColumn(name="tax_rate_id", referencedColumnName="id", nullable=false)
     */
    private $taxRate;

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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     * Set category
     *
     * @param \FitatuBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\FitatuBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \FitatuBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set taxRate
     *
     * @param \FitatuBundle\Entity\TaxRate $taxRate
     *
     * @return Product
     */
    public function setTaxRate(\FitatuBundle\Entity\TaxRate $taxRate = null)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return \FitatuBundle\Entity\TaxRate
     */
    public function getTaxRate()
    {
        return $this->taxRate;
    }
}
