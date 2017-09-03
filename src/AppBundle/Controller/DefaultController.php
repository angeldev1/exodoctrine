<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
    * @Route("/post/save")
    */
    public function postSaveAction()
    {
        // Crée une nouvelle entité (objet php)
        $post = new Post;
        $post->setTitle('Les lapins crétins');
        $post->setSummary('Ubi Soft');
        $post->setAuthorName('La Grosse Aventure est une comédie');
        $post->setCreatedAt ('22/08/17');

        // On récupère l'Entity Manager de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Indique au Manager que l'on souhaite (éventuellement) sauvegarder l'objet en BDD (pas de requête !)
        // Gère l'objet $post
        $em->persist($post);

        // Exécute la requête
        $em->flush();

        return new Response('Nouveau post sauvegardé avec l\'ID '.$post->getId());
    }

    /**
    * @Route("/post/show/{id}", name="post_show")
    */
    public function postShowAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if(!$post) {
            throw $this->createNotFoundException('ID non trouvé : '.$id);
        }

        dump($post);
        die();
    }

    /**
     * @Route("/post/view/{id}", name="post_view")
     *
     * Ici on utilise le ParamConverter "avec signature automatique"
     */
    public function postViewAction(Post $post)
    {
        dump($post);
        die();
    }

    /**
     * @Route("/post/display/{id}", name="post_display")
     *
     * Ici on utilise le ParamConverter "avec signature automatique"
     * Pour "intercepter" l'erreur 404, on met $post à null par défaut
     */
    public function postDisplayAction(Post $post = null)
    {
        if($post === null) {
            throw $this->createNotFoundException('Post non trouvé.');
        }

        dump($post);
        die();
    }

    /**
     * @Route("/post/repository")
     */
    public function postRepositoryAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');

        // find => par son id (clé primaire)
        $post = $repository->find(4);
        dump($post);

        // nom de méthode dynamique, basé que un champ (nom de la colonne)
        $post = $repository->findOneByTitle('Mario Kart');
        dump($post);

        // nom de méthode dynamique, basé que un champ (nom de la colonne)
        // findBy = 1 à plusieurs résultats
        $posts = $repository->findByEditor('Ubi Soft');
        dump($posts);
        $posts = $repository->findByAuthorName('jc');
        dump($posts);

        // findAll = tous
        $posts = $repository->findAll();
        dump($posts);

        // Recherche avec critère
        $post = $repository->findOneBy([
            'editor' => 'Ubi Soft',
            'title' => 'Assassin\'s Creed'
        ]);
        dump($post);

        // Recherche avec critère
        $posts = $repository->findBy(
            ['editor' => 'Ubi Soft'],
            ['title' => 'ASC']
        );
        dump($posts);

        return new Response('<html><body></body></html>');
    }

    /**
     * @Route("/post/update/{id}", name="post_update")
     */
    public function postUpdateAction(Post $post)
    {
        $post->setTitle('Assassin\'s Creed : Unity');

        // On récupère l'Entity Manager de Doctrine
        $em = $this->getDoctrine()->getManager();
        // A noter : $em->persist() pas utile ici car récupéré via Doctrine
        // On sauve en bdd
        $em->flush();

        dump($post);
        die();
    }
}
