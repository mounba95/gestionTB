<?php

namespace App\Controller;

use App\Entity\TypeCE;
use App\Form\TypeCEType;
use App\Repository\TypeCERepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeCEController extends AbstractController
{
    /**
     * @Route("/typeCE", name="typece_liste")
     */
    public function index(TypeCERepository $typeCERepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $typeCE = new TypeCE();
        $form = $this->createForm(TypeCEType::class, $typeCE);
        $form->handleRequest($request);
        //  return new JsonResponse($typeCE);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($typeCERepository->findOneByNomTypeCE(trim($form->get('nomTypeCE')->getData()))){
                $this->addFlash('doublon',"Ce type existe déja");
                return $this->render('typeCE/typece.html.twig', [
                    'typeces' => $typeCERepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $entityManager->persist($typeCE);
                $entityManager->flush();
                $typeCE = new TypeCE();
                $form = $this->createForm(TypeCEType::class, $typeCE);
                $this->addFlash('valider',"Enregistrement effectué avec success");

            }

            /*  return $this->render('typeCE/typece.html.twig', [
                  'typeces' => $typeCERepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('typeCE/typece.html.twig', [
            'typeces' => $typeCERepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/typeCE_edit{id}", name="typece_modifier")
     */
    public function edit(TypeCE $typeCE, TypeCERepository $typeCERepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $typeCE = new TypeCE();
        $form = $this->createForm(TypeCEType::class, $typeCE);
        $form->handleRequest($request);
        //  return new JsonResponse($typeCE);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tp = $typeCERepository->findOneByNomTypeCE(trim($form->get('nomTypeCE')->getData()));
            if ($tp and $tp->getId() != $typeCE->getId()){
                $this->addFlash('doublon',"Ce type existe déja");
            }else{
                $entityManager->persist($typeCE);
                $entityManager->flush();
                $typeCE = new TypeCE();
                $form = $this->createForm(TypeCEType::class, $typeCE);
                $this->addFlash('valider',"Enregistrement effectué avec success");
                return $this->redirectToRoute('typece_liste');
                /*  return $this->render('typeCE/typece.html.twig', [
                      'typeces' => $typeCERepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('typeCE/typeceedit.html.twig', [
            'typeces' => $typeCERepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("typeCE_delete/{id}", name="typece_supprimer")
     */
    public function delete(Request $request, TypeCE $typeCE): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($typeCE) {
            $type = $typeCE->getZoneImpCE()->getValues();
            if (!empty($type)){
                $this->addFlash('suppression_imp', "Impossible de supprimer ce type car il est lié à des centres");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($typeCE);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succè");
            }
        }

        return $this->redirectToRoute('typece_liste');
    }
}
