<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Serializer\SerializerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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

    /**
      * @Route("/api/genres/{id}", name="api_genres_show", requirements={"id"="\d+"}, methods={"GET"})
      */
      public function show(Genre $genre, SerializerInterface $Serializer){
        
        $resultat = $Serializer->serialize(
            $genre, 
            'json', 
            [
                'groups' => ['listGenreSimple']
            ]
        );
       
        return new JsonResponse($resultat, 200, [], true);
    }

}
