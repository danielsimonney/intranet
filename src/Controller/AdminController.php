<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Form\AssignType;
use App\Form\NoteType;
use App\Form\RoleType;
use App\Repository\UserRepository;
use App\Repository\SubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * Fonction qui va montrer la liste de tt les users de laplication
     * @Route("/admin/index", name="admin_index")
     */
    public function index(UserRepository $userRepository)
    {
        $users=$userRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * Fonction montrant un user spécifique
     * @Route("/admin/users/show/{id}", name="user_profile")
     */
    public function showUser(User $user){
        return $this->render('admin/user.html.twig', [
            'user' => $user,
        ]);
    }


    
    /**
     * Fonction qui va montrer la liste de tt les users de laplication
     * @Route("/admin/users/assign/{id}", name="assign_index")
     */
    public function AssignUsers(User $user,Request $request){
        $form=$this->createForm(AssignType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($user->getSubjects());die;
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }
        return $this->render('admin/assign.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Changer le status d'un user
     *
     * @Route("/admin/users/change/{id}", name="change_status")
     */
    public function changeStatus(User $user,Request $request){
$form=$this->createForm(RoleType::class,$user);
$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        }
        return $this->render('admin/status.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function modifyGrade(){

    }


    /**
     * Assigner un user à un subject
     *
     * @return void
     */
    public function assignUser(){

    }
}
