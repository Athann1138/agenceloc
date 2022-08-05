<?php

namespace App\Controller;

use App\Entity\Members;
use App\Form\MembersType;
use App\Repository\MembersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestion/members')]
class GestionMembersController extends AbstractController
{
    #[Route('/', name: 'app_gestion_members_index', methods: ['GET'])]
    public function index(MembersRepository $membersRepository): Response
    {
        return $this->render('gestion_members/index.html.twig', [
            'members' => $membersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_gestion_members_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MembersRepository $membersRepository): Response
    {
        $member = new Members();
        $form = $this->createForm(MembersType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membersRepository->add($member, true);

            return $this->redirectToRoute('app_gestion_members_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestion_members/new.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestion_members_show', methods: ['GET'])]
    public function show(Members $member): Response
    {
        return $this->render('gestion_members/show.html.twig', [
            'member' => $member,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gestion_members_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Members $member, MembersRepository $membersRepository): Response
    {
        $form = $this->createForm(MembersType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membersRepository->add($member, true);

            return $this->redirectToRoute('app_gestion_members_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestion_members/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gestion_members_delete', methods: ['POST'])]
    public function delete(Request $request, Members $member, MembersRepository $membersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$member->getId(), $request->request->get('_token'))) {
            $membersRepository->remove($member, true);
        }

        return $this->redirectToRoute('app_gestion_members_index', [], Response::HTTP_SEE_OTHER);
    }
}