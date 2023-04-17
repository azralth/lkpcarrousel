<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Entity;

use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;

/**
 * @ORM\Table(name=LkInteractive\Back\LkpCarrousel\Repository\CarrouselRepository::TABLE_NAME_LANG_WITH_PREFIX)
 * @ORM\Entity()
 */
class CarrouselLang
{

    /**
     * @var Carrousel
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="LkInteractive\Back\LkpCarrousel\Entity\Carrousel", inversedBy="carrouselLangs")
     * @ORM\JoinColumn(name="id_carrousel", referencedColumnName="id_carrousel", nullable=false)
     */
    private $carrousel;

    /**
     * @var Lang
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="btn_title", type="string", length=255)
     */
    private $btnTitle;


    /**
     * @return Carrousel
     */
    public function getId(): Carrousel
    {
        return $this->id;
    }

    /**
     * @param Carrousel $id
     * @return CarrouselLang
     */
    public function setId(Carrousel $id): CarrouselLang
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @param Lang $lang
     * @return CarrouselLang
     */
    public function setLang(Lang $lang): CarrouselLang
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CarrouselLang
     */
    public function setTitle(string $title): CarrouselLang
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBtnTitle(): string
    {
        return $this->btnTitle;
    }

    /**
     * @param string $btnTitle
     * @return CarrouselLang
     */
    public function setBtnTitle(string $btnTitle): CarrouselLang
    {
        $this->btnTitle = $btnTitle;
        return $this;
    }

    /**
     * @return Carrousel
     */
    public function getCarrousel(): Carrousel
    {
        return $this->carrousel;
    }

    /**
     * @param Carrousel $carrousel
     * @return CarrouselLang
     */
    public function setCarrousel(Carrousel $carrousel): CarrouselLang
    {
        $this->carrousel = $carrousel;
        return $this;
    }
}