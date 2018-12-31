<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductBundleController extends AbstractController
{
    /**
     * @Route("/product/bundle/show/{bundleid}", name="bundle_show")
     */
    public function show($bundleid)
    {

        $em = $this->getManager();
        $product = $em->getRepository("App:BundleProduct")->find($bundleid);

        return $this->render('product_bundle/bundle_show.twig', [
            'product'=>$product,
        ]);
    }

    /**
     * @return ObjectManager
     */
    public function getManager() {
        return $this->getDoctrine()->getManager();
    }
}
