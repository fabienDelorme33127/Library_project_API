<?php
namespace App\Serializer;

use App\Entity\Adherent;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AdherentContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;
        
        if ($resourceClass === Adherent::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === true) {
            if($request->getmethod() === 'GET'){
                $context['groups'][] = 'get_itemColl_admin';
            }
        }

        if ($resourceClass === Adherent::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === false) {
            if($request->getmethod() === 'PUT'){
                $context['groups'][] = 'put_admin';
            }
            if($request->getmethod() === 'POST'){
                $context['groups'][] = 'post_admin';
            }
        }

        return $context;
    }
}