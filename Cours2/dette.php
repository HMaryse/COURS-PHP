<?php 
//Fonctions Access aux donnees
   function selectClients():array {
    return
        [
            [
            "nom"=>"Wane",
            "prenom"=>"Baila",
            "telephone"=>"777661010",
            "adresse"=>"FO",
            "dette"=>[],

            ],
            [
            "nom"=>"Wane1",
            "prenom"=>"Baila1",
            "telephone"=>"777661011",
            "adresse"=>"FO1",
            "dette"=>[],

            ]
        ];


   }

   function selectClientByTel(array $clients,string $tel):array|null {
        foreach ($clients as  $client) {
            if ($client["telephone"] == $tel) {
               return $client;
            }
        }
        return null;
   }

   function insertClient(array &$tabClients,$client):void {
          // array_push($tabClients,$client);
           $tabClients[]=$client;
      }




//Fonctions Services ou Use Case  ou Metier
  function  enregistrerClient(array &$tabClients,array $client):bool{
     $result=  selectClientByTel($tabClients,$client["telephone"]);
     if (  $result==null ) {
        insertClient($tabClients,$client);
        return true;
     }
     return false;
  }

  function listerClient():array{
      return selectClients();
  }


function estVide(string $value):bool{
    //$value=="" ou empty($value)
    return empty($value);
}




//Fonctions Presentation
function saisieChampObligatoire(string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value));
   return $value;
}
function telephoneIsUnique(array $clients,string $sms):string{
    do {
        $value= readline($sms);
    } while (estVide($value) || selectClientByTel($clients,$value)!=null);
    return $value;
   
}

function afficheClient(array $clients):void{
    if (count($clients)==0) {
        echo "Pas de client trouve";
    }else {
        foreach ($clients as  $client) {
            echo"\n-----------------------------------------\n";
            echo "Telephone : ". $client["telephone"]."\t";
            echo "Nom : ". $client["nom"]."\t";
            echo "Prenom : ". $client["prenom"]."\t";
            echo "Adresse : ". $client["adresse"]."\t";
            echo "Dettes : \n"; 
            
      }
    }
    
}



function saisieClient(array $clients):array{
    return [
        "telephone"=>telephoneIsUnique($clients,"Entrer le Telephone: "),
         "nom"=>saisieChampObligatoire(" Entrer le Nom: "),
         "prenom"=>saisieChampObligatoire(" Entrer le Prenom: "),
         "adresse"=>saisieChampObligatoire(" Entrer l'Adresse: "),
    ] ; 
}

//Enregister une dette
function SaisieDette(): array {
    return [ 
        "montant" => (int)saisieChampObligatoire("Entrer le montant de la dette : "), 
        "date" => saisieChampObligatoire("Entrer la date de la dette : "), "montant_verse" => (int)saisieChampObligatoire("Entrer le montant versé : ") ];
}
// Ajouter une dette 
function ajouterDette(array &$tabClients, string $tel, array $dette):bool{ 
    foreach ($tabClients as &$client) { 
        if ($client["telephone"] == $tel) { 
            $dette["ref"] = Ref();
            $client["dette"][]=$dette;
            return True;
        return FALSE;

        }
    }
}

//Avoir une reference unique
function Ref(): string { 
    $reference = rand(0, 99999); 
    return (string)$reference;
}

//Lister les dettes 
function afficheDetteClient(array $clients, string $tel):void{
        $client = selectClientByTel($clients, $tel);   
        if ($client["telephone"] == $tel && !empty($client["dette"])){
                echo"\n-----------------------------------------\n";
                echo "Nom : ". $client["nom"]."\n";
                echo "Prenom : ". $client["prenom"]."\n";
                echo "Adresse : ". $client["adresse"]."\n";
                echo "Telephone : ". $tel."\n";   
                foreach ($client['dette'] as $index => $dette) { 
                    echo "Dette " . ($index + 1) . ": \nRef : " . $dette["ref"] ." 
                    \nMontant : " . $dette["montant"] . "
                    \nDate : " . $dette["date"] . " 
                    \nMontant versé : " . $dette["montant_verse"] . "\n";
                    echo"\n-----------------------------------------\n"; 
                }
       
        }else {
            echo "Aucune dette a ce numero";
        }
}
function PayerDetteClient(array $clients, string $tel, string $nv_montant):void {
    $client = selectClientByTel($clients, $tel); 
    if ($client["telephone"] == $tel && !empty($client["dette"])){
    
        foreach($client["dette"] as &$dette){
            $reste=$dette["montant"] - $dette["montant_verse"];
            if ($nv_montant < $reste) { 
                $dette["montant_verse"] += $nv_montant; 
                echo " Nouveau montant verse : " . $dette["montant_verse"] . "\n";
                $reste=$dette["montant"] - $dette["montant_verse"];
                echo"vous devez encore rembourse :" . $reste ."\n";
                
            }
            elseif ($nv_montant == $reste) { 
                $dette["montant_verse"] += $nv_montant; echo "La dette est entierement remboursee.\n
                Montant total versé : " . $dette["montant_verse"] . "\n";
            }else {
                echo"Le montant a paye est superieur au montant du";
            }
            }
        


    }
    
}

function menu():int{
    echo "
     1.Ajouter client \n
     2.Lister les clients\n 
     3.Rechercher un client par telephone\n
     4.Enregistrer une dette\n
     5.Lister les dettes d'un client\n
     6.Payer une dette\n
     7.Quitter\n";
    return (int)readline(" Faites votre choix: ");
}


function principal(){
   $clients= selectClients();
   do {

      $choix= menu();
      switch ($choix) {
       case 1:
        $client=saisieClient($clients);
       if (enregistrerClient($clients,  $client)) {
           echo"Client Enregistrer avec success \n";
       }else {
            echo"Le numero Telephone  existe deja \n";
       } 
       break;
       case 2:
        afficheClient( $clients);
       break;
       case 3:
        $tel=readline("Entrer un numero de tel: ");
        $result= selectClientByTel($clients,$tel);
        if ( $result==null ) {
           echo"Le client n'existe pas";
        } else {
            echo "Client trouvé : \n"; 
            echo "Nom : " . $result["nom"] . "\n"; 
            echo "Prénom : " . $result["prenom"] . "\n"; 
            echo "Téléphone : " . $result["telephone"] . "\n"; 
            echo "Adresse : " . $result["adresse"] . "\n";
        }
       break;
       case 4:
            do {
                $rep=readline("Voulez vous ajouter une dette O/N?: ");
                if ($rep=="O" || $rep=="o") {
                $tel=readline("Entrer un numero de tel: ");
                $result= selectClientByTel($clients,$tel);
                if ( $result==null ) {
                    echo"Le client n'existe pas\n";
                } else {
                    echo"Vous allez ajouter une dette...\n ";
                    $dette=SaisieDette();
                    if (ajouterDette($clients, $tel,$dette)){
                        echo"Dette ajoutee avec succes !\n";
                    }  
                }
                } 
            }while ($rep=="O" || $rep=="o" );    
        break;
        case 5:
            $tel=readline("Entrer un numero de tel: ");
            afficheDetteClient($clients,$tel);
        break;
        case 6:
            $tel=readline("Entrer un numero de tel: ");
            $nv_montant=readline("Entrer le montant que vous souhaitez verser: ");
            PayerDetteClient($clients,$tel, $nv_montant);
        break;
         case 7:
             echo"Au revoir";
        break;    
       default:
          echo "Veullez faire un bon choix: ";
           break;
      }

   } while ($choix!=7);
}
principal();