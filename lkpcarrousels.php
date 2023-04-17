<?php
/**
 *  Copyright (C) Lk Interactive - All Rights Reserved.
 *
 *  This is proprietary software therefore it cannot be distributed or reselled.
 *  Unauthorized copying of this file, via any medium is strictly prohibited.
 *  Proprietary and confidential.
 *
 * @author    Lk Interactive <contact@lk-interactive.fr>
 * @copyright 2022.
 * @license   Commercial license
 */

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductPresenter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once dirname(__FILE__).'/vendor/autoload.php';

class LkPCarrousels extends Module implements WidgetInterface
{
    public const LK_HOOK_AVALAIBLE = [
        'Display Home' => 'displayHome',
        'Display Home 2' => 'displayHome2',
        'Display Top' => 'displayTopColumn',
        'Right column' => 'displayRightColumn',
        'Left column' => 'displayLeftColumn',
        'Wrapper top' => 'displayWrapperTop',
        'Wrapper bottom' => 'displayWrapperBottom',
        'Footer product' => 'displayFooterProduct',
        'Product extra content' => 'displayProductExtraContent',
        'Product additional info' => 'displayProductAdditionalInfo',
        'Footer' => 'displayFooter',
        'Before footer' => 'displayFooterBefore',
        'Shopping cart' => 'displayShoppingCart',
        'Order details' => 'displayOrderDetail',
    ];

    public const LK_ORDER_BY = [
        'Name' => 'name',
        'Position in category' => 'position',
        'Price' => 'price',
        'Date add' => 'date_add',
        'Date update' => 'date_upd',
    ];

    private $templateFile;

    public function __construct()
    {
        $this->name = 'lkpcarrousels';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Lk Interactive';
        $this->need_instance = 0;

        $tabNames = [];
        foreach (Language::getLanguages() as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Product Category Carrousel', [], 'Modules.LkPCarrousels.Admin', $lang['locale']);
        }
        $this->tabs = [
            [
                'route_name' => 'lkpcarrousel_carrousel_index',
                'class_name' => 'AdminCarrouselController',
                'visible' => true,
                'name' => $tabNames,
                'parent_class_name' => 'AdminParentThemes',
                'wording' => $this->trans('Carrousel list', array(), 'Modules.LkPCarrousels.Admin'),
                'wording_domain' => 'Modules.LkPCarrousels.Admin',
            ],
        ];

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Lk Interactive - products carrousels', array(), 'Modules.LkPCarrousels.Admin');
        $this->description = $this->trans('Create simple products carrousels', array(), 'Modules.LkPCarrousels.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.6', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:lkpcarrousels/views/templates/hook/lkpcarrousels.tpl';
    }

    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';
        Configuration::updateValue('LK_ENABLE_SLICK', false);
        return parent::install() && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';
        Configuration::deleteByName('LK_ENABLE_SLICK');
        return parent::uninstall();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLkPCarrouselsConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Enable Slick library', [], 'Modules.LkPCarrousels.Admin'),
                        'name' => 'LK_ENABLE_SLICK',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'lk_enable_true_slick',
                                'value' => true,
                                'label' => $this->trans('Yes',[],'Modules.LkPCarrousels.Admin')
                            ),
                            array(
                                'id' => 'lk_enable_false_slick',
                                'value' => false,
                                'label' => $this->trans('No',[],'Modules.LkPCarrousels.Admin')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        return $fields['LK_ENABLE_SLICK'] = Configuration::get('LK_ENABLE_SLICK');
    }

    protected function postProcess()
    {
        if (Tools::isSubmit('submitLkPCarrouselsConf')) {
            Configuration::updateValue('LK_ENABLE_SLICK', Tools::getValue('LK_ENABLE_SLICK'));
            return $this->displayConfirmation($this->l('Save all settings.'));
        }

        return '';
    }

    public function getContent()
    {
        if (((bool)Tools::isSubmit('submitLkPCarrouselsConf')) == true) {
            return $this->postProcess();
        }

        return $this->renderForm();
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->templateFile);
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $repository = $this->get('lkinteractive.lkpcarrousel.repository.carrousel');
        $langId = $this->context->language->id;

        $allCarrousels = [];

        $carrousels = $repository->getCarrousels($hookName,$langId);
        if (count($carrousels) > 0) {
            foreach ($carrousels as $carrousel) {
                $carrouselId = $carrousel->getId();
                $categoriesId = $repository->getCarrouselCategories($carrouselId);
                $products = $this->getCarrouselProducts($categoriesId, $carrousel->getNbProduct(), $carrousel->getOrderBy(), $carrousel->getSortOrder());
                $allCarrousels[] = [
                    'show_arrow' => $carrousel->isShowArrow() ? 'true' : 'false',
                    'show_dots' => $carrousel->isShowBullet() ? 'true' : 'false',
                    'carrousel' => $carrousel,
                    'products' => $products,
                    'allProductsLink' => $this->getAllProductLink($categoriesId)
                ];
            }
        }

        return ['carrousels' => $allCarrousels];
    }

    /**
     * @param array $categoryIds
     * @param int $nProducts
     * @return array
     */
    private function getCarrouselProducts($categoryIds, $nProducts, $orderBy, $sortOrder)
    {
        if ($nProducts < 0) {
            $nProducts = 8;
        }

        $translator = $this->context->getTranslator();
        $productSearchContext = new ProductSearchContext($this->context);

        // Prepare query
        $query = new ProductSearchQuery();
        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
            ->setSortOrder(new SortOrder('product', $orderBy, $sortOrder))
        ;

        foreach ($categoryIds as $id_category) {
            $category = new Category($id_category['id_category']);
            $searchProvider = new CategoryProductSearchProvider($translator, $category);
            $result = $searchProvider->runQuery($productSearchContext, $query);
            foreach ($result->getProducts() as $product) {
                $products[] = $product;
            }
        }

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    private function getAllProductLink($categoryIds)
    {
        if (count($categoryIds) == 1) {
            return $this->context->link->getCategoryLink($categoryIds[0]['id_category']);
        }
    }

    public function hookDisplayHeader()
    {
        if (Configuration::get('LK_ENABLE_SLICK')) {
            $this->context->controller->registerStylesheet('lk-carousel-slick', 'modules/' . $this->name . '/views/css/slick.css' , ['media' => 'all', 'priority' => 150]);
            $this->context->controller->registerStylesheet('lk-carousel-slick-theme', 'modules/' . $this->name . '/views/css/slick-theme.css', ['media' => 'all', 'priority' => 150]);
            $this->context->controller->registerJavascript('lk-carrousel-slick', 'modules/' . $this->name . '/views/js/slick.min.js', ['position' => 'bottom', 'priority' => 150]);
        }
    }
}
