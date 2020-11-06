<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private $em = null;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Route("/products", name="products", methods={"GET"})
     */
    public function index(ProductRepository $prod): Response
    {
        return $this->render('layouts/index.html.twig', [
            'products' => $prod->findAll()
        ]);
    }

    /**
     * @Route("/product/create", name="product_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $product = new Product;
        $form = $this->createFormBuilder($product)
            ->add("name")
            ->add("description")
            ->add("price")
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute("products");
        }

        return $this->render('layouts/create_product.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/product/update/{id}", name="product_update", methods={"GET", "PUT"})
     */
     public function update(Product $prod, Request $request):Response{
         $form = $this->createFormBuilder($prod)
             ->setMethod("PUT")
             ->add("name")
             ->add("description")
             ->add("price")
             ->getForm();

         $form->handleRequest($request);
         if($form->isSubmitted()){
             dd($request);
         }
         return $this->render('layouts/update_product.html.twig',[
             "form" => $form->createView(),
             "product" => $prod
         ]);
     }
}
