<?php

// src/Form/SubscriptionType.php

namespace App\Form;

use App\Entity\Subscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscription', EntityType::class, [
                'class' => Subscription::class,
                'choice_label' => 'title',
            ]);
    }
}