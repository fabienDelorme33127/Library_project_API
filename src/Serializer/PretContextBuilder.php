<?php
namespace App\Serializer;

use App\Entity\Pret;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class PretContextBuilder implements SerializerContextBuilderInterface
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
        
        if ($resourceClass === Pret::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === false) {
            if($request->getmethod() === 'PUT'){
                $context['groups'][] = 'put_admin';
            }
        }

        return $context;
    }
}