<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Message;
use App\Form\MessageType;
use App\Service\AutomaticInterface;
use App\Service\CheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OccasionsController extends AbstractController
{
    #[Route('/occasions', name: 'app_occasions')]
    public function index(AutomaticInterface $auto, EntityManagerInterface $entityManager): Response
    {

        $min_price = $entityManager->getRepository(Car::class)->findOneBy([], ['price' => 'ASC'])?->getPrice();
        $max_price = $entityManager->getRepository(Car::class)->findOneBy([], ['price' => 'DESC'])?->getPrice();
        $min_klm = $entityManager->getRepository(Car::class)->findOneBy([], ['kilometers' => 'ASC'])?->getKilometers();
        $max_klm = $entityManager->getRepository(Car::class)->findOneBy([], ['kilometers' => 'DESC'])?->getKilometers();
        $min_year = $entityManager->getRepository(Car::class)->findOneBy([], ['year' => 'ASC'])?->getYear();
        $max_year = $entityManager->getRepository(Car::class)->findOneBy([], ['year' => 'DESC'])?->getYear();

        return $this->render('occasions/index.html.twig', [
            'auto' => $auto->getParams(),
            'min_price' => $min_price ?? 0,
            'max_price' => $max_price ?? 10000,
            'min_klm' => $min_klm ?? 0,
            'max_klm' => $max_klm ?? 100000,
            'min_year' => $min_year ?? 0,
            'max_year' => $max_year ?? 3000
        ]);
    }

    #[Route('/occasions_cars')]
    public function cars(EntityManagerInterface $entityManager): Response
    {
        $title = self::getFilter('title', 'string', '');
        $min_price = self::getFilter('min_price', 'numeric', 0);
        $max_price = self::getFilter('max_price', 'numeric', 1000000);
        $min_klm = self::getFilter('min_klm', 'numeric', 0);
        $max_klm = self::getFilter('max_klm', 'numeric', 1000000);
        $min_year = self::getFilter('min_year', 'numeric', 0);
        $max_year = self::getFilter('max_year', 'numeric', 3000);

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('car')
            ->from(Car::class, 'car')
            ->where($queryBuilder->expr()->like('car.title', ':title'))
            ->andWhere('car.price >= :minPrice')
            ->andWhere('car.price <= :maxPrice')
            ->andWhere('car.kilometers >= :minKlm')
            ->andWhere('car.kilometers <= :maxKlm')
            ->andWhere('car.year >= :minYear')
            ->andWhere('car.year <= :maxYear')
            ->setParameter('title', "%$title%")
            ->setParameter('minPrice', $min_price)
            ->setParameter('maxPrice', $max_price)
            ->setParameter('minKlm', $min_klm)
            ->setParameter('maxKlm', $max_klm)
            ->setParameter('minYear', $min_year)
            ->setParameter('maxYear', $max_year);

       $cars = $queryBuilder->getQuery()->getResult();

        $res = [];
        foreach ($cars as $car) {
            $res[] = [
                'id' => $car->getId(),
                'title' => $car->getTitle(),
                'images' => $car->getImagesNames(),
                'year' => $car->getYear(),
                'kilometers' => $car->getKilometers(),
                'fuel' => $car->getFuelName(),
                'price' => $car->getPrice()
            ];
        }
        return new Response(json_encode($res));

    }

    private static function getFilter(string $name, string $type, mixed $default): mixed
    {
        if (!CheckerInterface::checkArrayDataStatic($_POST, $name, $type))
            return $default;
        else
            return $_POST[$name];
    }

    #[Route('/occasions/car')]
    public function cars_details(Request $request, AutomaticInterface $automatic,
                                 EntityManagerInterface $entityManager, CheckerInterface $checker): Response
    {
        if (!$checker->checkArrayData($_GET, 'id', 'numeric') ||
            !($car = $entityManager->getRepository(Car::class)->findOneBy(['id' => $_GET['id']])))
            return $this->redirectToRoute('app_occasions');

        $message = new Message();
        if (isset($_GET['message']))
            $message->setMessage($_GET['message']);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDate(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('app_message', [
                'title' => 'Message envoyé',
                'message' => "Le personnel du garage vous recontactera dans les plus bref délais",
                'redirect_app' => 'app_occasions'
            ]);
        }

        return $this->render('occasions/car.html.twig', [
            'auto' => $automatic->getParams(),
            'car' => $car,
            'form' => $form->createView()
        ]);


    }

}
