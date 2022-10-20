<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends AbstractController
{
    /**
     * @Route("/region", name="region_liste")
     */
    public function index(RegionRepository $regionRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        //  return new JsonResponse($region);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($regionRepository->findOneByNomRegion(trim($form->get('nomRegion')->getData()))){
                $this->addFlash('doublon',"Cette region existe déja");
                return $this->render('region/region.html.twig', [
                    'regions' => $regionRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $entityManager->persist($region);
                $entityManager->flush();
                $region = new Region();
                $form = $this->createForm(RegionType::class, $region);
                $this->addFlash('valider',"Enregistrement effectué avec succès");

            }

            /*  return $this->render('region/region.html.twig', [
                  'regions' => $regionRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('region/region.html.twig', [
            'regions' => $regionRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/region_edit{id}", name="region_modifier")
     */
    public function edit(Region $region, RegionRepository $regionRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        //  return new JsonResponse($region);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tp = $regionRepository->findOneByNomRegion(trim($form->get('nomRegion')->getData()));
            if ($tp and $tp->getId() != $region->getId()){
                $this->addFlash('doublon',"Ce type existe déja");
            }else{
                $entityManager->persist($region);
                $entityManager->flush();
                $region = new Region();
                $form = $this->createForm(RegionType::class, $region);
                $this->addFlash('valider',"Enregistrement effectué avec succès");
                return $this->redirectToRoute('region_liste');
                /*  return $this->render('region/region.html.twig', [
                      'regions' => $regionRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('region/regionedit.html.twig', [
            'regions' => $regionRepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("region_delete/{id}", name="region_supprimer")
     */
    public function delete(Request $request, Region $region): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($region) {
            $dep = $region->getDepartements()->getValues();
            if (!empty($dep)){
                $this->addFlash('suppression_imp', "Impossible de supprimer cette région car elle est liée à des departements");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($region);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succès");
            }
        }

        return $this->redirectToRoute('region_liste');
    }
}
