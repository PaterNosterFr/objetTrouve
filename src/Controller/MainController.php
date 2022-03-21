<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // Une méthode par page !

    /**
     * @Route("/", name="main_home")
     */

    public function home()
    {
        echo "home page";
        die();
    }

//--------------------------------------

    /**
     * @Route("/test", name="main_test")
     */

    public function test()
    {
        echo "Page de test des codes";
        die();
    }

    //--------------------------------------


}