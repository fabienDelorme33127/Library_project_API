<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Pret;

class Test  extends AbstractController
{

        private $bookPublishingHandler;

        public function __construct( $token)
        {
                
        }

        public function __invoke( $data)
        {
                $data = "test";

                return $data;
        }
}

