<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfessorController extends AbstractController
{
    /**
     * @Route("/professor/new/grade/{id_user}/{id_subject}", name="new_grade")
     */
    public function addNote($id_user, $id_subject, Request $request, UserRepository $userRepository, SubjectRepository $subjectRepository)
    {
        // Tout d'abord je vérifie que c'est bien soit un professeur soit un administrateur
        if ($this->hasPermission($id_subject)) {
            // Ensuite je vérifie que le user a bien cette matière dans ces sujets
            
            $manager = $this->em = $this->getDoctrine()->getManager();
            $note = new Note;
            $user = $userRepository->findOneBy(array("id" => $id_user));
            $subject = $subjectRepository->findOneBy(array("id" => $id_subject));
            $note->setUser($user);
            $note->setSubject($subject);
            $form = $this->createForm(NoteType::class, $note);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($note);
                $manager->flush();
                if($this->getUser()->isGranted("ROLE_ADMIN")){
                    return $this->redirectToRoute('user_profile', ['id' => $id_user]);
                }else{
                    return $this->redirectToRoute('subject_show', ['id' => $id_subject]);
                }
            }
            return $this->render('professor/index.html.twig', [
                'subject' => $subject,
                'user' => $user,
                'form' => $form->createView(),
            ]);
            
        } else {
            $this->addFlash('warning', 'You dont have the rigths for this ticket');
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * Undocumented function
     *
     * @Route("/professor/edit/grade/{id_note}/{id_subject}", name="edit_note")
     */
    public function editNote($id_note, $id_subject,NoteRepository $noteRepository,Request $request)
    {
        if ($this->hasPermission($id_subject)) {
            $note = $noteRepository->findOneBy(array("id" => $id_note));
            $id_user=$note->getUser()->getId();
            $form=$this->createForm(NoteType::class,$note);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                
                return $this->redirectToRoute('new_grade',['id_user'=>$id_user,'id_subject'=>$id_subject]);
            }
    
            return $this->render('professor/edit.html.twig', [
                'note' => $note,
                'form' => $form->createView(),
            ]);
        } else {
            $this->addFlash('warning', 'You dont have the rigths for this ticket');
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * Undocumented function
     *
     * @Route("/professor/suppress/grade/{id_note}/{id_subject}", name="suppress_note")
     */
    public function suppressNote($id_note, $id_subject, NoteRepository $noteRepository, Request $request)
    {
        if ($this->hasPermission($id_subject)) {
            $note = $noteRepository->findOneBy(array("id" => $id_note));
            $id_user=$note->getUser()->getId();
            if ($this->isCsrfTokenValid('delete' . $note->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($note);
                $entityManager->flush();
            } else {
                die("Protection csrf non validé veuillez supprimer la note depuis le site seuleument !!");
            }

            return $this->redirectToRoute('new_grade',['id_user'=>$id_user,'id_subject'=>$id_subject]);
        } else {
            $this->addFlash('warning', 'You dont have the rigths for this subject');
            return $this->redirectToRoute('homepage');
        }

    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function hasSubject($id_user,$id_subject,UserRepository $userRepository){
        $user=$userRepository->find($id_user);
        foreach ($user->getSubjects() as $value) {
            if($value->getId()==$id_subject){
                return true;
            }
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    private function hasPermission($id)
    {
        $user = $this->getUser();
        if ($user->isGranted(User::admin)) {
             return true;
        }
        foreach ($this->getUser()->getSubjects() as $key => $value) {
            if ($value->getId() == $id) {
                return true;
            }
        }
        return false;
    }
}
