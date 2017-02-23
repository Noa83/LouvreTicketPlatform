<?php

namespace Louvre\TicketPlatformBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate', TextType::class)
            ->add('ticketType', ChoiceType::class, array(
                'choices' => array(
                    'Billet Journée' => 'Billet Journée',
                    'Billet Demie-journée' => 'Billet Demie-journée',
                )
            ))
            ->add('numberOfTickets', NumberType::class)
            ->add('email', EmailType::class)
            ->add('validation', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketPlatformBundle\Model\FormModelStep1'
        ));
    }
}