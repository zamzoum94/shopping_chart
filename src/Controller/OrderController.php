<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class OrderController extends AbstractController {
    /**
     * @Route("/order_purchase", methods={"POST"})
     */
    public function purchase(EntityManagerInterface $em, 
                             Request $request, 
                             UserRepository $rep,
                             SerializerInterface $serializer) : Response{
        $order = new Order();
        $user = $this->getUser();
        $content = $request->getContent();
        $order->setContent([
            "content" => $content
        ]);
        $user->addOrder($order);
        $em->persist($user);
        $em->persist($order);
        $em->flush();
        $this->addFlash("success", "Orders purchased succeffully!");
        return $this->json([
            'order' => "success"
        ]);
    }

    /**
     * @Route("/orders")
     */
    public function orders(SerializerInterface $serializer) : Response {
        $user = $this->getUser();
        $orders = $user->getOrders()->getValues();
        $res = array();
        foreach($orders as $order){
            $newOrder = [
                "id" => $order->getId(),
                "content" => $order->getContent()
            ];
            array_push($res, $newOrder);
        }

        return $this->json($res);
    }

    /**
    * @Route("/order/{id<\d+>}", name="app_get_order", methods={"GET"})
    */
    public function order(Order $id, ProductRepository $prod) : Response{
        $ids = json_decode($id->getContent()["content"]);
        $products = array();
        foreach ($ids as $idOrder) {
            array_push($products, $prod->findOneBy(["id" => $idOrder]));
        }
        return $this->render("layouts/order.html.twig", [
            "products" => $products
        ]);
    }
}