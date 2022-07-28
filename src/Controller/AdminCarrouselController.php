<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use LkInteractive\Back\Doctrine\Entity\Carrousel;
use LkInteractive\Back\Doctrine\Grid\CarrouselGridDefinitionFactory;
use LkInteractive\Back\Doctrine\Grid\CarrouselFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Security\Annotation\AdminSecurity;

class AdminCarrouselController extends FrameworkBundleAdminController
{
    /**
     * List carrousels
     *
     * @param CarrouselFilters $filters
     *
     * @return Response
     */
    public function indexAction(CarrouselFilters $filters)
    {
        $carrouselGridFactory = $this->get('lkinteractive.lkpcarrousel.grid.factory.carrousel');
        $carrouselGrid = $carrouselGridFactory->getGrid($filters);
        return $this->render(
            '@Modules/lkpcarrousel/views/templates/admin/index.html.twig',
            [
                'enableSidebar' => true,
                'layoutTitle' => $this->trans('Carrousel', 'Modules.Lkpcarrousel.Admin'),
                'layoutHeaderToolbarBtn' => $this->getToolbarButtons(),
                'carrouselGrid' => $this->presentGrid($carrouselGrid),
            ]
        );
    }

    /**
     * Provides filters functionality.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');
        return $responseBuilder->buildSearchResponse(
            $this->get('lkinteractive.lkpcarrousel.grid.definition.factory.carrousel'),
            $request,
            CarrouselGridDefinitionFactory::GRID_ID,
            'lkpcarrousel_carrousel_index'
        );
    }

    /**
     * Create carrousel
     *
     * @AdminSecurity(
     * "is_granted(['create'], request.get('_legacy_controller'))",
     * message="You do not have permission to create Carrousels."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $carrouselFormBuilder = $this->get(
            'lkinteractive.lkpcarrousel.form.builder.carrousel'
        );
        $carrouselForm = $carrouselFormBuilder->getForm();
        $carrouselForm->handleRequest($request);
        $carrouselFormHandler = $this->get(
            'lkinteractive.lkpcarrousel.form.handler.carrousel'
        );
        $result = $carrouselFormHandler->handle($carrouselForm);
        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );
            return $this->redirectToRoute('lkpcarrousel_carrousel_index');
        }
        return $this->render(
            '@Modules/lkpcarrousel/views/templates/admin/create.html.twig',
            [
                'quoteForm' => $carrouselForm->createView(),
            ]
        );
    }

    /**
     * Edit quote
     *
     * @AdminSecurity(
     * "is_granted(['update'], request.get('_legacy_controller'))",
     * message="You do not have permission to edit this."
     * )
     *
     * @param Request $request
     * (c) 2014-2021 Frédéric BENOIST (info@fbenoist.com) Page 91/121
     * @param int $quoteId
     *
     * @return Response
     */
    public function editAction(Request $request, $carrouselId)
    {
        $carrouselFormBuilder = $this->get(
            'lkinteractive.lkpcarrousel.form.builder.carrousel'
        );
        $carrouselForm = $carrouselFormBuilder->getFormFor((int)$carrouselId);
        $carrouselForm->handleRequest($request);
        $carrouselFormHandler = $this->get(
            'lkinteractive.lkpcarrousel.form.handler.carrousel'
        );
        $result = $carrouselFormHandler->handleFor((int)$carrouselId, $carrouselForm);
        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash(
                'success',
                $this->trans(
                    'Successful update.',
                    'Admin.Notifications.Success'
                )
            );
            return $this->redirectToRoute('lkpcarrousel_carrousel_index');
        }
        return $this->render(
            '@Modules/lkpcarrousel/views/templates/admin/edit.html.twig',
            [
                'carrouselForm' => $carrouselForm->createView(),
            ]
        );
    }

    /**
     * Delete quote
     *
     * @AdminSecurity(
     * "is_granted(['delete'], request.get('_legacy_controller'))",
     * message="You do not have permission to delete this."
     * )
     *
     * @param int $quoteId
     *
     * @return Response
     */
    public function deleteAction($quoteId)
    {
        $repository = $this->get(
            'lkinteractive.lkpcarrousel.repository.carrousel'
        );
        try {
            $quote = $repository->findOneById($quoteId);
        } catch (EntityNotFoundException $e) {
            $quote = null;
        }
        if (null !== $quote) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($quote);
            $em->flush();
            $this->addFlash(
                'success',
                $this->trans(
                    'Successful deletion.',
                    'Admin.Notifications.Success'
                )
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find carrousel %carrousel%',
                    'Modules.Lkpcarrousel.Admin',
                    ['%quote%' => $quoteId]
                )
            );
        }
        return $this->redirectToRoute('lkpcarrousel_carrousel_index');
    }

    /**
     * Delete bulk quotes
     *
     * @AdminSecurity(
     * "is_granted(['delete'], request.get('_legacy_controller'))",
     * message="You do not have permission to delete this."
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteBulkAction(Request $request)
    {
        $quoteIds = $request->request->get('carrousel_bulk');
        $repository = $this->get(
            'lkinteractive.lkpcarrousel.repository.carrousel'
        );
        try {
            $quotes = $repository->findById($quoteIds);
        } catch (EntityNotFoundException $e) {
            $quotes = null;
        }
        if (!empty($quotes)) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($quotes as $quote) {
                $em->remove($quote);
            }
            $em->flush();
            $this->addFlash(
                'success',
                $this->trans(
                    'The selection has been successfully deleted.',
                    'Admin.Notifications.Success'
                )
            );
        }
        return $this->redirectToRoute('lkpcarrousel_carrousel_index');
    }

    /**
     * @return array[]
     */
    private function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => $this->trans(
                    'Add new carrousel',
                    'Modules.Lkpcarrousel.Admin'
                ),
                'icon' => 'add_circle_outline',
                'href' => $this->generateUrl('lkpcarrousel_carrousel_create'),
            ]
        ];
    }
}