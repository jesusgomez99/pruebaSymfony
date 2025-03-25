<?php

namespace App\Form;

use App\Entity\Factura;
use App\Entity\Proveedor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('texto')
            ->add('proveedor', EntityType::class, [
                'class' => Proveedor::class,
                'choice_label' => 'nombre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
            'csrf_protection' => true, // Asegúrate de que la protección CSRF esté habilitada
        ]);
    }
}
