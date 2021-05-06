<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class LearningController extends AbstractController
{
    #[Route('/learning', name: 'learning')]
    public function index(): Response
    {
        return $this->render('learning/index.html.twig', [
            'controller_name' => 'LearningController',
        ]);
    }

    #[Route('/learning/about-becode', name: 'about-me')]
    public function aboutMe(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->has('name')) {
            $name = $session->get('name');
        }

        else{
            return $this->showMyName($request);
        }

        return $this->render('learning/about-me.html.twig', [
            'controller_name' => 'LearningController',
            'name' => $name,
        ]);
    }

    #[Route('/', name: 'show-my-name')]
    public function showMyName(Request $request): Response
    {

        $name = 'Unknown';
        $session = $request->getSession();

        if ($session->has('name')) {
            $name = $session->get('name');
        }

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('change-my-name'))
            ->setMethod('POST')
            ->add('name', TextType::class)
            ->getForm();

        return $this->render('learning/show-my-name.html.twig', [
            'controller_name' => 'LearningController',
            'form' => $form->createView(),
            'name' => $name,
        ]);
    }

    #[Route('/learning/change-my-name', name: 'change-my-name', methods: ['POST'])]
    public function changeMyName(Request $request): Response
    {
        if(!isset($session)) {
            $session = $request->getSession();
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('change-my-name'))
            ->setMethod('POST')
            ->add('name', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $session->set('name', $data['name']);

            return $this->redirectToRoute('show-my-name');
        }
    }
}
