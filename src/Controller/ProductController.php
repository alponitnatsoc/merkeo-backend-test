<?php

namespace App\Controller;

use App\Entity\BundleProduct;
use App\Entity\Product;
use App\Form\CSVFormType;
use function Couchbase\defaultDecoder;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Teapot\StatusCode;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/show", name="product_show")
     */
    public function show(Request $request)
    {
        $em = $this->getManager();
        $products = $em->getRepository("App:Product")->findAll();
        $form = $this->createForm(CSVFormType::class,null);
        $form->add('submit',SubmitType::class, array(
            'attr' => array('class'=>'form_file_submit'),
            'label' => 'form.file.button',
            'translation_domain' => 'messages',
        ));

        return $this->render('product/product_show.html.twig', [
            'products' => $products,
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/product/execute", methods={"POST"}, name="product_execute" )
     * @param Request $request
     * @return Response
     */
    public function handleCommands(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type','application/json');
        $contentType = $request->getContentType();
        $data = [];
        if($contentType != 'json' and $contentType != 'application/json'){
            $data['error'] = 'Error en el formato del request.<br/>';
            $response->setContent(json_encode($data));
            $response->setStatusCode(StatusCode::BAD_REQUEST);
            return $response;
        }
        try {
            $content = json_decode($request->getContent(),true);
            $data['response'] = "";
            foreach ($content as $action){
                $line = $action['line'];

                if (count($action) == 1) {
                    $data['response'] .= 'Error en el comando ' . $line . ' el comando no es es valido.<br/>';
                    continue;
                }

                $productID = $action['product-id'];
                $command = $action['command'];

                if (count($action) == 4) {
                    $amount = $action['amount'];
                }

                $em = $this->getManager();
                /** @var Product $product */
                $product = $em->getRepository("App:Product")->find($productID);

                if (empty($product)) {
                    $data['response'] .= 'Error en el comando ' . $line . ' el producto con id ' . $productID . ' no se encontro.<br/>';
                    continue;
                }

                if(($command == 'agregar' or $command == 'restar') and !isset($amount)) {
                    $data['response'] .= 'Error en el comando ' . $line . ' el inventario no es valido.<br/>';
                    continue;
                }

                switch ($command){
                    case 'activar':
                        $product->activateProduct();
                        $data['response'] .= 'Comando ' . $line . ' se activo correctamente el producto con id ' . $productID . '.<br/>';
                        break;
                    case 'desactivar':
                        $product->disableProduct();
                        $data['response'] .= 'Comando ' . $line . ' se desactivo correctamente el producto con id ' . $productID . '.<br/>';
                        break;
                    case 'agregar':
                        try{
                            $product->addInventory(floor($amount));
                            $data['response'] .= 'Comando ' . $line . ' se agrego el inventario del producto con id ' . $productID . '.<br/>';
                        }catch(\Exception $e){
                            $data['response'] .= 'Error en el comando ' . $line . ' no se pudo agregar el inventario.<br/>';
                            continue;
                        }
                        break;
                    case 'restar':
                        try{
                            $product->addInventory(floor($amount)*-1);
                            $data['response'] .= 'Comando ' . $line . ' se redujo el inventario del producto con id ' . $productID . '.<br/>';
                        }catch(\Exception $e){
                            $data['response'] .= 'Error en el comando ' . $line . ' no se pudo reducir el inventario.<br/>';
                            continue;
                        }
                        break;
                    default:
                        $data['response'] .= 'Error en el comando ' . $line . ' el comando no es valido.<br/>';
                        continue;
                }
                $em->persist($product);
                $em->flush();
            }
        } catch (\Exception $e){
            $data['error'] = 'Error al ejecutar comandos. -- ['.$e->getMessage().']';
            $response->setContent(json_encode($data));
            $response->setStatusCode(StatusCode::INTERNAL_SERVER_ERROR);
        }

        $response->setContent(json_encode($data));
        $response->setStatusCode(StatusCode::OK);

        return $response;
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
     * @return ObjectManager
     */
    public function getManager() {
        return $this->getDoctrine()->getManager();
    }
}
