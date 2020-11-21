<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Product;
use App\Form\ProductType as FormProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilder;
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
     * @Route("/", name="products", methods={"GET"})
     */
    public function index(ProductRepository $prod): Response
    {
        return $this->render('layouts/products.html.twig', [
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
            $this->addFlash("success", "Product succefully added");
            return $this->redirectToRoute("products");
        }

        return $this->render('layouts/create_product.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/product/update/{id<\d+>}", name="product_update", methods={"GET", "PUT"})
     */
     public function update(Product $prod, Request $request) : Response {
         $form = $this->createForm(FormProduct::class, $prod, ["method" => "PUT"]);
         $form->handleRequest($request);
         if($form->isSubmitted()){
             $this->em->flush();
             $this->addFlash("success", "Product succefully Updated");
             return $this->redirectToRoute("products");
         }
         return $this->render('layouts/update_product.html.twig',[
             "form" => $form->createView(),
             "product" => $prod
         ]);
     }

    /**
     * @Route("/product/delete/{id<\d+>}", name="product_delete", methods={"DELETE"})
     */
     public function delete(Product $product, Request $request) : Response{
         $this->em->remove($product);
         $this->em->flush();
         $this->addFlash("danger", "Product succefully removed");
         return $this->redirectToRoute("products");
     }
}
