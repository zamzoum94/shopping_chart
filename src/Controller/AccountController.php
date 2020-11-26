<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController {
    /**
    * @Route("/account", name="account", methods={"GET"})
    */
    public function index(){
    	if(!$this->getUser()){
    		$this->addFlash("danger", "You have to be logged access account!");
    		return $this->redirectToRoute("app_login");
    	}
        return $this->render("layouts/account.html.twig");
    }
}