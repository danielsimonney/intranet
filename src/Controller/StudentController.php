<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use App\Service\NotesOrganiser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * Homepage 
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $user=$this->getUser();
         dump($user->getMoyenne());
        return $this->render('student/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Montre le détail d'une matière
     *
     * @Route("/subjects/{id}", name="subject_show")
     */
    public function subjectShow(Subject $subject,SubjectRepository $subjectRepo){
        $myUser=$this->getUser();
        $professor=[];
        $student=[];
        foreach ($subject->getUser() as $user) {
            if($user->isGranted($user::professor) || $user->isGranted($user::admin)){
                $professor[]=$user;
            }else{
                $student[]=$user;
            }
        }
        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'professor'=>$professor,
            'students'=>$student,
            'user'=>$myUser,
            'title'=>$subject->getTitle(),
            
        ]);
    }



    /**
     * Undocumented function
     *
     * @Route("/subjects", name="subject_list")
     */
    public function subjectsList(SubjectRepository $subjectRepository){
        $subjects=$subjectRepository->findAll();
        $already=[];
        foreach ($subjects as $key=>&$subject) {
            foreach ($subject->getUser() as $value) {
                if($this->getUser()==$value){
                    $already[]=$subject;
                    unset($subjects[$key]);
                }
            }
        }
        return $this->render('subject/list.html.twig', [
            'subjects' => $subjects,
            'dejaInscrit'=>$already
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/subject/assign/{id}", name="assign_user")
     */
    public function assignUser(Subject $subject){
        $subject->addUser($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('subject_list');
    }
}
