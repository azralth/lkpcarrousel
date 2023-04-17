<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Form;

use Doctrine\ORM\EntityManagerInterface;
use LkInteractive\Back\LkpCarrousel\Entity\Carrousel;
use LkInteractive\Back\LkpCarrousel\Entity\CarrouselLang;
use LkInteractive\Back\LkpCarrousel\Repository\CarrouselRepository;
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
        return $this->saveCarrousel(0, $data);
    }

    public function update($id, array $data)
    {
        return $this->saveCarrousel($id, $data);
    }

    private function saveCarrousel($id, array $data)
    {
        if($id) {
            $carrousel = $this->carrouselRepository->findOneById($id);

            // Get original category to compare
            $originalCategories = [];
            $cats = $this->carrouselRepository->getCarrouselCategories($id);
            foreach ($cats as $cat) {
                $categoryId = $cat['id_category'];
                $originalCategories[$categoryId] = $categoryId;
            }

            foreach ($data['title'] as $langId => $content) {
                $btnTitle = $data['btn_title'][$langId] ?? '';
                if(empty($content)) {
                    $content = 'Carrousel Title';
                }
                $carrouselLang = $carrousel->getCarrouselLangByLangId($langId);
                if (null === $carrouselLang) {
                    continue;
                }
                $carrouselLang->setTitle($content);
                $carrouselLang->setBtnTitle($btnTitle);
            }

            foreach ($data['categories'] as $category) {
                $category = (int)$category;

                // TODO : remove this when PrestaShop add category entity
                // update category
                $carrouselCategory = $this->carrouselRepository->getCarrouselCategoryById($id, (int)$category);

                if (false === $carrouselCategory) {
                    $this->carrouselRepository->addCarrouselCategory($id,(int)$category);
                }

                if (isset($originalCategories[$category])) {
                    unset($originalCategories[$category]);
                }
            }

            // Remove all unused category
            if(! empty($originalCategories)) {
                foreach($originalCategories as $originalCategory) {
                    $this->carrouselRepository->removeCarrouselCategory($id, (int)$originalCategory);
                }
            }
        } else {
            $carrousel = new Carrousel();
            foreach ($data['title'] as $langId => $langContent) {
                $btnTitle = $data['btn_title'][$langId] ?? '';
                if(empty($langContent)) {
                    $langContent = 'Carrousel Title';
                }
                $lang = $this->langRepository->findOneById($langId);
                $carrouselLang = new CarrouselLang();
                $carrouselLang
                    ->setLang($lang)
                    ->setTitle($langContent)
                    ->setBtnTitle($btnTitle);
                $carrousel->addCarrouselLang($carrouselLang);
            }
        }

        $carrousel->setPosition(0);

        if (isset($data['hook']) && is_string($data['hook'])) {
            $carrousel->setHook($data['hook']);
            $lkpCarrouselModule = new \LkPCarrousels();
            $lkpCarrouselModule->registerHook($data['hook']);
        }
        if (isset($data['order_by']) && is_string($data['order_by'])) {
            $carrousel->setOrderBy($data['order_by']);
        }
        if (isset($data['sort_order']) && is_string($data['sort_order'])) {
            $carrousel->setSortOrder($data['sort_order']);
        }
        if (isset($data['nb_product']) && is_int($data['nb_product'])) {
            $carrousel->setNbProduct($data['nb_product']);
        }
        if (isset($data['nb_product_to_show']) && is_int($data['nb_product_to_show'])) {
            $carrousel->setNbProductToShow($data['nb_product_to_show']);
        }
        if (isset($data['show_arrow']) && is_bool($data['show_arrow'])) {
            $carrousel->setShowArrow($data['show_arrow']);
        }
        if (isset($data['show_bullet']) && is_bool($data['show_bullet'])) {
            $carrousel->setShowBullet($data['show_bullet']);
        }
        if (isset($data['active']) && is_bool($data['active'])) {
            $carrousel->setActive($data['active']);
        }
        if(!$id) {
            $this->entityManager->persist($carrousel);
        }

        $this->entityManager->flush();

        // TODO : remove this when PrestaShop add category entity
        // add category association on creation
        if (!$id) {
            foreach ($data['categories'] as $category) {
                $this->carrouselRepository->addCarrouselCategory($carrousel->getId(),(int)$category);
            }
        }
        return $carrousel->getId();
    }
}