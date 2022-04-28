<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Serializer\SerializerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



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

    /**
      * @Route("/api/genres", name="api_genres_create", methods={"POST"})
      */
      public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $Serializer){
   
        $data = $request->getContent();
      /*   $genre =  new Genre();
        $Serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]); */
        $genre = $Serializer->deserialize($data, Genre::class, 'json');

        $manager->persist($genre);
        $manager->flush();
       
        return new JsonResponse(
            "le genre a bien été créé", 
            Response::HTTP_CREATED, 
            [
                'location' => "/api/genres/" . $genre->getId(),
            ],
            true
        );
    }

    /**
      * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
      */
      public function update(Genre $genre, Request $request, EntityManagerInterface $manager, SerializerInterface $Serializer){
   
        $data = $request->getContent();
        $Serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
        $manager->persist($genre);
        $manager->flush();
       
        return new JsonResponse(
            "le genre a bien été modifé", 
            Response::HTTP_OK, 
            [],
            true
        );
    }

    /**
      * @Route("/api/genres/{id}", name="api_genres_update", methods={"DELETE"})
      */
      public function supprimer(Genre $genre, Request $request, EntityManagerInterface $manager, SerializerInterface $Serializer){
   
        $manager->remove($genre);
        $manager->flush();
       
        return new JsonResponse(
            "le genre a bien été supprimé", 
            Response::HTTP_OK, 
            []
        );
    }

}
