<?php

namespace FitatuBundle\Model;

use \Doctrine\ORM\EntityManager;
use FitatuBundle\Entity\Cart as CartEntity;
use FitatuBundle\Entity\Product as ProductEntity;

class Cart
{
    private $clientId;

    private $cartId;

    private $entityManager;

    private $emptyCartPosition;

    public function __construct(
        int $clientId,
        int $cartId,
        EntityManager $entityManager,
        CartEntity $emptyCartPosition
    ) {
        $this->clientId = $clientId;
        $this->cartId = $cartId;
        $this->entityManager = $entityManager;
        $this->emptyCartPosition = $emptyCartPosition;
    }

    public function getProductsAndSortByTaxRate() : array
    {
        $products = $this->getProductsInCart();
        $products = $this->prepareProducts($products);

        return $products;
    }

    private function getProductsInCart() : array
    {
        return $this->entityManager
            ->getRepository(CartEntity::class)
            ->findProductsInCartAndSortByTaxRate(
                $this->cartId,
                $this->clientId
            )
        ;
    }

    private function prepareProducts(array $products) : array
    {
        $result = array();

        $index = -1;
        $taxRate = '';

        foreach ($products as $product) {
            $productTaxRate = $product['product']['taxRate']['value'];
            if (strlen($taxRate) == 0 || $taxRate !== $productTaxRate) {
                $index++;

                $result[$index] = array(
                    'taxRate' => $productTaxRate,
                    'products' => []
                );

                $taxRate = $productTaxRate;
            }

            $result[$index]['products'][] = array(
                'name' => $product['product']['name'],
                'price' => $product['product']['price'],
                'quantity' => $product['quantity'],
                'sum' => $product['quantity'] * $product['product']['price'],
                'id' => $product['id']
            );
        }

        return $result;
    }

    public function addProduct(int $productId) : bool
    {
        $result = false;

        $product = $this->getProduct($productId);

        if (!is_null($product)) {
            $this->addToCart($product);
            $result = true;
        }
        return $result;
    }

    private function getProduct(int $productId) : ?ProductEntity
    {
        return $this->entityManager
            ->getRepository(ProductEntity::class)
            ->findOne($productId)
        ;
    }

    private function addToCart(ProductEntity $product)
    {
        $cart = $this->getProductInCart($product);

        if (is_null($cart)) {
            $cart = clone $this->emptyCartPosition;
            $cart->setCartId(1);
            $cart->setClientId(1);
            $cart->setQuantity(1);
            $cart->setProduct($product);
        } else {
            $cart->setQuantity(
                $cart->getQuantity() + 1
            );
        }

        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    private function getProductInCart(ProductEntity $product) : ?CartEntity
    {
        return $this->entityManager
            ->getRepository(CartEntity::class)
            ->findOneProductInCart(
                $product,
                $this->cartId,
                $this->clientId
            )
        ;
    }

    public function removeProduct(int $positionId) : bool
    {
        $result = false;
        $positionInCart = $this->getPositionInCart($positionId);

        if (!is_null($positionInCart)) {
            $this->entityManager->remove($positionInCart);
            $this->entityManager->flush();
            $result = true;
        }

        return $result;
    }

    private function getPositionInCart(int $positionId) : ?CartEntity
    {
        return $this->entityManager
            ->getRepository(CartEntity::class)
            ->findById(
                $positionId,
                $this->cartId,
                $this->clientId
            )
        ;
    }
}
