<?php

namespace FitatuBundle\Controller;


use FitatuBundle\Model\Cart as CartModel;
use FitatuBundle\Entity\Product;
use FitatuBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Cart controller.
 *
 * @Route("cart")
 */
class CartController extends Controller
{
    /**
     * List products in cart
     *
     * @Route("/list", name="cart_list_product")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $cartModel = $this->getCartModel();
        $productsInCart = $cartModel->getProductsAndSortByTaxRate();

        return $this->render('cart/list.html.twig', array(
        	'productsInCart' => $productsInCart
        ));
    }

    /**
     * Add to cart a product entity.
     *
     * @Route("/add", name="product_add_to_cart")
     * @Method("POST")
     */
    public function addToCartAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('product_id', HiddenType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if (isset($data['product_id'])) {
                $cartModel = $this->getCartModel();
                $cartModel->addProduct(
                    (int) $data['product_id']
                );
            }
        }

        return $this->redirectToRoute('cart_list_product');
    }

    private function getCartModel() : CartModel
    {
        return new CartModel(
            1,
            1,
            $this->getDoctrine()->getManager()
        );
    }
}
