<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Entity;

use PrestaShopBundle\Entity\Lang;
/**
 * @ORM\Table(name="`lk_carrousel_lang`")
 * @ORM\Entity()
 */
class CarrouselLang
{
    /**
     * @var Carrousel
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="LkInteractive\Back\Doctrine\Entity\Carrousel", inversedBy="CarrouselLang")
     * @ORM\JoinColumn(name="id_carrousel", referencedColumnName="id_carrousel", nullable=false)
     */
    private $id;

    /**
     * @var Lang
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string" length=255)
     */
    private $title;

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
}