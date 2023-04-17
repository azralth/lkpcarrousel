<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use LkInteractive\Back\LkpCarrousel\Entity\Carrousel;
use LkInteractive\Back\LkpCarrousel\Grid\CarrouselGridDefinitionFactory;
use LkInteractive\Back\LkpCarrousel\Grid\CarrouselFilters;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Service\Grid\ResponseBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\ModuleActivated;

/**
 * Class AdminCarrouselController.
 *
 * @ModuleActivated(moduleName="lkpcarrousels", redirectRoute="lkpcarrousel_carrousel_index")
 */
class AdminCarrouselController extends FrameworkBundleAdminController
{
    /**
     * List carrousels
     *
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
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
            '@Modules/lkpcarrousels/views/templates/admin/index.html.twig',
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
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
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
            '@Modules/lkpcarrousels/views/templates/admin/create.html.twig',
            [
                'layoutTitle' => $this->trans('Create carrousel', 'Modules.Lkpcarrousel.Admin'),
                'carrouselForm' => $carrouselForm->createView(),
            ]
        );
    }

    /**
     * Edit carrousel
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     * @param int $carrouselId
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
            '@Modules/lkpcarrousels/views/templates/admin/edit.html.twig',
            [
                'carrouselForm' => $carrouselForm->createView(),
                'layoutTitle' => $this->trans('Carrousel edition', 'Modules.Lkpcarrousel.Admin'),
                'help_link' => false,
            ]
        );
    }

    /**
     * Delete carrousel
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param int $carrouselId
     *
     * @return Response
     */
    public function deleteAction($carrouselId)
    {
        $repository = $this->get(
            'lkinteractive.lkpcarrousel.repository.carrousel'
        );
        try {
            $carrousel = $repository->findOneById($carrouselId);
        } catch (EntityNotFoundException $e) {
            $carrousel = null;
        }
        if (null !== $carrousel) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($carrousel);
            $em->flush();
            $repository->removeCarrouselCategories((int)$carrouselId);
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
                    ['%quote%' => $carrouselId]
                )
            );
        }
        return $this->redirectToRoute('lkpcarrousel_carrousel_index');
    }

    /**
     * Delete bulk quotes
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))", message="Access denied.")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteBulkAction(Request $request)
    {
        $carrouselIds = $request->request->get('carrousel_bulk');
        $repository = $this->get(
            'lkinteractive.lkpcarrousel.repository.carrousel'
        );
        try {
            $carrousels = $repository->findById($carrouselIds);
        } catch (EntityNotFoundException $e) {
            $carrousels = null;
        }
        if (!empty($carrousels)) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($carrousels as $carrousel) {
                $em->remove($carrousel);
                $repository->removeCarrouselCategories($carrousel->getId());
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

    /**
     * @param Request $request
     * @param int $carrouselId
     *
     * @return Response
     */
    public function toggleAction(Request $request, int $carrouselId): Response
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $contentBlock = $entityManager
            ->getRepository(Carrousel::class)
            ->findOneBy(['id' => $carrouselId]);

        if (empty($contentBlock)) {
            return $this->json([
                'status' => false,
                'message' => sprintf('Content block %d doesn\'t exist', $carrouselId)
            ]);
        }

        try {
            $contentBlock->setActive(!$contentBlock->isActive());
            $entityManager->flush();
            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => sprintf(
                    'There was an error while updating the status of content block %d: %s',
                    $carrouselId,
                    $e->getMessage()
                ),
            ];
        }

        return $this->json($response);
    }
}