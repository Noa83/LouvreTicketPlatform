<?php

namespace Louvre\TicketPlatformBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OwnerStep2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('form2', CollectionType::class, array(
                'entry_type'   => FormStep2Type::class,
                'allow_add'    => true
            ))
            ->add('validation',      SubmitType::class);
        //bonne pratique: ajouter le validation dans le twig directement et l'enlever de la.

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketPlatformBundle\Model\OwnerStep2'
        ));
    }
}
