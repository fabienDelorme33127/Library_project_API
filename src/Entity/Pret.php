<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
*      collectionOperations = {
*           "get" = {
*               "method" : "GET",
*               "path" : "/prets",
*               "normalization_context" = {
*                   "groups" : { "get_role_adherent" }
*               },
*           },
*           "post" = {
*               "method" : "POST",
*           }
*       },
 *      itemOperations = {
 *           "get" = {
 *               "method" : "GET",
 *               "path" : "/prets/{id}",
 *               "normalization_context" = {
 *                   "groups" : { "get_role_adherent" }
 *               },
 *           },
 *           "delete" = {
 *               "method" : "DELETE",
 *               "path" : "/prets/{id}",
 *               "security" : "is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource"
 *           },
 *            "put" = {
 *               "method" : "PUT",
 *               "path" : "/prets/{id}",
 *               "security" : "is_granted('ROLE_MANAGER')",
 *               "security_message" : "Vous n'avez pas l'autorisation d'accéder à cette ressource",
 *               "denormalization_context" = {
 *                   "groups" : { "put_manager" }
 *               }
 *           }
 *      }
 * )
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "put_admin", "get_role_adherent" })
     */
    private $datePret;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "put_admin", "get_role_adherent" })
     */
    private $dateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({ "put_manager", "put_admin" })
     */
    private $dateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "put_admin", "get_role_adherent" })
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "put_admin" })
     */
    private $adherent;

    public function __construct()
    {
        $this->datePret = new \DateTime();

        $dateRetourPrevue = date("Y-m-d H:m:n", strtotime('15 days', $this->getDatePret()->getTimestamp()));
        $dateRetourPrevue = \DateTime::createFromFormat('Y-m-d H:m:n', $dateRetourPrevue);
        $this->dateRetourPrevue = $dateRetourPrevue;

        $this->dateRetourReelle =  null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): self
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourReelle(): ?\DateTimeInterface
    {
        return $this->dateRetourReelle;
    }

    public function setDateRetourReelle(?\DateTimeInterface $dateRetourReelle): self
    {
        $this->dateRetourReelle = $dateRetourReelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }
}
