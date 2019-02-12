<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comments_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                   ->setLimit(5)
                   ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition de commentaire
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @param  ObjectManager $manager [description]
     * @param  Comment       $comment [description]
     * @param  Request       $request [description]
     * @return [type]                 [description]
     */
    public function edit(ObjectManager $manager, Comment $comment,Request $request){
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                "success",
                "Le commentaire {$comment->getId()} à bien été modifié"
            );
        }

        return $this->render("admin/comment/edit.html.twig",[
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @return [type] [description]
     */
    public function delete(ObjectManager $manager, Comment $comment){
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            "success",
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_comments_index");
    }

}
