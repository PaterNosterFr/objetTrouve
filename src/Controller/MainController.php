<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // Une méthode (function) par page !

    /**
     * @Route("/", name="main_home")
     */

    public function home()
    {
        return $this->render("main/home.html.twig");
    }

//--------------------------------------

    /**
     * @Route("/test", name="main_test")
     */

    public function test()
    {
        return $this -> render("main/test.html.twig");
    }

    //--------------------------------------


}