<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name=LkInteractive\Back\LkpCarrousel\Repository\CarrouselRepository::TABLE_NAME_WITH_PREFIX)
 * @ORM\Entity(repositoryClass="LkInteractive\Back\LkpCarrousel\Repository\CarrouselRepository")
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
     * @ORM\OneToMany(targetEntity="LkInteractive\Back\LkpCarrousel\Entity\CarrouselLang", cascade={"persist", "remove"}, mappedBy="carrousel")
     */
    private $carrouselLangs;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_product", type="integer")
     */
    private $nbProduct;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_product_to_show", type="integer")
     */
    private $nbProductToShow;


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
     *
     * @ORM\Column(name="hook", type="string", length=255)
     */
    private $hook;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="order_by", type="string", length=255)
     */
    private $orderBy;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="sort_order", type="string", length=255)
     */
    private $sortOrder;

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

    public function __construct()
    {
        $this->carrouselLangs = new ArrayCollection();
        $this->carrouselCategories = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getCarrouselLangs()
    {
        return $this->carrouselLangs;
    }

    /**
     * @param int $langId
     * @return CarrouselLang|null
     */
    public function getCarrouselLangByLangId(int $langId)
    {
        foreach ($this->carrouselLangs as $carrouselLang) {
            if ($langId === $carrouselLang->getLang()->getId()) {
                return $carrouselLang;
            }
        }
        return null;
    }

    /**
     * @param CarrouselLang $carrouselLang
     * @return $this
     */
    public function addCarrouselLang(CarrouselLang $carrouselLang)
    {
        $carrouselLang->setCarrousel($this);
        $this->carrouselLangs->add($carrouselLang);
        return $this;
    }

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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Carrousel
     */
    public function setActive(bool $active): Carrousel
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
     * @return int
     */
    public function getNbProductToShow(): int
    {
        return $this->nbProductToShow;
    }

    /**
     * @param int $nbProductToShow
     * @return Carrousel
     */
    public function setNbProductToShow(int $nbProductToShow): Carrousel
    {
        $this->nbProductToShow = $nbProductToShow;
        return $this;
    }

    /**
     * @return string
     */
    public function getHook(): string
    {
        return $this->hook;
    }

    /**
     * @param string $hook
     * @return Carrousel
     */
    public function setHook(string $hook): Carrousel
    {
        $this->hook = $hook;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return Carrousel
     */
    public function setOrderBy(string $orderBy): Carrousel
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sortOrder;
    }

    /**
     * @param string $sortOrder
     * @return Carrousel
     */
    public function setSortOrder(string $sortOrder): Carrousel
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdd()
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
     * @return string
     */
    public function getCarrouselTitle()
    {
        if ($this->carrouselLangs->count() <= 0) {
            return '';
        }

        $carrouselLang = $this->carrouselLangs->first();

        return $carrouselLang->getTitle();
    }

    /**
     * @return string
     */
    public function getCarrouselBtnTitle()
    {
        if ($this->carrouselLangs->count() <= 0) {
            return '';
        }

        $carrouselLang = $this->carrouselLangs->first();

        return $carrouselLang->getBtnTitle();
    }

    /**
     * Now we tell doctrine that before we persist or update we call the update
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