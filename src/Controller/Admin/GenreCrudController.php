<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class GenreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Genre::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    /**
      * @Route("/api/genres", name="api_genres", methods={"GET"})
      */
    public function list(GenreRepository $repo, SerializerInterface $Serializer){
   
        $genres = $repo->findAll();
        $resultat = $Serializer->serialize(
            $genres, 
            'json', 
            [
                'groups' => ['listGenreSimple', 'listGenreFull']
            ]
        );
       
        return new JsonResponse($resultat, 200, [], true);
    }

}
