<?php

namespace App\Controller;

use App\Entity\BundleProduct;
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


        return $this->render('product/product_show.html.twig', [
            'products' => $products,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/product/test", name="product_test")
     */
    public function test()
    {
        $em = $this->getManager();

        $product = new Product();
        $product->setReference('ADD001');
        $product->setName('Tenis Adidas Blancos');
        $product->setPrice(39.9);
        $product->setCost(20.10);
        $product->setInventory(80);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();

        $product = new Product();
        $product->setReference('ADD002');
        $product->setName('Tenis Adidas Negros');
        $product->setPrice(42.9);
        $product->setCost(20.10);
        $product->setInventory(75);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();

        $product = new Product();
        $product->setReference('ADD003');
        $product->setName('Tenis Adidas Azules');
        $product->setPrice(41.9);
        $product->setCost(20.10);
        $product->setInventory(70);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();

        $product = new Product();
        $product->setReference('NIK001');
        $product->setName('Tenis Nike Negros');
        $product->setPrice(35.9);
        $product->setCost(16.9);
        $product->setInventory(100);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();

        $product = new Product();
        $product->setReference('NIK002');
        $product->setName('Tenis Nike Blancos');
        $product->setPrice(32.9);
        $product->setCost(16.9);
        $product->setInventory(65);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();


        $product = new BundleProduct();
        $product->setReference('ADD010');
        $product->setName('Bundle Adidas B & N');
        $product->setInventory(80);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();
        $product1 = $em->getRepository('App:Product')->find(1);
        $product2 = $em->getRepository('App:Product')->find(2);
        $product->addProduct($product1);
        $product->addProduct($product2);
        $em->persist($product);
        $em->flush();

        $product = new BundleProduct();
        $product->setReference('NIK010');
        $product->setName('Bundle Nike B & N');
        $product->setInventory(90);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();
        $product1 = $em->getRepository('App:Product')->find(4);
        $product2 = $em->getRepository('App:Product')->find(5);
        $product->addProduct($product1);
        $product->addProduct($product2);
        $em->persist($product);
        $em->flush();

        $product = new BundleProduct();
        $product->setReference('ADD011');
        $product->setName('Bundle Adidas B, A & N');
        $product->setInventory(80);
        $product->setStatus(1);
        $em->persist($product);
        $em->flush();
        $product1 = $em->getRepository('App:Product')->find(1);
        $product2 = $em->getRepository('App:Product')->find(2);
        $product3 = $em->getRepository('App:Product')->find(3);
        $product->addProduct($product1);
        $product->addProduct($product2);
        $product->addProduct($product3);
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('product_show');
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
