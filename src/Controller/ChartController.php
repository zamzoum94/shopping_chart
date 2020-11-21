<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class ChartController extends AbstractController{
    /**
     * @Route("/chart", name="get_from_chart")
     */
    public function getFromChart(){
        return $this->render("layouts/chart.html.twig");
    }

    /**
     * @Route("/getProductFromChart/{id<\d+>}")
     */
    public function getItemFromChart(Product $id){
        return $this->json($id);
    }
}