<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerController extends AbstractController
{
    /**
     *@Route("/desactive", name="desactive_user")
     */
    public function ActionDesactivation(Request $request, ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $user = $em->getRepository(User::class)->find($id);
        $user->setIsActive(0);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'desactive',
            "Ce compte a été desactive avec sucess !!!"
        );
        return $this->redirectToRoute('liste_user');
    }
    /**
     *@Route("/active", name="active_user")
     */
    public function ActionActive(Request $request, ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $user = $em->getRepository(User::class)->find($id);
        $user->setIsActive(1);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'active',
            "Ce compte a été active avec sucess !!!"
        );
        return $this->redirectToRoute('liste_user');
    }

    /**
     *@Route("/reset_password", name="reset_Password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $session = $this->getUser();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($session->getid());
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $oldPassword = $request->get('reset_password')['password'];
            if ($passwordEncoder->isPasswordValid($user, $oldPassword)) {
                $newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getSecondPassword());
                $user->setPassword($newEncodedPassword);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('compte_edit',['id'=>$user->getId() ]);
            } else {
                $form->addError(new FormError('Ancien mot de passe incorrecte'));
            }
        }

        return $this->render('user_manager/Reset.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    function generateRandomString($length)
    {
        $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321'.time();  //la suite de caractere est concatenee a l'heure courante, mesurée en secondes

        //echo $characters;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = $length; $i > 0; $i--)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     *@Route("/resetPassword", name="resetPassword")
     */
    public function passwordReset(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $user = $em->getRepository(User::class)->find($id);
        $mdp = $this->generateRandomString(6);
        $encoded = $encoder->encodePassword($user, $mdp);
        $user->setPassword($encoded);
        $manager->persist($user);
        $manager->flush();
        $this->addFlash(
            'notice',
            "Le mot de passe de ".$user->getNomUtilisateur().
            "a été renitialisé le nouveau mot de passe est : " .$mdp
        );
        return $this->redirectToRoute('liste_user');
    }
}
