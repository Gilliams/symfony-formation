<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Service\PaginationService;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * {page}
     * {page <>} = requirements
     * {page <\d+>} = requirements numeric only et nombreux ou pas ...
     * {page <\d+>?} = optionnel
     * {page <\d+>?1} = optionnel et 1 par defaut
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        // Methode find : permet de retrouver un enregistrement par son identifiant
        // $ad = $repo->find(304);

        // $ad = $repo->findBy([
        //     'title' => 'Annonce corrigée !',
        //     'id' => 302
        // ]);

        // Prend 4 params: 1 Criteres, 2 Orders, 3 Limite, 4 Offset(début)
        // $ads = $repo->findBy([], [], 5, 0);


        // $limit = 10;

        // Pagination ...
        // $start = $page * $limit - $limit;
        // 1 * 10 = 10 - 10 = 0
        // 2 * 10 = 20 - 10 = 10

        // $total = count($repo->findAll());

        // ceil arrondi au dessus 3.4 => 4
        // $pages = ceil($total / $limit);

        $pagination->setEntityClass(Ad::class)
                   ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param  Ad     $ad [description]
     * @return [type]     [description]
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param  Ad            $ad      [description]
     * @param  ObjectManager $manager [description]
     * @return [Response]                 [description]
     */
    public function delete(Ad $ad, ObjectManager $manager){
        if(count($ad->getBookings()) > 0){
            $this->addFlash(
                "warning",
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations !"
            );
        }else{
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                "success",
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute("admin_ads_index");
    }
}
