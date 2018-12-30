<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CSVFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/show", name="product_show")
     */
    public function show(Request $request)
    {
        $em = $this->getManager();
        $products = $em->getRepository("App:Product")->findAll();
        $form = $this->createForm(CSVFormType::class);
        $form->add('submit',SubmitType::class, array(
            'attr' => array('class'=>'form_file_submit'),
            'label' => 'form.file.button',
            'translation_domain' => 'messages'
        ));
        $form->handleRequest($request);


        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @param $productId
     * @param $units
     * add or decrease inventory units in the amount passed as parameter, if the amount is not an integer is rounded to the nearest integer
     */
    public function addInventory($productId,$units) {
        /** @var ObjectManager $em */
        $em = $this->getManager();
        $product = $em->getRepository('App:Product')->find($productId);
        $product->addInventory(round($units));
        $em->persist($product);
        $em->flush();
    }

    /**
     * @return ObjectManager
     */
    public function getManager() {
        return $this->getDoctrine()->getManager();
    }
}
