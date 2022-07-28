<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Form;

use Doctrine\ORM\EntityManagerInterface;
use LkInteractive\Back\Doctrine\Entity\Carrousel;
use LkInteractive\Back\Doctrine\Entity\CarrouselLang;
use LkInteractive\Back\Doctrine\Repository\CarrouselRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;

class CarrouselFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var CarrouselRepository
     */
    private $carrouselRepository;
    /**
     * @var LangRepository
     */
    private $langRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param CarrouselRepository $carrouselRepository
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CarrouselRepository    $carrouselRepository,
        LangRepository         $langRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->carrouselRepository = $carrouselRepository;
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {
        $quote = new Carrousel();
        $quote->setPosition($data['position']);
        foreach ($data['title'] as $langId => $langContent) {
            $lang = $this->langRepository->findOneById($langId);
            $carrouselLang = new CarrouselLang();
            $carrouselLang
                ->setLang($lang)
                ->setTitle($langContent);
            $quote->addCarrouselLang($quoteLang);
        }
        $this->entityManager->persist($quote);
        $this->entityManager->flush();
        return $quote->getId();
    }

    public function update($id, array $data)
    {
        $carrousel = $this->carrouselRepository->findOneById($id);
        $carrousel->setPosition($data['position']);
        foreach ($data['content'] as $langId => $content) {
            $carrouselLang = $carrousel->getCarrouselLangByLangId($langId);
            if (null === $carrouselLang) {
                continue;
            }
            $carrouselLang->setTitle($content);
        }
        $this->entityManager->flush();
        return $carrousel->getId();
    }
}