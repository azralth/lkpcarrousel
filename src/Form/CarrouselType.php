<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Form;

use LkPCarrousels;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\CategoryChoiceTreeType;
use PrestaShopBundle\Form\Admin\Type\ChoiceCategoriesTreeType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class CarrouselType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TranslatableType::class,
                [
                    'label' => $this->trans('Title', 'Modules.LkPCarrousels.Admin'),
                    'translation_domain' => 'Modules.LkPCarrousels.Admin',
                    'constraints' => [
                        new DefaultLanguage([
                            'message' => $this->trans(
                                'The field %field_name% is required at least in your default language.',
                                'Admin.Notifications.Error',
                                [
                                    '%field_name%' => sprintf(
                                        '"%s"',
                                        $this->trans('Content', 'Modules.LkPCarrousels.Admin')),
                                ]
                            ),
                        ]),
                    ],
                ]
            )
            ->add(
                'hook',
                ChoiceType::class,
                [
                    'label' => $this->trans('Display hook', 'Modules.LkPCarrousels.Admin'),
                    'help' => $this->trans('Select Hook to display the carrousel', 'Modules.LkPCarrousels.Admin'),
                    'choices' => LkPCarrousels::LK_HOOK_AVALAIBLE,
                ]
            )
            ->add(
                'nb_product',
                IntegerType::class,
                [
                    'label' => $this->trans('Product number', 'Modules.LkPCarrousels.Admin'),
                    'help' => $this->trans('Number of product Hook to display', 'Modules.LkPCarrousels.Admin'),
                ]
            )
            ->add(
                'nb_product_to_show',
                IntegerType::class,
                [
                    'label' => $this->trans('Product to show', 'Modules.LkPCarrousels.Admin')
                ]
            )
            ->add(
                'categories',
                CategoryChoiceTreeType::class,
                [
                    'label' => $this->trans('Categories', 'Admin.Catalog.Feature'),
                    'multiple' => true,
                ]
            )
            ->add(
                'btn_title',
                TranslatableType::class,
                [
                    'required'   => false,
                    'label' => $this->trans('Button title', 'Modules.LkPCarrousels.Admin'),
                    'translation_domain' => 'Modules.LkPCarrousels.Admin',
                    'constraints' => [
                        new DefaultLanguage([
                            'message' => $this->trans(
                                'The field %field_name% is required at least in your default language.',
                                'Admin.Notifications.Error',
                                [
                                    '%field_name%' => sprintf(
                                        '"%s"',
                                        $this->trans('Content', 'Modules.LkPCarrousels.Admin')),
                                ]
                            ),
                        ]),
                    ],
                ]
            )
            ->add(
                'order_by',
                ChoiceType::class,
                [
                    'label' => $this->trans('Order by', 'Modules.LkPCarrousels.Admin'),
                    'help' => $this->trans('Default sorting', 'Modules.LkPCarrousels.Admin'),
                    'choices' => LkPCarrousels::LK_ORDER_BY,
                ]
            )
            ->add(
                'sort_order',
                ChoiceType::class,
                [
                    'label' => $this->trans('Sort order', 'Modules.LkPCarrousels.Admin'),
                    'choices' => [
                        'Desc' => 'desc',
                        'Asc' => 'asc',
                    ],
                ]
            )
            ->add(
                'show_bullet',
                SwitchType::class,
                [
                    'required'   => false,
                    'label' => $this->trans('Show bullet', 'Modules.LkPCarrousels.Admin'),
                    'help' => $this->trans('Show the bullet navigation', 'Modules.LkPCarrousels.Admin'),
                ]
            )
            ->add(
                'show_arrrow',
                SwitchType::class,
                [
                    'required'   => false,
                    'label' => $this->trans('Show arrow', 'Modules.LkPCarrousels.Admin'),
                    'help' => $this->trans('Show the arrow navigation', 'Modules.LkPCarrousels.Admin'),
                ]
            )
            //Object : checkbox
            ->add(
                'active',
                SwitchType::class,
                [
                    'required'   => true,
                    'label' => 'Enable',
                ]
            );
    }
}