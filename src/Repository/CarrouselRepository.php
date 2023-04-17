<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use PrestaShop\PrestaShop\Adapter\Entity\Db;

class CarrouselRepository extends EntityRepository
{
    public const TABLE_NAME = 'lk_carrousel';
    public const TABLE_NAME_LANG = 'lk_carrousel_lang';
    public const TABLE_NAME_CAT = 'lk_carrousel_category_association';
    public const TABLE_NAME_WITH_PREFIX = _DB_PREFIX_ . self::TABLE_NAME;
    public const TABLE_NAME_LANG_WITH_PREFIX = _DB_PREFIX_ . self::TABLE_NAME_LANG;
    private $db;

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        $this->setDb(DB::getInstance());
        parent::__construct($em, $class);
    }

    /**
     * @param string $hookname
     * @param int $langId
     *
     * @return array
     */
    public function getCarrousels($hookName, $langId)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.carrouselLangs', 'cl')
            ->addSelect('cl')
        ;

        if (0 !== $langId) {
            $qb
                ->andWhere('cl.lang = :langId')
                ->setParameter('langId', $langId)
            ;
        }
        $qb
            ->andWhere('c.hook = :hookName')
            ->setParameter('hookName', $hookName)
        ;

        $carrousels = $qb->getQuery()->getResult();

        return $carrousels;
    }

    /**
     * @param int $carrouselId
     * @return array
     */
    public function getCarrouselCategories(int $carrouselId): array
    {
        $categories = [];
        $categories = $this->getDb()->executeS('SELECT id_category FROM '._DB_PREFIX_.self::TABLE_NAME_CAT.' WHERE id_carrousel = '.$carrouselId.'');
        return $categories;
    }

    /**
     * @param int $carrouselId
     * @param int $categoryId
     * @return bool
     */
    public function getCarrouselCategoryById(int $carrouselId, int $categoryId): bool
    {
        $category = $this->getDb()->getValue('SELECT id_category FROM '._DB_PREFIX_.self::TABLE_NAME_CAT.' WHERE id_carrousel = '.$carrouselId.' AND id_category = '.$categoryId.'');
        if ($category) {
            return true;
        }

        return false;
    }

    /**
     * @param int $carrouselId
     * @param int $categoryId
     * @return boolean
     */
    public function addCarrouselCategory(int $carrouselId, int $categoryId): bool
    {
        $result = $this->getDb()->insert(self::TABLE_NAME_CAT, [
            'id_category' => (int) $categoryId,
            'id_carrousel' => (int) $carrouselId
        ]);
        return $result;
    }

    /**
     * @param int $carrouselId
     */
    public function removeCarrouselCategories(int $carrouselId)
    {
        $this->getDb()->delete(self::TABLE_NAME_CAT, 'id_carrousel = '.$carrouselId.'');
    }

    /**
     * @param int $carrouselId
     * @param int $categoryId
     */
    public function removeCarrouselCategory(int $carrouselId, int $categoryId)
    {
        $this->getDb()->delete(self::TABLE_NAME_CAT, 'id_carrousel = '.$carrouselId.' AND id_category = '.$categoryId.'');
    }


    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db): void
    {
        $this->db = $db;
    }


}