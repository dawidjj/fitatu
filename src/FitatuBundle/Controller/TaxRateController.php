<?php

namespace FitatuBundle\Controller;

use FitatuBundle\Entity\TaxRate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Taxrate controller.
 *
 * @Route("taxrate")
 */
class TaxRateController extends Controller
{
    /**
     * Lists all taxRate entities.
     *
     * @Route("/", name="taxrate_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $taxRates = $em->getRepository('FitatuBundle:TaxRate')->findAll();

        return $this->render('taxrate/index.html.twig', array(
            'taxRates' => $taxRates,
        ));
    }

    /**
     * Creates a new taxRate entity.
     *
     * @Route("/new", name="taxrate_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $taxRate = new Taxrate();
        $form = $this->createForm('FitatuBundle\Form\TaxRateType', $taxRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($taxRate);
            $em->flush();

            return $this->redirectToRoute('taxrate_show', array('id' => $taxRate->getId()));
        }

        return $this->render('taxrate/new.html.twig', array(
            'taxRate' => $taxRate,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a taxRate entity.
     *
     * @Route("/{id}", name="taxrate_show")
     * @Method("GET")
     */
    public function showAction(TaxRate $taxRate)
    {
        $deleteForm = $this->createDeleteForm($taxRate);

        return $this->render('taxrate/show.html.twig', array(
            'taxRate' => $taxRate,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing taxRate entity.
     *
     * @Route("/{id}/edit", name="taxrate_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TaxRate $taxRate)
    {
        $deleteForm = $this->createDeleteForm($taxRate);
        $editForm = $this->createForm('FitatuBundle\Form\TaxRateType', $taxRate);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('taxrate_edit', array('id' => $taxRate->getId()));
        }

        return $this->render('taxrate/edit.html.twig', array(
            'taxRate' => $taxRate,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a taxRate entity.
     *
     * @Route("/{id}", name="taxrate_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TaxRate $taxRate)
    {
        $form = $this->createDeleteForm($taxRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($taxRate);
            $em->flush();
        }

        return $this->redirectToRoute('taxrate_index');
    }

    /**
     * Creates a form to delete a taxRate entity.
     *
     * @param TaxRate $taxRate The taxRate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TaxRate $taxRate)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('taxrate_delete', array('id' => $taxRate->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
