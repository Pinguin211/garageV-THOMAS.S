<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Option;
use App\Service\CheckerInterface;
use App\Service\ConstraintsInterface;
use App\Service\PathInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private CheckerInterface $checker,
                                private PathInterface $path)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = new \DateTime();
        $year = (int)$date->format('Y'); // Anné maximum de la voiture

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonces :',
                'constraints' => ConstraintsInterface::StringConstraint(8, 25, 'Le titre')
            ])
            ->add('imagesNames', TextType::class,
                ['required' => false, 'mapped' => false])
            ->add('year', IntegerType::class, [
                'label' => 'Année de mise en circulation :',
                'constraints' => ConstraintsInterface::NumberConstraint(1900, $year, 'L\'année')
            ])
            ->add('kilometers', IntegerType::class, [
                'label' => 'Le nombres de kilometres du véhicule :',
                'constraints' => ConstraintsInterface::NumberConstraint(0, 999999, 'La distance')
            ])
            ->add('fuelType', ChoiceType::class, [
                'label' => 'Type d\'énergie du véhicule :',
                'choices' => [
                    Car::getFuelNameByType(Car::FUEL_DIESEL_TYPE) => Car::FUEL_DIESEL_TYPE,
                    Car::getFuelNameByType(Car::FUEL_ESSENCE_TYPE) => Car::FUEL_ESSENCE_TYPE,
                    Car::getFuelNameByType(Car::FUEL_ELECTRICITY_TYPE) => Car::FUEL_ELECTRICITY_TYPE,
                ], 'expanded' => true
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix de vente souhaitez : (en €)',
                'constraints' => ConstraintsInterface::NumberConstraint(1, 999999, 'Le prix')
            ])
            ->add('options', ChoiceType::class,
                [
                    'label' => 'Options du véhicule :',
                    'required' => false,
                    'mapped' => false,
                    'choices' => $this->getOptionsChoices(),
                    'multiple' => true,
                    'expanded' => true
                ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $car = $event->getData();
                $form = $event->getForm();
                $this->setOptionsData($car, $form);
            })
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $car = $event->getData();
                $form = $event->getForm();
                $this->setCarOptions($car, $form);
                $this->setCarImagesNames($car, $form);
            })
        ;
    }

    //Retourne l'array du choix des options possible
    private function getOptionsChoices(): array
    {
        $options = $this->entityManager->getRepository(Option::class)->findBy([], ['name' => 'ASC']);
        $choices = [];
        foreach ($options as $option)
            $choices[$option->getName()] = $option->getId();
        return $choices;
    }

    //Set les options deja presente dans le vehicule sur true
    private function setOptionsData(Car $car, FormInterface $form)
    {
        $data = [];
        foreach ($car->getOptions() as $option) {
            $data[] = $option->getId();
        }
        $form->get('options')->setData($data);
    }

    //Ajoute les options de la voiture selon le formulaire
    private function setCarOptions(Car $car, FormInterface $form) {
        $ids = $form->get('options')->getData();
        $options = [];
        foreach ($ids as $id)
            $options[] = $this->entityManager->getRepository(Option::class)->findOneBy(['id' => $id]);
        $car->setOptions($options);
    }

    //Ajoute les images upload sur le serveur, et ajoute leurs nom dans l'objet
    private function setCarImagesNames(Car $car, FormInterface $form)
    {
        $arr_places = [1, 2, 3, 4, 5];
        $arr_res = [];
        foreach ($arr_places as $place)
            $arr_res[$place] = $this->addImages($car, $place);
        if (in_array(true, $arr_res))
            $this->removeImages($car, $arr_res);
        else
            $form->get('imagesNames')->addError(new FormError("Vous devez avoir au minimum une image"));

    }

    //Effectue les remplacement des nouvelle images
    private function addImages(Car $car, int $place)
    {
        $file = $_FILES["image_$place"] ?? [];
        if ($this->checker->checkUploadedFile($file, 2097152, ['png', 'jpeg', 'jpg'], ['image/png', 'image/jpeg', 'image/jpg']))
        {
            $actual_name = $car->getImageName($place);
            if ($file['name'] === $actual_name)
                return true;
            else if ($actual_name)
                unlink($this->path->getCarImagesDirPath($place) . $actual_name);

            $ext = $this->checker->fileGetExtension($file);
            do {
                $file_name = uniqid() . ".$ext";
                $file_path = $this->path->getCarImagesDirPath() . $file_name;
            } while (file_exists($file_path));
            move_uploaded_file($file['tmp_name'], $file_path);
            $car->setImageName($place, $file_name);
            return true;
        }
        else
            return false;
    }

    //Efface les anciennes image qui on etait effacer
    public function removeImages(Car $car, array $arr_res)
    {
        foreach ($arr_res as $place => $value)
        {
            if (!$value && ($name = $car->getImageName($place))) {
                unlink($this->path->getCarImagesDirPath() . $name);
                $car->removeImageName($place);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
