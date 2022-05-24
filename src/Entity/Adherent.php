<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ApiResource(
 *      collectionOperations = {
 *           "get" = {
 *               "method" : "GET",
 *               "path" : "/adherents",
 *               "security" : "is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *           },
 *           "post" = {
 *               "method" : "POST",
 *               "security" : "is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *               "denormalization_context" = {
 *                   "groups" : { "post_manager" }
 *               },
 *           }
 *      },
 *      itemOperations = {
 *          "get" = {  
 *               "method" : "GET",
 *               "path" : "/adherents/{id}/prets",
 *               "security" : "(is_granted('ROLE_ADHERENT') and user.getId() == id) or is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *               "normalization_context" = {
 *                   "groups" : { "get_prets_user_co" }
 *               },
 *          },
 *          "get" = {
 *               "method" : "GET",
 *               "path" : "/adherents/{id}",
 *               "security" : "(is_granted('ROLE_ADHERENT') and user.getId() == id) or is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *               "normalization_context" = {
 *                   "groups" : { "get_itemColl_adherent" }
 *               },
 *          },
 *          "put" = {
 *               "method" : "PUT",
 *               "path" : "/adherents/{id}",
 *               "security" : "(is_granted('ROLE_ADHERENT') and user.getId() == id) or is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *               "denormalization_context" = {
 *                   "groups" : { "put_itemColl_adherent" }
 *               }
 *          },
 *           "delete" = {
 *               "method" : "DELETE",
 *               "path" : "/adherents/{id}",
 *               "security" : "is_granted('ROLE_ADMIN')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource"
 *           }
 *      }
 * )
 * @UniqueEntity(
 *     fields={"mail"},
 *     message="Il existe déjà un mail {{ value }}, veuillez saisir un autre mail"
 * )
 */
class Adherent implements UserInterface
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_MANAGER = "ROLE_MANAGER";
    const ROLE_ADHERENT = "ROLE_ADHERENT";
    const DEFAULT_ROLE = "ROLE_ADHERENT";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_itemColl_adherent" })
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_itemColl_adherent" })
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_itemColl_adherent" })
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_itemColl_adherent" })
     */
    private $codeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_admin" })
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "post_manager", "get_itemColl_adherent", "put_itemColl_adherent" })
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "post_manager", "modif_password_adherent", "put_admin", "get_itemColl_manager" })
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="adherent")
     * @ApiSubresource
     * @Groups({ "get_prets_user_co", "get_itemColl_adherent" })
     */
    private $prets;

    /**
     * @ORM\Column(type="array")
     * @Groups({ "get_itemColl_manager", "put_admin" })
     */
    private $roles = [];

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $leRole = [];
        $leRole[] = self::DEFAULT_ROLE;
        $this->roles = $leRole;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->codeCommune;
    }

    public function setCodeCommune(?string $codeCommune): self
    {
        $this->codeCommune = $codeCommune;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Pret>
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setAdherent($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }

        return $this;
    }




    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(){
        return null;
    }

    public function eraseCredentials(){}

    public function getUsername(){
        return $this->getMail();
    }

    public function getUserIdentifier(){}
    
}
