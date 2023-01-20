<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Entity\FaitEC;
use App\Form\CentreType;
use App\Form\RapportTableauType;
use App\Form\FaitECType;
use App\Repository\CentreRepository;
use App\Repository\FaitECRepository;
use App\Repository\RegionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CentreController extends AbstractController
{

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil(CentreRepository $centreRepository, RegionRepository $regionRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $regions = $regionRepository->findAll();
        $date = new \DateTime;
        $an   = $date->format('Y');
        $anne = (int) $an;
        $indicateurNaissanceFille = 1;
        $indicateurNaissanceGarçon = 3;
        foreach ($regions as $key => $region) {
            //Requete Nbr Fille par Region
           $nbrNaissanceFille =  $centreRepository->getDonneeParAnneeParIndicateurParRegion($indicateurNaissanceFille, $anne, $region->getId());

           //Requette Nbr Garçon par Region
           $nbrNaissanceGarçon =  $centreRepository->getDonneeParAnneeParIndicateurParRegion($indicateurNaissanceGarçon, $anne, $region->getId());


           if($nbrNaissanceFille){
                $listeNbrNaissanceFille[$region->getNomRegion()]['nbrNaissanceFille'] = $nbrNaissanceFille[0]['nbrNaissanceFille'];
           }else{
                $listeNbrNaissanceFille[$region->getNomRegion()]['nbrNaissanceFille'] = 0;
           }

           if($nbrNaissanceGarçon){
            $listeNbrNaissanceGarçon[$region->getNomRegion()]['nbrNaissanceGarçon'] = $nbrNaissanceGarçon[0]['nbrNaissanceGarçon'];
       }else{
            $listeNbrNaissanceGarçon[$region->getNomRegion()]['nbrNaissanceGarçon'] = 0;
       }
        }
        return $this->render('accueil.html.twig', [
            'data' => $centreRepository->getCentresByType(),
        ]);
    }

    /**
     * @Route("/centre", name="centre_liste")
     */
    public function index(CentreRepository $centreRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);
        //  return new JsonResponse($centre);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // return new JsonResponse($form->get('region')->getData()->getId());
            if ($centreRepository->findOneByNomCE(trim($form->get('nomCE')->getData()))){
                $this->addFlash('doublon',"Ce centre existe déja");
                return $this->render('centre/centre.html.twig', [
                    'centres' => $centreRepository->findAll(), 'form' => $form->createView(),
                ]);
            }else{
                $centre->setDate(new \DateTime);
                $entityManager->persist($centre);
                $entityManager->flush();
                $centre = new Centre();
                $form = $this->createForm(CentreType::class, $centre);
                $this->addFlash('valider',"Enregistrement effectué avec succés");
            }

            /*  return $this->render('centre/centre.html.twig', [
                  'centres' => $centreRepository->findAll(), 'form' => $form->createView(),
              ]);*/
        }

        return $this->render('centre/centre.html.twig', [
            'centres' => $centreRepository->findAll(), 'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/centre_edit{id}", name="centre_modifier")
     */
    public function edit(Centre $centre, CentreRepository $centreRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);
        //  return new JsonResponse($centre);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ind = $centreRepository->findOneByNomCE(trim($form->get('nomCE')->getData()));
            // return new JsonResponse($ind['id'][0]);
            if ($ind and $ind->getId() != $centre->getId()){
                $this->addFlash('doublon',"Ce centre existe déja");
            }else{
                $entityManager->persist($centre);
                $entityManager->flush();
                $centre = new Centre();
                $form = $this->createForm(CentreType::class, $centre);
                $this->addFlash('valider',"Enregistrement effectué avec succés");
                return $this->redirectToRoute('centre_liste');
                /*  return $this->render('centre/centre.html.twig', [
                      'centres' => $centreRepository->findAll(), 'form' => $form->createView(),
                  ]);*/
            }

        }

        return $this->render('centre/centreedit.html.twig', [
            'centres' => $centreRepository->findAll(), 'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/centre_show{id}", name="centre_detail")
     */
    public function view(Centre $centre, CentreRepository $centreRepository, Request $request, FaitECRepository $faitECRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $faitEC = new FaitEC();
        $form = $this->createForm(FaitECType::class, $faitEC);
        $form->handleRequest($request);
        //  return new JsonResponse($centre);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $ind = $centreRepository->findOneByNomCE(trim($form->get('nomCE')->getData()));
            // return new JsonResponse($ind['id'][0]);
            $faitEC->setDate(new \DateTime)->setCentre($centre);
            $entityManager->persist($faitEC);
            $entityManager->flush();
            $faitEC = new FaitEC();
            $form = $this->createForm(FaitECType::class, $faitEC);
            $this->addFlash('valider',"Enregistrement effectué avec succés");
        }

        $faitEC = $faitECRepository->findFaitEC($request->get('id'));
        return $this->render('centre/centreshow.html.twig', ['faitECs'=> $faitEC,
            'form' => $form->createView(), 'centre' => $centre,
        ]);
    }

    /**
     * @Route("/ind_edit{id}", name="ind_modifier")
     */
    public function viewedit(FaitEC $faitEC, CentreRepository $centreRepository, Request $request, FaitECRepository $faitECRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $faitEC = new FaitEC();
        $form = $this->createForm(FaitECType::class, $faitEC);
        $form->handleRequest($request);
        $centre = $centreRepository->findOneById($faitEC->getCentre()->getId());
        //  return new JsonResponse($centre);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $ind = $centreRepository->findOneByNomCE(trim($form->get('nomCE')->getData()));
            // return new JsonResponse($ind['id'][0]);
            $entityManager->persist($faitEC);
            $entityManager->flush();
            $faitEC = new FaitEC();
            $form = $this->createForm(FaitECType::class, $faitEC);
            $this->addFlash('valider',"Enregistrement effectué avec succés");
        }

        $faitECs = $faitECRepository->findFaitEC($centre->getId());
        return $this->render('centre/centreshow.html.twig', ['faitECs'=> $faitECs,
            'form' => $form->createView(), 'centre' => $centre,
        ]);
    }



    /**
     * @Route("centre_delete/{id}", name="centre_supprimer")
     */
    public function delete(Request $request, Centre $centre): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($centre) {
            $faits = $centre->getFaitECs()->getValues();
            //return new JsonResponse($faits);
            if(!empty($faits)){
                $this->addFlash('suppression_imp', "Impossible de supprimer ce centre car il est lié à des données");
            }else{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($centre);
                $entityManager->flush();
                $this->addFlash('suppression', "Suppression éffectuée avec succés");
            }
        }

        return $this->redirectToRoute('centre_liste');
    }

    /**
     * @Route("ind_delete/{id}", name="ind_supprimer")
     */
    public function deleteFaits(Request $request, FaitEC $faitEC): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $idCentre = $faitEC->getCentre()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($faitEC);
        $entityManager->flush();
        $this->addFlash('suppression', "Suppression éffectuée avec succés");

        return $this->redirectToRoute('centre_detail',array('id' => $idCentre));
    }

     /**
     * @Route("/tableau", name="tableau_bord")
     */
    public function tableauB(CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('tableau/tableaubord.html.twig', [
            'data' => $centreRepository->getDashboardData(),
        ]);
    }

     /**
     * @Route("/tableau_filtrage", name="tableau_bord_filtrage")
     */
    public function tableauBF(Request $request,CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(RapportTableauType::class, null);
        $form->handleRequest($request);
        $result = 0;
        if ($form->isSubmitted() && $form->isValid()) {
            $indicateur = $form->get('indicateur')->getData();
            if ($indicateur){
                $dataregion =  $centreRepository->getDashboardDataByRegion($indicateur->getId());
                $datadepartement =  $centreRepository->getDashboardDataByDepartement($indicateur->getId());
                $datacommune =  $centreRepository->getDashboardDataByCommune($indicateur->getId());
                $datacentre =  $centreRepository->getDashboardDataByCentre($indicateur->getId());
              //  return new JsonResponse($dataregion);
                return $this->render('tableau/tableaubordfiltrage.html.twig', [
                    'datacentre' => $datacentre, 'form' => $form->createView(), 'result' => 1, 'dataregion' => $dataregion, 'datadepartement' => $datadepartement, 'datacommune' => $datacommune
                ]);
            }
        }
        return $this->render('tableau/tableaubordfiltrage.html.twig', [
          'form' => $form->createView(), 'result' =>$result
        ]);
    }


    //Widget

    
    public function getNaissanceByAnneeEncour(CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //indicateur naissance
        $nFille = 1;
        $nGarçon = 3;
        $date       = new \DateTime;
        $an   = $date->format('Y');
        $anne = (int) $an;
        $donnee = $centreRepository->getNaissanceByAnneeEncour($nFille, $nGarçon, $anne);
        if ($donnee and $donnee[0]['nombre'] != null) {
            $response = new Response($donnee[0]['nombre']);
            return $response;
        }else{
            return new Response(0);
        }
    }

    public function getMariageByAnneeEncour(CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //indicateur mariage 
        $indicateur = 4;
        $date       = new \DateTime;
        $an   = $date->format('Y');
        $anne = (int) $an;
        $donnee = $centreRepository->getNombreFaitByAnneeEncour($indicateur, $anne);
        if ($donnee and $donnee[0]['nombre'] != null) {
            $response = new Response($donnee[0]['nombre']);
            return $response;
        }else{
            return new Response(0);
        }
    }

    public function getDeceByAnneeEncour(CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //indicateur dece 
        $indicateur = 5;
        $date       = new \DateTime;
        $an   = $date->format('Y');
        $anne = (int) $an;
        $donnee = $centreRepository->getNombreFaitByAnneeEncour($indicateur, $anne);
        if ($donnee and $donnee[0]['nombre'] != null) {
            $response = new Response($donnee[0]['nombre']);
            return $response;
        }else{
            return new Response(0);
        }
    }

    public function getDivorceByAnneeEncour(CentreRepository $centreRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //indicateur divorce 
        $indicateur = 5;
        $date       = new \DateTime;
        $an   = $date->format('Y');
        $anne = (int) $an;
        $donnee = $centreRepository->getNombreFaitByAnneeEncour($indicateur, $anne);
        if ($donnee and $donnee[0]['nombre'] != null) {
            $response = new Response($donnee[0]['nombre']);
            return $response;
        }else{
            return new Response(0);
        }
    }
}
