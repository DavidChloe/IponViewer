<?php


/*

    Classe de gestion des connexion au base de données. Cette classe est utilisé pour créer les objets PDO qui servent à lancer les requête dans le script php.

*/


class DatabaseConnectionPool {

    // Argument privée, propre à la classe, de type array. 
    private static $connections = [];


    // Configuration des bases de données. Utilisation des paramètres établie dans les variables globales.
    // Argument privée, propre à la classe, de type array. Ce tableau contient 4 tableaux (1 pour chaque base de données, identifié par une clé).
    // La clé de chaque sous-tableau est très importante car elle est utilisé pour créer un obkjet de la classe qui permettra d'ouvrir une connexion vers la base de données choisie dont le nom doit corresponde à la clé de l'un des sous-tableau.
    private static $dbConfigs = [
        // Paramètres d'Ipon 
        'Ipon' => [
            'dsn' => 'oci:dbname=(DESCRIPTION=(CONNECT_TIMEOUT=3)(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST='.HOST_IPON.')(PORT='.PORT_IPON.')))(CONNECT_DATA=(SID='.SID_IPON.')))',
            'username' => USER_IPON,
            'password' => PWD_IPON,
            'options' => []
        ],
        // Paramètres d'Ipon Rip
        'IponRip' => [
            'dsn' => 'oci:dbname=(DESCRIPTION=(CONNECT_TIMEOUT=3)(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST='.HOST_IPON_RIP.')(PORT='.PORT_IPON_RIP.')))(CONNECT_DATA=(SID='.SID_IPON_RIP.')))',
            'username' => USER_IPON_RIP,
            'password' => PWD_IPON_RIP,
            'options' => []
        ],
        // Paramètres de Georeso sur model GER
        'Georeso' => [
            'dsn' => 'pgsql:host='.HOST_GEORESO.';dbname='.DB_NAME_GEORESO.'',
            'username' => USER_GEORESO,
            'password' => PWD_GEORESO,
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        ],

        // Paramètres de Georeso sur model GER
        'GeoresoRip' => [
            'dsn' => 'pgsql:host='.HOST_GEORESO.';dbname='.DB_NAME_GEORESO.'',
            'username' => USER_GEORESO,
            'password' => PWD_GEORESO,
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        ]
    
    ];

    // Fonction permettant de créer une connexion vers une base de données
    // Prend en paramètre l'un des clé du tableau $dbConfigs pour cibler à quelle base de données se connecter
    public static function createConnection($databaseName) {

        // Lancement d'un premier timer
        $time_start = microtime(true);
        // Récupération de l'heure
        $heure = date("H:i:s");
        // Initialisation de la variable qui recueillera l'objet PDO à null
        $pdo = null;

        // Tentative d'ouverture d'une connexion bdd
        try {
            // Création de l'objet PDO
            $pdo = new PDO(self::$dbConfigs[$databaseName]['dsn'], self::$dbConfigs[$databaseName]['username'], self::$dbConfigs[$databaseName]['password']);
            // Stockage de l'objet PDO
            self::$connections[$databaseName] = $pdo;
            // Passage du flag à true
            $flag = true;
                    
        }// En cas d'échec 
        catch (PDOException $e) {
            // Affichage du message d'erreur
            echo '<div class="o-alert">'.'Échec de la connexion à la base de données '.$databaseName.'. Vérifier votre connexion Cactus - '. $e->getMessage().'</div>';
            // Passage du flag à false
            $flag = false;
        }

        // Lancement d'un deuxieme timer
        $time_end = microtime(true);
        // Calcule de la différence
        $time = round(($time_end - $time_start) * 1000, 2);
        
        // Renvoyer la valeur du flag (true pour une connexion établie, false pour une connexion échouée), l'heure du test, la durée d'éxécution de la fonction et l'objet PDO
        return [$flag, $heure, $time, $pdo];
    }

    // Fonction pour récupérer l'objet PDO stocké
    // Prend en paramètre la clé correspond au nom de la base de donnée ciblée
    public static function getPDO($databaseName){
        // Si l'objet existe dans le tableau
        if (isset(self::$connections[$databaseName])) {
            // On le renvoie pour pouvoir l'utiliser
            return self::$connections[$databaseName];
        } else {   
            // Sinon, renvoyer false      
            return false;
        }

    }

    // Fonction pour fermer proprement la connexion
    // Prend en paramètre la clé correspond au nom de la base de donnée ciblée
    public static function closeConnection($databaseName) {
        // Si l'objet existe dans le tableau
        if (isset(self::$connections[$databaseName])) {
            // On le passe à null
            self::$connections[$databaseName] = null;
        }
    }

}



?>