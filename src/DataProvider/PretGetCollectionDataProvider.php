<?php

namespace App\DataProvider;

use App\Entity\Pret;
use App\Repository\PretRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class PretGetCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
        public function __construct(TokenStorageInterface $tokenStorage, PretRepository $pretRepo){
                $this->tokenStorage = $tokenStorage;
                $this->pretRepo = $pretRepo;
        }

        public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
        {
                return Pret::class === $resourceClass;
        }


        public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
        {
                try {
                        $collection = $this->pretRepo->findBy([
                                'adherent' => $this->tokenStorage->getToken()->getUser()->getId()
                        ]);
                } catch (\Exception $e) {
                throw new \RuntimeException(sprintf("Impossible d'accéder à la ressource externe: %s", $e->getMessage()));
                }
                
                return $collection; 
        }
}