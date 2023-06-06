<?php

namespace App\Form;

use App\Entity\Service;
use App\Service\CheckerInterface;
use App\Service\ConstraintsInterface;
use App\Service\PathInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{

    public function __construct(private CheckerInterface $checker,
                                private PathInterface $path)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie du service :',
                'constraints' => ConstraintsInterface::StringConstraint(3, 25, 'Le nom')])
            ->add('list', CollectionType::class, [
                'label' => 'Indiquer les services :',
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('imageName', TextType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $service = $event->getData();
                $form = $event->getForm();
                $this->setImage($service, $form);
            })
        ;
    }

    private function setImage(Service $service, FormInterface $form)
    {
        $file = $_FILES["image"] ?? [];
        if ($this->checker->checkUploadedFile($file, 2097152, ['png', 'jpeg', 'jpg'], ['image/png', 'image/jpeg', 'image/jpg']))
        {
            $actual_name = $service->getImageName();
            if ($file['name'] === $actual_name)
                return;
            else if ($actual_name)
                unlink($this->path->getServicesImagesDirPath() . $actual_name);

            $ext = $this->checker->fileGetExtension($file);
            do {
                $file_name = uniqid() . ".$ext";
                $file_path = $this->path->getServicesImagesDirPath() . $file_name;
            } while (file_exists($file_path));
            move_uploaded_file($file['tmp_name'], $file_path);
            $service->setImageName($file_name);
        }
        else
            $form->get('imageName')->addError(new FormError("L'image est requise"));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
