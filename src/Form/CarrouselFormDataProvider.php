<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Form;

use LkInteractive\Back\LkpCarrousel\Repository\CarrouselRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CarrouselFormDataProvider implements FormDataProviderInterface
{

    /**
     * @var CarrouselRepository
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
        $carrouselData['hook'] = $carrousel->getHook();
        $carrouselData['order_by'] = $carrousel->getOrderBy();
        $carrouselData['sort_order'] = $carrousel->getSortOrder();
        $carrouselData['nb_product'] = $carrousel->getNbProduct();
        $carrouselData['nb_product_to_show'] = $carrousel->getNbProductToShow();
        $carrouselData['show_bullet'] = $carrousel->isShowBullet();
        $carrouselData['show_arrrow'] = $carrousel->isShowArrow();
        $carrouselData['active'] = $carrousel->isActive();

        foreach ($carrousel->getCarrouselLangs() as $carrouselLang) {
            $carrouselData['title'][$carrouselLang->getLang()->getId()] = $carrouselLang->getTitle();
            $carrouselData['btn_title'][$carrouselLang->getLang()->getId()] = $carrouselLang->getBtnTitle();
        }
        foreach ($this->repository->getCarrouselCategories($carrouselId) as $carrouselCategory) {
            $categoryId = $carrouselCategory['id_category'];
            $carrouselData['categories'][$categoryId] = $categoryId;
        }
        return $carrouselData;
    }

    public function getDefaultData()
    {
        return [
            'active' => 0,
            'categories' => [2],
            'show_arrrow' => 1,
        ];
    }
}
