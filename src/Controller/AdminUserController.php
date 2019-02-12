<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     */
    public function index(UserRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(User::class)
                   ->setPage($page);

        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination
        ]);

    }

    /**
     * Permet d'afficher le formulaire d'edition de commentaire
     *
     * @Route("/admin/users/{id}/edit", name="admin_user_edit")
     *
     * @param  ObjectManager $manager [description]
     * @param  Comment       $comment [description]
     * @param  Request       $request [description]
     * @return [type]                 [description]
     */
    public function edit(ObjectManager $manager, User $user,Request $request){
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                "success",
                "Le commentaire {$user->getFullName()} à bien été modifié"
            );
        }

        return $this->render("admin/user/edit.html.twig",[
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/users/{id}/delete", name="admin_user_delete")
     *
     * @return [type] [description]
     */
    public function delete(ObjectManager $manager, User $user){
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            "success",
            "L'utilisateur a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_users_index");
    }
}
