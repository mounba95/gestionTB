<?php

namespace App\Controller;

use App\Entity\IndicateurBase;
use App\Form\IndicateurBaseType;
use App\Repository\IndicateurBaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndicateurBaseController extends AbstractController
{
    /**
     * @Route("/indicateurBase", name="indicateurBase_liste")
     */
    public function index(IndicateurBaseRepository $indicateurBaseRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $indicateurBase = new IndicateurBase();
        $form = $this->createForm(IndicateurBaseType::class, $indicateurBase);
        $form->handleRequest($request);
        //  return new JsonResponse($indicateurBase);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($indicateurBaseRepository->findOneByNomIndicateurBase(trim($form->get('nomIndicateurBase')->getData()))){
                $this->addFlash('doublon',"Cette zone existe déja");
                return $this->render('indicateurBase/indicateurBase.html.twig', [
                    'indicateurBases' => $indicateurBaseRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $entityManager->persist($indicateurBase);
                $entityManager->flush();
                $indicateurBase = new IndicateurBase();
                $form = $this->createForm(IndicateurBaseType::class, $indicateurBase);
                $this->addFlash('valider',"Enregistrement effectué avec success");
            }

            /*  return $this->render('indicateurBase/indicateurBase.html.twig', [
                  'indicateurBases' => $indicateurBaseRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('indicateurBase/indicateurBase.html.twig', [
            'indicateurBases' => $indicateurBaseRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/indicateurBase_edit{id}", name="indicateurBase_modifier")
     */
    public function edit(IndicateurBase $indicateurBase, IndicateurBaseRepository $indicateurBaseRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $indicateurBase = new IndicateurBase();
        $form = $this->createForm(IndicateurBaseType::class, $indicateurBase);
        $form->handleRequest($request);
        //  return new JsonResponse($indicateurBase);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $indBase = $indicateurBaseRepository->findOneByNomIndicateurBase(trim($form->get('nomIndicateurBase')->getData()));
            if ($indBase and $indicateurBase->getId() != $indBase->getId()){
                $this->addFlash('doublon',"Cette zone existe déja");
            }else{
                $entityManager->persist($indicateurBase);
                $entityManager->flush();
                $indicateurBase = new IndicateurBase();
                $form = $this->createForm(IndicateurBaseType::class, $indicateurBase);
                $this->addFlash('valider',"Enregistrement effectué avec success");
                return $this->redirectToRoute('indicateurBase_liste');
                /*  return $this->render('indicateurBase/indicateurBase.html.twig', [
                      'indicateurBases' => $indicateurBaseRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('indicateurBase/indicateurBaseedit.html.twig', [
            'indicateurBases' => $indicateurBaseRepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("indicateurBase_delete/{id}", name="indicateurBase_supprimer")
     */
    public function delete(Request $request, IndicateurBase $indicateurBase): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($indicateurBase) {
            $entityManager = $this->getDoctrine()->getManager();
            $indcateur = $indicateurBase->getIndicateurs()->getValues();
            if (!empty($indcateur)){
                $this->addFlash('suppression_imp', "Impossible de supprimer cet indicateur de base car il est lié à des indicateurs");
            }else{
                $entityManager->remove($indicateurBase);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succè");
            }
        }
        return $this->redirectToRoute('indicateurBase_liste');

    }
}
