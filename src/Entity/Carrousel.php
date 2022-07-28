<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lk_carrousel")
 * @ORM\Entity(repositoryClass="LkInteractive\Back\Doctrine\Repository\CarrouselRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Carrousel
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_carrousel", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="LkInteractive\Entity\CarrouselLang", cascade("persist", "remove")
     */
    private $carrouselLang;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="active", type="integer")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="nb_product", type="integer")
     */
    private $nbProduct;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_arrow", type="boolean")
     */
    private $showArrow = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_bullet", type="boolean")
     */
    private $showBullet = false;

    /**
     * @var string
     *
     * @ORM\Column(name="assoc_object", type="string" columnDefinition="ENUM('category', 'manufacturer')")
     */
    private $object;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_object", type="integer")
     */
    private $idObject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upd", type="datetime")
     */
    private $dateUpd;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Carrousel
     */
    public function setId(int $id): Carrousel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Carrousel
     */
    public function setPosition(int $position): Carrousel
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * @param int $active
     * @return Carrousel
     */
    public function setActive(int $active): Carrousel
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbProduct(): int
    {
        return $this->nbProduct;
    }

    /**
     * @param int $nbProduct
     * @return Carrousel
     */
    public function setNbProduct(int $nbProduct): Carrousel
    {
        $this->nbProduct = $nbProduct;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowArrow(): bool
    {
        return $this->showArrow;
    }

    /**
     * @param bool $showArrow
     * @return Carrousel
     */
    public function setShowArrow(bool $showArrow): Carrousel
    {
        $this->showArrow = $showArrow;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowBullet(): bool
    {
        return $this->showBullet;
    }

    /**
     * @param bool $showBullet
     * @return Carrousel
     */
    public function setShowBullet(bool $showBullet): Carrousel
    {
        $this->showBullet = $showBullet;
        return $this;
    }

    /**
     * @return string
     */
    public function getObject(): string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return Carrousel
     */
    public function setObject(string $object): Carrousel
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdObject(): int
    {
        return $this->idObject;
    }

    /**
     * @param int $idObject
     * @return Carrousel
     */
    public function setIdObject(int $idObject): Carrousel
    {
        $this->idObject = $idObject;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdd(): \DateTime
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTime $dateAdd
     * @return Carrousel
     */
    public function setDateAdd(\DateTime $dateAdd): Carrousel
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpd(): \DateTime
    {
        return $this->dateUpd;
    }

    /**
     * @param \DateTime $dateUpd
     * @return Carrousel
     */
    public function setDateUpd(\DateTime $dateUpd): Carrousel
    {
        $this->dateUpd = $dateUpd;
        return $this;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the upda
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setDateUpd(new DateTime());
        if ($this->getDateAdd() == null) {
            $this->setDateAdd(new DateTime());
        }
    }
}