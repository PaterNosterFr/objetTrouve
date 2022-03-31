<?php

namespace App\Controller;

use App\Repository\ObjetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
/*    Une méthode (function) par page !
    Bien commencer le parametre par /**
    Bien s'assurer que le "use" est appelé
    1ier paramètre : l'url souhaité
    ensuite , puis : name="", requirements="", methods=""*/

    /**
     * @Route("/", name="main_home")
     */

    public function home(ObjetsRepository $objetsRepository): Response
    {
        return $this->render("main/home.html.twig");
    }

	//! TODO : Faire un compteur

//--------------------------------------

    /**
     * @Route("/test", name="main_test")
     */

    public function test()
    {
        $serie =[
            "titre" => "Dune 2",
            "année" => 2021
        ];


        return $this -> render("main/test.html.twig", [
            "mySerie" => $serie,

        ]);
    }

    //--------------------------------------


}