<?php

namespace App\Fonctions;

use App\Entity\Participant;
use App\Entity\Campus;
use App\Repository\CampusRepository;


function lectureCsv(string $fichier){



    // open the file
    $f = fopen($fichier, 'r');

    if ($f === false) {
        die('Cannot open the file ' . $fichier);
    }

    if (($row = fgetcsv($f,1000,";")) !== false) {
        foreach($row as $element){
            $entete[] = $element;
        }
    }


    // read each line in CSV file at a time
    $k=0;
    while (($row = fgetcsv($f,1000,";")) !== false) {
        $j=0;
        foreach($row as $element){
            $data[$k][$j] = $element;
            $j++;
        }
        $k++;
    }

    // close the file
    fclose($f);

    return ([$entete,$data]);

}

function ecritureObjets($tableau, CampusRepository $campusRepository){
    $entete = $tableau[0];
    $donnees = $tableau[1];
    $nouveauxParticipants = [];
    foreach ($donnees as $ele){
        $nouvParti = new Participant();
        $i=0;
        foreach($ele as $elt){
            if ($entete[$i] == 'nom'){
                $nouvParti->setNom($elt);
            }
            if ($entete[$i] == 'prenom'){
                $nouvParti->setPrenom($elt);
            }
            if ($entete[$i] == 'mail'){
                $nouvParti->setMail($elt);
            }
            if ($entete[$i] == 'password'){
                $nouvParti->setPassword($elt);
            }
            if ($entete[$i] == 'telephone'){
                $nouvParti->setTelephone($elt);
            }
            if ($entete[$i] == 'administrateur'){
                if($elt == '1' or $elt == 1){
                    $nouvParti->setAdministrateur(true);
                    $nouvParti->setRoles(["{\"ROLE\":\"ROLE_ADMIN\"}"]);
                }
                else{
                    $nouvParti->setAdministrateur(false);
                }

            }
            if ($entete[$i] == 'campus') {
                $campus = $campusRepository->findOneBy(['nom'=> $elt]);
                $nouvParti->setCampus($campus);
            }
            $i++;
        }
        $nouvParti->setActif(true);
        $nouveauxParticipants[] = $nouvParti;
    }

    return $nouveauxParticipants;
}

?>