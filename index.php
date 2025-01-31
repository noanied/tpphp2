<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
</html>

<?php

// Classe CorpsCeleste : modèle parent de tous les corps célestes
class CorpsCeleste {
    public $nom;
    public $vitesse;
    public $masse;
    public $diametre;
    public $demiGrandAxe;

    // Constructeur de la classe CorpsCeleste
    public function __construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe) {
        $this->nom = $nom;
        $this->vitesse = $vitesse;
        $this->masse = $masse;
        $this->diametre = $diametre;
        $this->demiGrandAxe = $demiGrandAxe;
    }

    // Calcul de l'avancement en fonction de la durée (en années)
    public function avancement($duree) {
        return $this->vitesse * $duree; // Retourne la distance parcourue
    }
}

// Classe Planete qui hérite de CorpsCeleste
class Planete extends CorpsCeleste {
    public $type;

    public function __construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe, $type) {
        parent::__construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
        $this->type = $type;
    }
}

// Classe Asteroide qui hérite de CorpsCeleste
class Asteroide extends CorpsCeleste {
    public $type;

    public function __construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe) {
        parent::__construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
        $this->type = 'solide';  // Type par défaut pour un astéroïde
    }
}

// Classe Comete qui hérite de CorpsCeleste
class Comete extends CorpsCeleste {
    public $type;

    public function __construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe) {
        parent::__construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
        $this->type = 'solide';  // Type par défaut pour une comète
    }
}

// Classe PlaneteNaine qui hérite de CorpsCeleste
class PlaneteNaine extends CorpsCeleste {
    public $type;

    public function __construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe, $type) {
        parent::__construct($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
        $this->type = $type;
    }
}

// Classe Course : gère la course entre les corps célestes
class Course {
    public $participants = [];

    // Constructeur de la course
    public function __construct() {
        $this->genererParticipants();  // Génére les participants à la course
    }

    // Fonction pour générer les 10 participants
    public function genererParticipants() {
        $types = ['Planete', 'Asteroide', 'Comete', 'PlaneteNaine']; // Types de corps célestes
        for ($i = 0; $i < 10; $i++) {
            $type = $types[array_rand($types)];  // Choix aléatoire du type de corps céleste
            $nom = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);  // Génère un nom aléatoire de 8 lettres
            $vitesse = rand(10, 100);  // Vitesse entre 10 et 100 km/h
            $masse = rand(0, 1000) / 1000.0;  // Masse entre 0.0 et 1.0
            $diametre = rand(1, 100000);  // Diamètre entre 1 et 100 000 km
            $demiGrandAxe = rand(1, 1000);  // Demi-grand axe entre 1 et 1000 millions de km
            $typePlanete = ['liquide', 'solide', 'gazeux'][rand(0, 2)];  // Type de planète (aléatoire)

            // Création de l'objet correspondant au type choisi
            if ($type === 'Planete') {
                $this->participants[] = new Planete($nom, $vitesse, $masse, $diametre, $demiGrandAxe, $typePlanete);
            } elseif ($type === 'Asteroide') {
                $this->participants[] = new Asteroide($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
            } elseif ($type === 'Comete') {
                $this->participants[] = new Comete($nom, $vitesse, $masse, $diametre, $demiGrandAxe);
            } elseif ($type === 'PlaneteNaine') {
                $this->participants[] = new PlaneteNaine($nom, $vitesse, $masse, $diametre, $demiGrandAxe, $typePlanete);
            }
        }
    }

    // Fonction pour afficher la grille de départ des participants
    public function afficherGrilleDepart() {
        // Trie les participants par ordre croissant de demi-grand axe, puis par vitesse
        usort($this->participants, function($a, $b) {
            if ($a->demiGrandAxe == $b->demiGrandAxe) {
                return $a->vitesse < $b->vitesse ? 1 : -1;
            }
            return $a->demiGrandAxe < $b->demiGrandAxe ? -1 : 1;
        });

        echo "<h1>Grille de départ</h1>";
        foreach ($this->participants as $index => $participant) {
            echo "Le " . ($index + 1) . "ème participant " . $participant->nom . " est un/une " . get_class($participant) . " de type " . $participant->type . " <br>";
        }
    }

    // Fonction pour afficher les résultats de la course
    public function afficherResultats($duree) {
        // Trie les participants en fonction du nombre de tours effectués (plus de tours en premier)
        usort($this->participants, function($a, $b) use ($duree) {
            $avancementA = $a->avancement($duree);
            $avancementB = $b->avancement($duree);
            return $avancementA < $avancementB ? 1 : -1;
        });

        // Affiche les résultats des 3 premiers participants
        echo "<h1>Résultats de la course</h1>";
        echo "Le vainqueur de la course est un/une " . get_class($this->participants[0]) . " de type " . $this->participants[0]->type . ", le grand " . $this->participants[0]->nom . ", il a effectué " . round($this->participants[0]->avancement($duree), 2) . " tours d'orbite.<br>";
        echo "Le lauréat de la médaille d'argent est un/une " . get_class($this->participants[1]) . " de type " . $this->participants[1]->type . ", le non moins talentueux " . $this->participants[1]->nom . ", il a effectué " . round($this->participants[1]->avancement($duree), 2) . " tours d'orbite.<br>";
        echo "Le troisième candidat présent sur le podium est un/une " . get_class($this->participants[2]) . " de type " . $this->participants[2]->type . ", le vénérable " . $this->participants[2]->nom . ", il a effectué " . round($this->participants[2]->avancement($duree), 2) . " tours d'orbite.<br>";
    }
}

// Création de la course
$course = new Course();

// Affichage de la grille de départ
$course->afficherGrilleDepart();

// Durée de la course (en années), choisie aléatoirement
$duree = rand(1, 100);

// Affichage des résultats de la course
$course->afficherResultats($duree);

?>