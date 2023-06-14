<?php

namespace App\Form;

use App\Entity\Message;
use App\Service\ConstraintsInterface;
use App\Service\NormalizeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :',
                'constraints' => ConstraintsInterface::StringConstraint(1, 100, "Votre nom")])
            ->add('phone', TextType::class, [
                'label' => 'Telephone :',
                'constraints' => ConstraintsInterface::PhoneConstraint('Le numéro')])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'constraints' => ConstraintsInterface::EmailConstraint('Votre email')])
            ->add('message', TextareaType::class, [
                'label' => 'Message :',
                'constraints' => ConstraintsInterface::StringConstraint(1, 500, 'Le message')
            ])
            ->add('date', HiddenType::class, [
                'mapped' => false, 'required' => false])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $message = $event->getData();
                $this->normalizeObject($message);
                $event->setData($message);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }

    public function normalizeObject(array &$message)
    {
        $message['name'] = NormalizeInterface::normalizeText($message['name']);
        $message['message'] = NormalizeInterface::normalizeText($message['message']);
        $message['phone'] = NormalizeInterface::normalizePhone($message['phone']);
    }
}
