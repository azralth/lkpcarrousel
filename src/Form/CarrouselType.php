<?php
declare(strict_types=1);

namespace LkInteractive\Back\Doctrine\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarrouselType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('title', TextType::class, [
                'label' => 'Author name',
                'help' => 'Author name (e.g. Alexandre Dumas).',
                'translation_domain' => 'Modules.Acbodoctrine.Admin',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => 255]
                        ),
                    ]),
                    new NotBlank(),
                ]
            ])*/
            ->add('title', TranslatableType::class, [
                'label' => 'Title',
                'help' => 'Carrousel Title',
                'translation_domain' => 'Modules.lkpcarrousel.Admin',
                'constraints' => [
                    new DefaultLanguage([
                        'message' => $this->trans(
                            'The field %field_name% is required at least in your default language.',
                            'Admin.Notifications.Error',
                            [
                                '%field_name%' => sprintf(
                                    '"%s"',
                                    $this->trans('Content', 'Modules.lkpcarrousel.Admin')),
                            ]
                        ),
                    ]),
                ],
            ]);
    }
}