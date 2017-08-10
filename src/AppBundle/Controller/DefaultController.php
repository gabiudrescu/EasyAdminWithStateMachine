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
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/apply_transition", name="apply_transition")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function applyTransitionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Post');

        $entity = $repo->find($request->get('entity'));

        if (!$entity) {
            $this->addFlash('danger', sprintf('could not find entity %s', $request->get('entity')));

            return $this->redirectToRoute('admin');
        }

        $factory = $this->get('sm.factory');
        $postSM = $factory->get($entity);

        $initialStatus = $entity->getStatus();

        if (!($transition = $request->get('transition'))) {
            $this->addFlash('danger', sprintf('could not apply transition %s to entity %s',
                $transition,
                $entity->getId()
            ));

            return $this->redirectToRoute('admin');
        }

        if ($postSM->can($transition)) {
            $postSM->apply($transition);

            $this->getDoctrine()->getManager()->flush($entity);

            $this->addFlash('success', sprintf('Successfully transitioned %s from %s to %s through %s',
                    $entity->getId(),
                    $initialStatus,
                    $entity->getStatus(),
                    $transition
                ));

            return $this->redirectToRoute('admin');
        }

        $this->addFlash('danger', 'there is no transition defined');

        return $this->redirectToRoute('admin');
    }
}
