<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class HomeController extends Controller{


    /**
     * [hello description]
     *
     * @Route("/hello/{prenom}", name="hello")
     * Montre la page qui dit bonjour
     *  @return void
     */
    public function hello($prenom = "anonyme"){
        return new Response("Bonjour ".$prenom);
    }


    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        return $this->render(
            'home.html.twig'
        );
    }
}

?>
