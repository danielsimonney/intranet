<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\AssignType;
use App\Form\NoteType;
use App\Form\RoleType;
use App\Form\SubjectType;
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
    public function index(UserRepository $userRepository,SubjectRepository $subjectRepository)
    {
        $users=$userRepository->findAll();
        $subjects=$subjectRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'subjects'=>$subjects

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
     * Undocumented function
     *
     * @Route("/admin/subject/assign/{id}", name="subject_assign")
     */
    public function assignSubject(Subject $subject,Request $request){
        $form=$this->createForm(SubjectType::class,$subject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($user->getSubjects());die;
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin_index');
        }
        return $this->render('admin/assign.html.twig', [
            'form' => $form->createView(),
        ]); 
        
    }

/**
 * Undocumented function
 *
 * @Route("/admin/subject/new", name="subject_new")
 */
    public function newSubject(Request $request){
        $manager = $this->em = $this->getDoctrine()->getManager();
        $subject=new Subject;
        $form=$this->createForm(SubjectType::class,$subject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($subject);
            $manager->flush();           
            return $this->redirectToRoute('admin_index');
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
}
