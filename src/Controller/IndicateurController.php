<?php

namespace App\Controller;

use App\Entity\Indicateur;
use App\Form\IndicateurType;
use App\Repository\IndicateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndicateurController extends AbstractController
{
    /**
     * @Route("/indicateur", name="indicateur_liste")
     */
    public function index(IndicateurRepository $indicateurRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $indicateur = new Indicateur();
        $form = $this->createForm(IndicateurType::class, $indicateur);
        $form->handleRequest($request);
        //  return new JsonResponse($indicateur);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // return new JsonResponse($form->get('indicateurBase')->getData()->getId());
            if ($indicateurRepository->findNomIndcateurAndIndicateurBase(trim($form->get('nomIndicateur')->getData()), trim($form->get('indicateurBase')->getData()->getId()))){
                $this->addFlash('doublon',"Cette zone existe déja");
                return $this->render('indicateur/indicateur.html.twig', [
                    'indicateurs' => $indicateurRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $indicateur->setDate(new \DateTime);
                $entityManager->persist($indicateur);
                $entityManager->flush();
                $indicateur = new Indicateur();
                $form = $this->createForm(IndicateurType::class, $indicateur);
                $this->addFlash('valider',"Enregistrement effectué avec success");
            }

            /*  return $this->render('indicateur/indicateur.html.twig', [
                  'indicateurs' => $indicateurRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('indicateur/indicateur.html.twig', [
            'indicateurs' => $indicateurRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/indicateur_edit{id}", name="indicateur_modifier")
     */
    public function edit(Indicateur $indicateur, IndicateurRepository $indicateurRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $indicateur = new Indicateur();
        $form = $this->createForm(IndicateurType::class, $indicateur);
        $form->handleRequest($request);
        //  return new JsonResponse($indicateur);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ind = $indicateurRepository->findNomIndcateurAndIndicateurBase(trim($form->get('nomIndicateur')->getData()), trim($form->get('indicateurBase')->getData()->getId()));
          // return new JsonResponse($ind['id'][0]);
            if ($ind and $ind[0]['id'] != $indicateur->getId()){
                $this->addFlash('doublon',"Cet indicateur existe déja");
            }else{
                $entityManager->persist($indicateur);
                $entityManager->flush();
                $indicateur = new Indicateur();
                $form = $this->createForm(IndicateurType::class, $indicateur);
                $this->addFlash('valider',"Enregistrement effectué avec success");
                return $this->redirectToRoute('indicateur_liste');
                /*  return $this->render('indicateur/indicateur.html.twig', [
                      'indicateurs' => $indicateurRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('indicateur/indicateuredit.html.twig', [
            'indicateurs' => $indicateurRepository->findAll(), 'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("indicateur_delete/{id}", name="indicateur_supprimer")
     */
    public function delete(Request $request, Indicateur $indicateur): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($indicateur) {
            $faits = $indicateur->getFaitECs()->getValues();
            //return new JsonResponse($faits);
            if(!empty($faits)){
                $this->addFlash('suppression_imp', "Impossible de supprimer cet indicateur car il est lié à des faits");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($indicateur);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succés");
            }
        }

        return $this->redirectToRoute('indicateur_liste');
    }
}
