<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/article", name="article_index")
     */
    public function indexAction()
    {
        $article = $this->getDoctrine()->getRepository(Article::class);
        $articles = $article->findAll();

        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/create", name="article_new")
     *
     * @param Request $request
     *
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/create.html.twig', ['article' => $article,
            'form' => $form->createView(), ]);
    }

    /**
     * @Route("/show/{id}", name="article_show")
     *
     * @param $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        return $this->render('entity/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/update/{id}", name="article_edit")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $article = $em->getRepository(Article::class)->find($id);
        $editForm = $this->createForm(ArticleType::class, $article);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('article_index', ['id' => $id]);
        }

        return $this->render('article/update.html.twig', ['article' => $article,
            'edit_form' => $editForm->createView(), ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('article_index');
    }
}
