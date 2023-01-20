<?php

namespace App\Controller;

use App\Entity\ZoneImpCE;
use App\Form\ZoneImpCEType;
use App\Repository\ZoneImpCERepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZoneImpCEController extends AbstractController
{
    /**
     * @Route("/zoneImpCE", name="zoneImpce_liste")
     */
    public function index(ZoneImpCERepository $zoneImpCERepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $zoneImpCE = new ZoneImpCE();
        $form = $this->createForm(ZoneImpCEType::class, $zoneImpCE);
        $form->handleRequest($request);
        //  return new JsonResponse($zoneImpCE);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($zoneImpCERepository->findOneByNomZoneImpCE(trim($form->get('nomZoneImpCE')->getData()))){
                $this->addFlash('doublon',"Cette zone existe déja");
                return $this->render('zoneImpCE/zoneImpce.html.twig', [
                    'zoneImpces' => $zoneImpCERepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $entityManager->persist($zoneImpCE);
                $entityManager->flush();
                $zoneImpCE = new ZoneImpCE();
                $form = $this->createForm(ZoneImpCEType::class, $zoneImpCE);
                $this->addFlash('valider',"Enregistrement effectué avec success");
            }

            /*  return $this->render('zoneImpCE/zoneImpce.html.twig', [
                  'zoneImpces' => $zoneImpCERepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('zoneImpCE/zoneImpce.html.twig', [
            'zoneImpces' => $zoneImpCERepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/zoneImpCE_edit{id}", name="zoneImpce_modifier")
     */
    public function edit(ZoneImpCE $zoneImpCE, ZoneImpCERepository $zoneImpCERepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $zoneImpCE = new ZoneImpCE();
        $form = $this->createForm(ZoneImpCEType::class, $zoneImpCE);
        $form->handleRequest($request);
        //  return new JsonResponse($zoneImpCE);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $zi = $zoneImpCERepository->findOneByNomZoneImpCE(trim($form->get('nomZoneImpCE')->getData()));
            if ($zi and $zi->getId() != $zoneImpCE->getId()){
                $this->addFlash('doublon',"Cette zone existe déja");
            }else{
                $entityManager->persist($zoneImpCE);
                $entityManager->flush();
                $zoneImpCE = new ZoneImpCE();
                $form = $this->createForm(ZoneImpCEType::class, $zoneImpCE);
                $this->addFlash('valider',"Enregistrement effectué avec succé");
                return $this->redirectToRoute('zoneImpce_liste');
                /*  return $this->render('zoneImpCE/zoneImpce.html.twig', [
                      'zoneImpces' => $zoneImpCERepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('zoneImpCE/zoneImpceedit.html.twig', [
            'zoneImpces' => $zoneImpCERepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("zoneImpCE_delete/{id}", name="zoneImpce_supprimer")
     */
    public function delete(Request $request, ZoneImpCE $zoneImpCE): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($zoneImpCE) {
            $zone = $zoneImpCE->getCentres()->getValues();
            if (!empty($zone)){
                $this->addFlash('suppression_imp', "Impossible de supprimer cette zone d'implementation car il est lié à des centres");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($zoneImpCE);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succè");
            }
        }

        return $this->redirectToRoute('zoneImpce_liste');
    }
}
