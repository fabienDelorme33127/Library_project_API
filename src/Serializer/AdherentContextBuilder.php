<?php
namespace App\Serializer;

use App\Entity\Adherent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AdherentContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(SerializerContextBuilderInterface $decorated, SerializerInterface $serializer, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $token)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
        $this->token = $token;
        $this->serializer = $serializer;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;
        
        if ($resourceClass === Adherent::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_MANAGER') && $normalization === true) {
            if($request->getmethod() === 'GET'){
                $context['groups'][] = 'get_itemColl_manager';
            }
        }

        if ($resourceClass === Adherent::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADMIN') && $normalization === false) {
            if($request->getmethod() === 'PUT'){
                $context['groups'][] = 'put_admin';
            }
        }

        if ($resourceClass === Adherent::class && isset($context['groups']) && $this->authorizationChecker->isGranted('ROLE_ADHERENT') && $normalization === false) {
            if($request->getmethod() === 'PUT'){
                if($this->token->getToken()->getRoleNames()[0] == "ROLE_ADHERENT") {
                    $context['groups'][] = 'modif_password_adherent';
                }               
            }
            if($request->getmethod() === 'POST'){
                $role = json_decode($request->getContent(), true);
                if($role["roles"][0] !== "ROLE_ADHERENT"){
                    $role["roles"][0] = "ROLE_ADHERENT";               
                }
            }
        }

        return $context;
    }
}