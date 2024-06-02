<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GeneratePdfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'URL:',
                'required' => true,
            ])
            ->add('fileName', TextType::class, [
                'label' => 'File Name:',
                'required' => true,
            ]);
    }
}
