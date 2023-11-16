<?php
// Crétation de la classe Utils qui contient la méthode static generateRandomNbr
abstract class Utils
{
    // Méthode static pour générer un nombre aléatoire en ayant comme paramètres un min et max
    public static function generateRandomNbr($min, $max)
    {
        // Retourne un nombre aléatoire entre le min et le max
        return rand($min, $max);
    }
}

// Création de la classe Character
// Avec les propriétés name, marbles, loss et gain propre a chacun des personnages
// que se soit le héros ou les ennemis
class Character
{
    // Creation des propriétés
    private $name;
    private $marbles;
    private $loss;
    private $gain;

    // Constructeur pour initialiser les propriétés des personnages
    public function __construct($name, $marbles, $loss, $gain)
    {
        $this->name = $name;
        $this->marbles = $marbles;
        $this->loss = $loss;
        $this->gain = $gain;
    }

    // Méthodes getters pour obtenir les attributs du personnage
    public function getName()
    {
        return $this->name;
    }

    public function getMarbles()
    {
        return $this->marbles;
    }

    public function getLoss()
    {
        return $this->loss;
    }

    public function getGain()
    {
        return $this->gain;
    }

    // Méthode pour modifier le nombre de billes
    public function setMarbles($marbles)
    {
        $this->marbles = $marbles;
    }
}

// Création de la classe Hero qui hérite de la classe Character
// avec une propriété screamWar qui détermine le cri de guerre du héros
class Hero extends Character
{
    // Création de la propriété screamWar
    private $screamWar;

    // Constructeur pour initialiser les propriétés du héros
    public function __construct($name, $marbles, $loss, $gain, $screamWar)
    {
        // Appel du constructeur de la classe Character
        // pour initialiser les propriétés du héros qui sont aussi les même que la classe Character
        // en ajoutant la propriété screamWar propre au héros
        parent::__construct($name, $marbles, $loss, $gain);
        $this->screamWar = $screamWar;
    }

    // Méthode getter pour obtenir le cri de guerre du héros
    public function getScreamWar()
    {
        return $this->screamWar;
    }

    // Méthode pour vérifier si le héros a gagné ou perdu
    public function checkEnemy($guess, $enemyMarbles)
    {
        // Affecte la valeur de la propriété marbles à la variable enemyMarblesNumber
        $enemyMarblesNumber = $enemyMarbles;

        // Vérifie si le nombre de billes de l'ennemi est pair ou impair
        $isEven = $enemyMarblesNumber % 2 === 0;

        // Si le héros a choisi pair et que le nombre de billes de l'ennemi est pair
        if (($guess === "pair" && $isEven) || ($guess === "impair" && !$isEven)) {
            $this->setMarbles($this->getMarbles() + $enemyMarblesNumber + $this->getGain());
            echo "Victoire ! Vous avez gagné $enemyMarblesNumber billes.</br>";
            // Si le héros a choisi pair et que le nombre de billes de l'ennemi est impair
        } else {
            $this->setMarbles($this->getMarbles() - $enemyMarblesNumber + $this->getLoss());
            echo "Défaite ! Vous avez perdu $enemyMarblesNumber billes.</br>";
        }

        // Si le héros a moins de 0 billes
        if ($this->getMarbles() <= 0) {
            echo "Vous avez moins de 0 billes. Vous avez perdu la partie.</br>";
            exit;
        }
    }
}

// Création de la classe Enemy qui hérite de la classe Character
class Enemy extends Character
{
    // Création de la propriété age et koreanName
    private $age;
    private $koreanName;

    public function __construct($marbles)
    {
        // Appel du constructeur de la classe Character pour initialiser les propriétés de l'ennemi
        // avec des valeurs aléatoires
        parent::__construct("Enemy", $marbles, 0, 0);
        $this->age = Utils::generateRandomNbr(1, 100);
        $this->koreanName = $this->generateKoreanName();
    }

    // Méthodes getters pour obtenir les attributs de l'ennemi
    public function getAge()
    {
        return $this->age;
    }

    public function getKoreanName()
    {
        return $this->koreanName;
    }

    // Méthode pour générer un nom aléatoire pour chaque ennemi
    private function generateKoreanName()
    {
        $koreanNames = ["Ji-hoon", "Min-ji", "Sung-ho", "Eun-ji", "Woo-jin", "Ji-yeon", "Sang-min", "Hye-jin", "Joon-ho", "Yeon-woo", "Haeun", "Jin-ho"];
        // Retourne un nom aléatoire en utilisant la fonction array_rand qui retourne un index aléatoire
        return $koreanNames[array_rand($koreanNames)];
    }
}

// Classe représentant le jeu
class Game
{
    // Propriétés du jeu
    private $hero;
    private $enemies;
    private $usedEnemyIndices = [];

    // Constructeur pour initialiser le jeu avec un héros et des ennemis
    public function __construct($hero, $enemies)
    {
        $this->hero = $hero;
        $this->enemies = $enemies;
    }

    // Méthode pour rencontrer un ennemi et déclencher un combat
    public function encounterEnemy($enemy)
    {
        echo "Rencontre avec {$enemy->getKoreanName()} qui a {$enemy->getAge()} ans et qui a {$enemy->getMarbles()} billes dans sa main.</br>";
        // Génère un nombre aléatoire entre 0 et 1
        if (Utils::generateRandomNbr(0, 1)) {
            // Si le nombre aléatoire est 1, le héros choisi pair
            $guess = 'pair';
        } else {
            // Sinon le héros choisi impair
            $guess = 'impair';
        }

        // Appel de la méthode checkEnemy de la classe Hero
        // qui vérifie si le héros a gagné ou perdu
        // et donc fait le calcul du nombre de billes perdu ou gagné pour le héros
        $this->hero->checkEnemy($guess, $enemy->getMarbles());

        // Affiche le nombre de billes restantes du héros
        echo "Il vous reste {$this->hero->getMarbles()} billes.</br>";
    }

    // Méthode pour obtenir un ennemi aléatoire qui n'a pas encore été rencontré
    public function getNextEnemy()
    {
        // Récupère l'index du prochain ennemi à rencontrer
        $nextEnemyIndex = count($this->usedEnemyIndices);

        // Récupère l'ennemi correspondant à l'index actuel
        $nextEnemy = $this->enemies[$nextEnemyIndex];

        // Ajoute l'index de l'ennemi à la liste des ennemis rencontrés
        $this->usedEnemyIndices[] = $nextEnemyIndex;

        // Retourne le prochain ennemi dans l'ordre
        return $nextEnemy;
    }

    // Méthode pour terminer le jeu
    public function endGame()
    {
        echo "La partie est terminée. ";

        // Si le héros a plus de 1 billes après avoir battu tous les ennemis
        if ($this->hero->getMarbles() >= 1) {
            // Affiche le cri de guerre du héros et le nombre de billes qu'il a gagné
            echo $this->hero->getScreamWar() . " ";
            echo "Félicitations ! Vous avez survécu et gagné 45,6 milliards de Won sud-coréen.";
        }
    }

    // Méthode pour déterminer le niveau de difficulté et lancer les combats
    public function startGame($difficulty)
    {
        echo "Vous avez choisi le niveau de difficulté : ";
        // Détermine le nombre de tours en fonction de la difficulté
        if ($difficulty == 1) {
            echo "Facile </br>";
            $numRounds = 5;
        } elseif ($difficulty == 2) {
            echo "Difficile </br>";
            $numRounds = 10;
        } elseif ($difficulty == 3) {
            echo "Impossible </br>";
            $numRounds = 20;
        } else {
            echo "Inconnu";
            $numRounds = 0;
        }

        // Lance les combats en fonction du nombre de tours étblis par la difficulté
        for ($i = 0; $i < $numRounds; $i++) {
            $this->encounterEnemy($this->getNextEnemy());
        }

        // Termine le jeu quznd il a battu tous les ennemis
        $this->endGame();
    }
}

// Création des personnages avec leurs noms, nombre de billes, bonus, malus et cri de guerre
$characters = [
    new Hero("Seong Gi-hun", 15, 2, 1, "Yeah !"),
    new Hero("Kang Sae-byeok", 25, 1, 2, "Youpi!"),
    new Hero("Cho Sang-woo", 35, 0, 3, "Come on!"),
];

// Création des ennemis
$enemies = [];

// Création des 20 ennemis avec un nombre de billes aléatoire
for ($i = 1; $i <= 20; $i++) {
    //
    $enemy = new Enemy(Utils::generateRandomNbr(1, 20));
    $enemies[] = $enemy;
}

$selectedHero = $characters[array_rand($characters)];

// Affiche le personnage choisi
echo "Personnage choisi : {$selectedHero->getName()}</br>";

// Choix de la difficulté
// si on met generateRandomNbr(1, 1) on aura toujours le niveau facile
// si on met generateRandomNbr(2, 2) on aura toujours le niveau difficile
// si on met generateRandomNbr(3, 3) on aura toujours le niveau impossible
$selectedDifficulty = Utils::generateRandomNbr(1, 3);
// Appel du constructeur de la classe Game avec comme paramètres le personnage choisi et les ennemis
$game = new Game($selectedHero, $enemies);
// Appel de la méthode startGame dans la classe Game avec comme paramètre la difficulté
$game->startGame($selectedDifficulty);
?>