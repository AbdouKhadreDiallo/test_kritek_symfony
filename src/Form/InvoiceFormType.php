<?php

namespace App\Form;

use App\Entity\Facture;
use App\Form\InvocelinesFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class InvoiceFormType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateFacture', DateType::class, ['attr' => ['class' => 'form-control']])
            ->add('customerId', NumberType::class, ['attr' => ['class' => 'form-control']])
            ->add('invoicelines', CollectionType::class, [
                'entry_type' => InvocelinesFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary'], 'label' => 'new invoce'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}