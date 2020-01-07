<?php
namespace App\Service;

class NotesOrganiser
{
    public function OrganiseNotes($notes, $subjects)
    {
        $results = [];
        foreach ($subjects as $subject) {
            $hasNote = false;
            foreach ($notes as $note) {
                if ($note->getSubject() == $subject) {
                    $subjectName = $subject->getTitle();
                    $results[$subjectName][] = $note;
                    $hasNote = true;
                }
            }
            if ($hasNote == false) {
                $subjectName = $subject->getTitle();
                $results[$subjectName]["hasBeenNoted"] = false;
               
            } else {
                $subjectName = $subject->getTitle();
                $results[$subjectName]["hasBeenNoted"] = true;
            }
            $results[$subjectName]["subject_id"]=$subject->getId();
            
        }

        
        foreach ($results as &$sub) {
            if ($sub["hasBeenNoted"] == false) {
                
            } else {
              
                $divisor = 0;
                $add = 0;

                foreach ($sub as $key => $note) {
                    
                    if($key!=="hasBeenNoted" && $key!=="subject_id"){
                        $divisor += $note->getCoeff();
                        $add += $note->getNote() * $note->getCoeff();
                        dump($add);
                    }
                    
                
            }
                $sub["moyenne"] = $add / $divisor;
            }
        } 

            return $results;
        }


        public function getMoyenneGeneral($notes, $subjects){
            $results = [];
        foreach ($subjects as $subject) {
            $hasNote = false;
            foreach ($notes as $note) {
                if ($note->getSubject() == $subject) {
                    $subjectName = $subject->getTitle();
                    $results[$subjectName][] = $note;
                    $hasNote = true;
                }
            }
            if ($hasNote == false) {
                $subjectName = $subject->getTitle();
                $results[$subjectName]["hasBeenNoted"] = false;
            } else {
                $subjectName = $subject->getTitle();
                $results[$subjectName]["hasBeenNoted"] = true;
            }
        }

        
        foreach ($results as &$sub) {
            if ($sub["hasBeenNoted"] == false) {
                
                $sub["moyenne"] = "not noted";
            } else {
              
                $divisor = 0;
                $add = 0;

                foreach ($sub as $key => $note) {
                    
                    if($key!=="hasBeenNoted"){
                        $divisor += $note->getCoeff();
                        $add += $note->getNote() * $note->getCoeff();
                        dump($add);
                    }
                    
                
            }
                $sub["moyenne"] = $add / $divisor;
            }
        }
            $moyenneTotale = 0;
            $count = 0;
            foreach ($results as $subject) {
                if ($subject["hasBeenNoted"] != false) {
                    
                    $count++;
                    $moyenneTotale += $subject["moyenne"];
                }
            }
          if($count==0){
              return false;
          }
            return $moyenneTotale / $count;
        }
    
}
