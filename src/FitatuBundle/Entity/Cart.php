<?php

namespace FitatuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Cart
 *
 * @ORM\Table(
 *  name="cart",
 *  uniqueConstraints={@ORM\UniqueConstraint(columns={"cart_id", "product_id"})}
 * )
 * @UniqueEntity(
 *  fields={"cartId","product"}
 * )
 * @ORM\Entity(repositoryClass="FitatuBundle\Repository\CartRepository")
 */
class Cart
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
     * @var int
     *
     * @ORM\Column(name="cart_id", type="integer", nullable=false)
     */
    private $cartId;

    /**
     * @var int
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     */
    private $clientId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="id")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
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
     * Set cartId
     *
     * @param integer $cartId
     *
     * @return Cart
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;

        return $this;
    }

    /**
     * Get cartId
     *
     * @return integer
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Cart
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Cart
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \FitatuBundle\Entity\Product $product
     *
     * @return Cart
     */
    public function setProductId(\FitatuBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \FitatuBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product
     *
     * @param \FitatuBundle\Entity\Product $product
     *
     * @return Cart
     */
    public function setProduct(\FitatuBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }
}
