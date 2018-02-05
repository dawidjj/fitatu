<?php

namespace FitatuBundle\Controller;

use FitatuBundle\Entity\Product;
use FitatuBundle\Entity\Cart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $forms = [];

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('FitatuBundle:Product')->findAll();

        foreach ($products as $product) {
            $addToCartForm = $this->createAddToCartForm($product);
            $forms[$product->getId()] = $addToCartForm->createView();
        }

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'forms' => $forms
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('FitatuBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $addToCartForm = $this->createAddToCartForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
            'add_to_cart_from' => $addToCartForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $addToCartForm = $this->createAddToCartForm($product);
        $editForm = $this->createForm('FitatuBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'add_to_cart_from' => $addToCartForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }


    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product) : \Symfony\Component\Form\Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to add to cart a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createAddToCartForm(Product $product) : \Symfony\Component\Form\Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_add_to_cart'))
            ->add('product_id', HiddenType::class, array(
                'data' => $product->getId()
            ))
            ->setMethod('POST')
            ->getForm()
        ;
    }
}
