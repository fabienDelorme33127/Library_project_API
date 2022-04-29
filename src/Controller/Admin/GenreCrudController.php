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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /**
      * @Route("/api/genres", name="api_genres_create", methods={"POST"})
      */
      public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator){
   
        $data = $request->getContent();
        $genre = $serializer->deserialize($data, Genre::class, 'json');

        $errors = $validator->validate($genre);

        if (count($errors)) {
            $errorsJson =  $serializer->serialize($errors, 'json');
            return new JsonResponse(
                $errorsJson, 
                Response::HTTP_BAD_REQUEST, 
                [],
                true
            );
        }


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
        $Serializer->deserialize($data, Genre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $genre]);
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
      * @Route("/api/genres/{id}", name="api_genres_delete", methods={"DELETE"})
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
