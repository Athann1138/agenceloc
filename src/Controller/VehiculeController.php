<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Service\NameFile;
use App\Form\VehiculeType;

use App\Repository\VehiculeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/vehicule')]
class VehiculeController extends AbstractController
{
    #[Route('/', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VehiculeRepository $vehiculeRepository, NameFile $nameFile): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            
                $imageFile=$form->get('imageFile')->getData();
                /*dd($imageFile); 
                si upload fichier->$imageFile est un objet issu de la class UploadedFile
                si pas d'upload->$imageFile est null */
                //TTT de l'upload
                if($imageFile)//si l'image n'est pas null => $imageFile est un objet
                {
                    
                }
                
                $vehicule->setCreatedAt(new \DateTimeImmutable());
                $vehiculeRepository->add($vehicule, true);

                $this->addFlash("success","véhicule ajouté");
                return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        

            return $this->renderForm('vehicule/new.html.twig', [
                'vehicule' => $vehicule,
                'form' => $form,
            ]);

                    $nomImage=$nameFile->renameFile($imageFile);
                    $imageFile->move(
                        $this->getParameter('imageVehicule'),$nomImage);
    
                    $vehicule->setImage($nomImage);
                
                

            

            
        }

        return $this->renderForm('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
       
        
    }

    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET'])]
     public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
     public function edit(Request $request, Vehicule $vehicule, VehiculeRepository $vehiculeRepository, NameFile $nameFile): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $imageFile=$form->get('imageFile')->getData();
                //traitement de l'upload
            if($imageFile){
            $nomImage=$nameFile->renameFile($imageFile);
                    $imageFile->move(
                        $this->getParameter('imageVehicule'),
                        $nomImage
                    );

                    if($vehicule->getImage())
                    {
                        //fonction php !!unlink()!!==>permet de supprimer un fichier
                        unlink($this->getParameter('imageVehicule')."/".$vehicule->getImage());
                        // syntaxe de unlink ---> unlink(projet/public/images/vehicule  /  20220804....png)
                    }
                    $vehicule->setImage($nomImage);
                }
            
            $vehiculeRepository->add($vehicule, true);

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, VehiculeRepository $vehiculeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $vehiculeRepository->remove($vehicule, true);
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}