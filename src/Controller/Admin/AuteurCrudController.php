<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NationaliteRepository;
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



class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    /**
      * @Route("/api/auteurs", name="api_auteurs", methods={"GET"})
      */
    public function list(AuteurRepository $repo, SerializerInterface $Serializer){
   
        $auteurs = $repo->findAll();
        $resultat = $Serializer->serialize(
            $auteurs, 
            'json', 
            [
                'groups' => ['listAuteurSimple', 'listAuteurFull']
            ]
        );
       
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
      * @Route("/api/auteurs/{id}", name="api_auteurs_show", requirements={"id"="\d+"}, methods={"GET"})
      */
      public function show(Auteur $auteur, SerializerInterface $Serializer){
        
        $resultat = $Serializer->serialize(
            $auteur, 
            'json', 
            [
                'groups' => ['listAuteurSimple']
            ]
        );
       
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
      * @Route("/api/auteurs", name="api_auteurs_create", methods={"POST"})
      */
      public function create(Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator, NationaliteRepository $nationaliteRepository){

        $data = $request->getContent();
        $dataArray = json_decode($data, true);
        $nationalite = $nationaliteRepository->find($dataArray["nationalite_id"]["id"]);
        $auteur = $serializer->deserialize($data, Auteur::class, 'json');
        $auteur->setNationalite($nationalite);

        /* $errors = $validator->validate($auteur);

        if (count($errors)) {
            $errorsJson =  $serializer->serialize($errors, 'json');
            return new JsonResponse(
                $errorsJson, 
                Response::HTTP_BAD_REQUEST, 
                [],
                true
            );
        } */


        $manager->persist($auteur);
        $manager->flush();
       
        return new JsonResponse(
            "le auteur a bien été créé", 
            Response::HTTP_CREATED, 
            [
                'location' => "/api/auteurs/" . $auteur->getId(),
            ],
            true
        );
    }

    /**
      * @Route("/api/auteurs/{id}", name="api_auteurs_update", methods={"PUT"})
      */
      public function update(Auteur $auteur, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, NationaliteRepository $nationaliteRepository){
   
        $data = $request->getContent();
        $dataArray = json_decode($data, true);

        $nationalite = $nationaliteRepository->find($dataArray["nationalite_id"]["id"]);

        $data = $serializer->deserialize($data, Auteur::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $auteur]);
        $data->setNationalite($nationalite);

        $manager->persist($auteur);
        $manager->flush();
       
        return new JsonResponse(
            "l'auteur a bien été modifé", 
            Response::HTTP_OK, 
            [],
            true
        );
    }

    /**
      * @Route("/api/auteurs/{id}", name="api_auteurs_delete", methods={"DELETE"})
      */
      public function supprimer(Auteur $auteur, Request $request, EntityManagerInterface $manager, SerializerInterface $Serializer){
   
        $manager->remove($auteur);
        $manager->flush();
       
        return new JsonResponse(
            "l'auteur a bien été supprimé", 
            Response::HTTP_OK, 
            []
        );
    }

}
