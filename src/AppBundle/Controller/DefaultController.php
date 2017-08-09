<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find(1);

        if  (!$post){
            $post = new Post();
            $post->setTitle('lorem ipsum');
            $post->setBody('bla bla');

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        $factory = $this->get('sm.factory');
        $postSM = $factory->get($post, 'simple');

        if ($transition = $request->get('transition')) {
            if ($postSM->can($transition)) {
                $postSM->apply($transition);

                $this->getDoctrine()->getManager()->flush($post);
            }
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'entity' => $post,
        ]);
    }
}
