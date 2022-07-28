<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Form;

use LkInteractive\Back\Doctrine\Repository\CarrouselRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CarrouselFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var QuoteRepository
     */
    private $repository;

    /**
     * @param CarrouselRepository $repository
     */
    public function __construct(CarrouselRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getData($carrouselId)
    {
        $carrousel = $this->repository->findOneById($carrouselId);
        foreach ($carrousel->getCarrouselLang() as $carrouselLang) {
            $carrouselData['title'][$carrouselLang->getLang()->getId()] = $carrouselLang->getTitle();
        }
        return $carrouselData;
    }

    public function getDefaultData()
    {
        return [
            'content' => [],
        ];
    }
}
