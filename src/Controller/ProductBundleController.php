<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductBundleController extends AbstractController
{
    /**
     * @Route("/product/bundle", name="product_bundle")
     */
    public function index()
    {
        return $this->render('product_bundle/index.html.twig', [
            'controller_name' => 'ProductBundleController',
        ]);
    }
}
