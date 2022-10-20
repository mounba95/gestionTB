<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneType;
use App\Repository\CommuneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommuneController extends AbstractController
{
    /**
     * @Route("/commune", name="commune_liste")
     */
    public function index(CommuneRepository $communeRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);
        //  return new JsonResponse($commune);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // return new JsonResponse($form->get('region')->getData()->getId());
            if ($communeRepository->findNomCommuneAndDepartement(trim($form->get('nomCommune')->getData()), trim($form->get('departement')->getData()->getId()))){
                $this->addFlash('doublon',"Cette commune existe déja");
                return $this->render('commune/commune.html.twig', [
                    'communes' => $communeRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                //$commune->setDate(new \DateTime);
                $entityManager->persist($commune);
                $entityManager->flush();
                $commune = new Commune();
                $form = $this->createForm(CommuneType::class, $commune);
                $this->addFlash('valider',"Enregistrement effectué avec succés");
            }

            /*  return $this->render('commune/commune.html.twig', [
                  'communes' => $communeRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('commune/commune.html.twig', [
            'communes' => $communeRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/commune_edit{id}", name="commune_modifier")
     */
    public function edit(Commune $commune, CommuneRepository $communeRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);
        //  return new JsonResponse($commune);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ind = $communeRepository->findNomCommuneAndDepartement(trim($form->get('nomCommune')->getData()), trim($form->get('departement')->getData()->getId()));
            // return new JsonResponse($ind['id'][0]);
            if ($ind and $ind[0]['id'] != $commune->getId()){
                $this->addFlash('doublon',"Cette commune existe déja");
            }else{
                $entityManager->persist($commune);
                $entityManager->flush();
                $commune = new Commune();
                $form = $this->createForm(CommuneType::class, $commune);
                $this->addFlash('valider',"Enregistrement effectué avec succés");
                return $this->redirectToRoute('commune_liste');
                /*  return $this->render('commune/commune.html.twig', [
                      'communes' => $communeRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('commune/communeedit.html.twig', [
            'communes' => $communeRepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("commune_delete/{id}", name="commune_supprimer")
     */
    public function delete(Request $request, Commune $commune): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($commune) {
            $faits = $commune->getCentres()->getValues();
            //return new JsonResponse($faits);
            if(!empty($faits)){
                $this->addFlash('suppression_imp', "Impossible de supprimer cette commune car elle est liée à des centres");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($commune);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succés");
            }
        }

        return $this->redirectToRoute('commune_liste');
    }
}
