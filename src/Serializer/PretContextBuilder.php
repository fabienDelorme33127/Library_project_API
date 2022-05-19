<?php
namespace App\Serializer;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class PretContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;
        
        if ($resourceClass === Pret::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === false) {
            if($request->getmethod() === 'PUT'){
                $context['groups'][] = 'put_admin';
            }
        }

        if ($resourceClass === Pret::class && $this->authorizationChecker->isGranted('ROLE_ADHERENT')) {
            if($request->getmethod() === 'POST'){
                
            }
        }

        return $context;
    }
}