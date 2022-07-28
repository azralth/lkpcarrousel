<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Grid;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CarrouselGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'carrousel';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans(
            'Carrousel',
            [],
            'Modules.Lkpcarrousel.Admin'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new BulkActionColumn('bulk'))
                ->setOptions([
                    'bulk_field' => 'id_carrousel',
                ])
            )
            ->add((new DataColumn('id_carrousel'))
                ->setName($this->trans(
                    'ID',
                    [],
                    'Admin.Global'
                ))
                ->setOptions([
                    'field' => 'id_carrousel',
                ]))
            ->add((new DataColumn('title'))
                ->setName($this->trans(
                    'Title',
                    [],
                    'Modules.Lkpcarrousel.Admin'
                ))
                ->setOptions([
                    'field' => 'author',
                ]))
            ->add((new ActionColumn('actions'))
                ->setName($this->trans(
                    'Actions',
                    [],
                    'Admin.Global'
                ))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans(
                                'Edit',
                                [],
                                'Admin.Actions'
                            ))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'lkpcarrousel_carrousel_edit',
                                'route_param_name' => 'carrouselId',
                                'route_param_field' => 'id_carrousel',
                                'clickable_row' => true,
                            ]))
                        ->add((new SubmitRowAction('delete'))
                            ->setName($this->trans(
                                'Delete',
                                [],
                                'Admin.Actions'
                            ))
                            ->setIcon('delete')
                            ->setOptions([
                                'method' => 'DELETE',
                                'route' => 'lkpcarrousel_carrousel_delete',
                                'route_param_name' => 'carrouselId',
                                'route_param_field' => 'id_carrousel',
                                'confirm_message' => $this->trans(
                                    'Delete selected item?',
                                    [],
                                    'Admin.Notifications.Warning'
                                ),
                            ]))
                ]));
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_carrousel', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans(
                            'ID',
                            [],
                            'Admin.Global'
                        ),
                    ],
                ])
                ->setAssociatedColumn('id_carrousel'))
            ->add((new Filter('author', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans(
                            'author',
                            [],
                            'Modules.Lkpcarrousel.Admin'
                        ),
                    ],
                ])
                ->setAssociatedColumn('author'))
            ->add((new Filter('content', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans(
                            'Content',
                            [],
                            'Modules.Lkpcarrousel.Admin'
                        ),
                    ],
                ])
                ->setAssociatedColumn('content'))
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id'
                    ,
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'lkpcarrousel_carrousel_index',
                ])
                ->setAssociatedColumn('actions'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans(
                    'Refresh list',
                    [],
                    'Admin.Advparameters.Feature'
                ))
                ->setIcon('refresh'))
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans(
                    'Show SQL query',
                    [],
                    'Admin.Actions'
                ))
                ->setIcon('code'))
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans(
                    'Export to SQL Manager',
                    [],
                    'Admin.Actions'
                ))
                ->setIcon('storage'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getBulkActions()
    {
        return (new BulkActionCollection())
            ->add((new SubmitBulkAction('delete_bulk'))
                ->setName($this->trans('Delete selected', [], 'Admin.Actions'))
                ->setOptions([
                    'submit_route' => 'lkpcarrousel_carrousel_bulk_delete',
                    'confirm_message' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning'),
                ]));
    }
}