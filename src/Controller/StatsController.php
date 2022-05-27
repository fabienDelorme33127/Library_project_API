<?php

namespace App\Controller;

use App\Repository\AdherentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    /**
     * @Route(
     *      path = "apiPlatform/adherents/nbPretsParAdherent", 
     *      name = "adherents_nbPrets",
     *      methods = "GET"
     * )
     */
    public function nbPretsParAdherent(AdherentRepository $repo)
    {
        $nbPretsParAdherent = $repo->nbPretsParAdherent();
        return $this->json($nbPretsParAdherent);
    }
}
