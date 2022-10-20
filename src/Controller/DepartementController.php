<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DepartementController extends AbstractController
{
    /**
     * @Route("/departement", name="departement_liste")
     */
    public function index(DepartementRepository $departementRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);
        //  return new JsonResponse($departement);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // return new JsonResponse($form->get('region')->getData()->getId());
            if ($departementRepository->findNomDepartementAndRegion(trim($form->get('nomDepartement')->getData()), trim($form->get('region')->getData()->getId()))){
                $this->addFlash('doublon',"Ce departement existe déja");
                return $this->render('departement/departement.html.twig', [
                    'departements' => $departementRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                //$departement->setDate(new \DateTime);
                $entityManager->persist($departement);
                $entityManager->flush();
                $departement = new Departement();
                $form = $this->createForm(DepartementType::class, $departement);
                $this->addFlash('valider',"Enregistrement effectué avec succés");
            }

            /*  return $this->render('departement/departement.html.twig', [
                  'departements' => $departementRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('departement/departement.html.twig', [
            'departements' => $departementRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/departement_edit{id}", name="departement_modifier")
     */
    public function edit(Departement $departement, DepartementRepository $departementRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);
        //  return new JsonResponse($departement);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ind = $departementRepository->findNomDepartementAndRegion(trim($form->get('nomDepartement')->getData()), trim($form->get('region')->getData()->getId()));
            // return new JsonResponse($ind['id'][0]);
            if ($ind and $ind[0]['id'] != $departement->getId()){
                $this->addFlash('doublon',"Ce departement existe déja");
            }else{
                $entityManager->persist($departement);
                $entityManager->flush();
                $departement = new Departement();
                $form = $this->createForm(DepartementType::class, $departement);
                $this->addFlash('valider',"Enregistrement effectué avec success");
                return $this->redirectToRoute('departement_liste');
                /*  return $this->render('departement/departement.html.twig', [
                      'departements' => $departementRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('departement/departementedit.html.twig', [
            'departements' => $departementRepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("departement_delete/{id}", name="departement_supprimer")
     */
    public function delete(Request $request, Departement $departement): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($departement) {
            $faits = $departement->getCommunes()->getValues();
            //return new JsonResponse($faits);
            if(!empty($faits)){
                $this->addFlash('suppression_imp', "Impossible de supprimer ce departement car il est lié à des communes");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($departement);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succés");
            }
        }

        return $this->redirectToRoute('departement_liste');
    }
}
