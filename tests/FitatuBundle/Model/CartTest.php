<?php

namespace App\Tests\FitatuBundle\Model;

use PHPUnit\Framework\TestCase;
use FitatuBundle\Model\Cart;
use FitatuBundle\Entity\Cart as CartEntity;
use FitatuBundle\Entity\Product;

class CartTest extends TestCase
{
    public function testGetProductsAndSortByTaxRate()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(),
            new CartEntity()
        );

        $products = $cart->getProductsAndSortByTaxRate();

        $this->assertEquals(
            $products,
            $this->dataForGetProductsAndSortByTaxRate()
           );
    }

    public function testAddProductNotExist()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(),
            new CartEntity()
        );

        $result = $cart->addProduct(12);

        $this->assertEquals($result, false);
    }

    public function testAddExistProduct()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(true),
            new CartEntity()
        );

        $result = $cart->addProduct(6);

        $this->assertEquals($result, true);
    }

    public function testChangeQuantityInCart()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(true, true),
            new CartEntity()
        );

        $result = $cart->addProduct(8);

        $this->assertEquals($result, true);
    }

    public function testRemovePositionInCart()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(true, true),
            new CartEntity()
        );

        $result = $cart->removeProduct(123);

        $this->assertEquals($result, true);    
    }

    public function testRemoveNotExistPositionInCart()
    {
        $cart = new Cart(
            1,
            1,
            $this->getMockEntityManager(true),
            new CartEntity()
        );

        $result = $cart->removeProduct(124);

        $this->assertEquals($result, false);    
    }

    private function getMockEntityManager(
        bool $existProduct = false,
        bool $productInCart = false
    ) {
        $repository = $this->setMockRepository(
            $existProduct,
            $productInCart
        );

        return $this->setMockEntityManager($repository);
    }

    private function setMockRepository(
        bool $existProduct = false,
        bool $productInCart = false
    ) {
        $repository = $this
            ->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'findProductsInCartAndSortByTaxRate',
                'findOne',
                'findOneProductInCart',
                'findById'
            ))
            ->getMock()
        ;

         $repository->expects($this->any())
            ->method('findProductsInCartAndSortByTaxRate')
            ->will($this->returnValue(
                $this->findProductsInCartAndSortByTaxRate()
            ))
        ;

        $data = $existProduct ? new Product() : null;
         $repository->expects($this->any())
            ->method('findOne')
            ->will($this->returnValue($data))
        ;

        $data = $productInCart ? new CartEntity() : null;
         $repository->expects($this->any())
            ->method('findOneProductInCart')
            ->will($this->returnValue($data))
        ;

         $repository->expects($this->any())
            ->method('findById')
            ->will($this->returnValue($data))
        ;

        return $repository;
    }

    private function setMockEntityManager($repository)
    {
        $entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getRepository',
                'persist',
                'flush',
                'remove'
               ))
            ->getMock();

         $entityManager->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null))
        ;

         $entityManager->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null))
        ;

         $entityManager->expects($this->any())
            ->method('remove')
            ->will($this->returnValue(null))
        ;

         $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repository));

        return $entityManager;
    }

    private function findProductsInCartAndSortByTaxRate() : array
    {
        return [
            $this->getItem([5, 1, 1, 2, 6, 'Telefon', 100, 3, 8]),
            $this->getItem([6, 1, 1, 3, 7, 'Klawiatura', 300, 3, 8]),
            $this->getItem([4, 1, 1, 1, 8, 'Myszka', 200, 2, 23])
        ];
    }

    private function getItem(array $data) : array
    {
        return [
            'id' => $data[0],
            'cartId' => $data[1],
            'clientId' => $data[2],
            'quantity' => $data[3],
            'product' => [
                'id' => $data[4],
                'name' => $data[5],
                'price' => $data[6],
                'taxRate' => [
                    'id' => $data[7],
                    'value' => $data[8]
                ]
            ]
        ];
    }

    private function dataForGetProductsAndSortByTaxRate() : array
    {
        return [
            [
                'taxRate' => 8,
                'products' => [
                    [
                        'name' => 'Telefon',
                        'price' => 100,
                        'quantity' => 2,
                        'sum' => 200,
                        'id' => 5
                    ],
                    [
                        'name' => 'Klawiatura',
                        'price' => 300,
                        'quantity' => 3,
                        'sum' => 900,
                        'id' => 6
                    ]
                ]
            ],
            [
                'taxRate' => 23,
                'products' => [
                    [
                        'name' => 'Myszka',
                        'price' => 200,
                        'quantity' => 1,
                        'sum' => 200,
                        'id' => 4
                    ]
                ]
            ],
        ];
    }
}
