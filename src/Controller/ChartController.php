<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class ChartController extends AbstractController{
    /**
     * @Route("/getchart", name="get_chart")
     */
    public function getFromChart(){
        if(!$this->getUser()){
            $this->addFlash("danger", "You have to be logged in to access the cart!");
            return $this->redirectToRoute("app_login");
        }
        return $this->render("layouts/chart.html.twig");
    }

    /**
     * @Route("/getProductFromChart/{id<\d+>}")
     */
    public function getItemFromChart(Product $id){
        return $this->json($id);
    }
}