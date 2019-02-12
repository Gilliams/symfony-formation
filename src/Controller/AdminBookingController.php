<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);
                   // Template bidon pour tester une autre pagination
                   //->->setTemplatePath('admin/booking/pagination.html.twig');

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination

        ]);
    }

    /**
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * @return [type] [description]
     */
    public function edit(ObjectManager $manager, Booking $booking, Request $request){
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                "success",
                "La réservation {$booking->getId()} à bien été modifiée"
            );
        }

        return $this->render("admin/booking/edit.html.twig",[
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * @return [type] [description]
     */
    public function delete(ObjectManager $manager, Booking $booking){
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            "success",
            "La réservation a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_bookings_index");
    }
}
