<?php

namespace App\Controller;

use App\Entity\Adherent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdherentController extends AbstractController
{
    /**
     * @Route(
     *      path = "apiPlatform/adherents/{id}/prets/count",
     *      name = "adherent_prets_count",
     *      methods = "GET",
     *      defaults = {
     *          "_controller" = "\app\controller\AdherentController::nombrePrets",
     *          "_api_resource_class" = "Adherent::class",
     *          "_api_item_name" = "getNbPrets"
     *      }
     * )
     */
    public function nombrePrets(Adherent $data)
    {
        $count = $data->getPrets()->count();
        return $this->json([
            'id' => $data->getId(),
            'nombre_prets' => $count
        ]);
    }
}
