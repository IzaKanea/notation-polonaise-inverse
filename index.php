<?php
    /**
     * Reverse Polish notation : Notation polonaise inversé
     *
     * Ce code a été écrit pour des étudiants qui veulent aller un peu plus loin dans la rédaction de leur code.
     * Un étudiant qui termine sa première année de programmation pourrait obtenir un résultat similaire.
     * 
     * Interprétation personnelles de la notation polonaise inversé.
     *  + Gestion des signes : +, -, *, / et ^
     *  + Gestion des nombres décimaux : 3.5  -> "."
     *  + Posibilité d'utilisé ou non des parenthéses
     *  - Ne gère pas les erreurs de saisie
     *  - Ne gère pas les opérateurs unaires ( - 3 )
     *
     * Concepts abordés :
     *  - Programmation orienté objet(POO)
     *  - Héritage
     *  - Class abstraite
     *
     * Entrées acceptées : ATTENTION DE BIEN METTRE UN ESPACE ENTRE CHAQUE CARACTERE
     *  - $input = "( 3.1 ) + ( 4 ) ^ ( 3 )";
     *  - $input = "( 56 * ( ( ( 6 + 2 ) / ( 8 - 7 ) ) * ( 2 ^ 3 ) ) )";
     *  - $input = "2 - 4 + 6 + 2 ^ 4 ^ 1 * 3 / 3 * 2 / 2 ^ 2 ^ 1 * 3 + 4 / 2 * 3 * 4 ^ 2 / 16";
     *
     * Entrées non traitées :
     *  - $input = "( 3.1) + ( 4 ) ^ ( 3 )"; Il manque un espace "3.1)"
     *  - $input = "2 - 4 + 6 + 2)";         la ")" est en trop
     * 
     * @category  Travaux pratiques
     * @author    Kanea Iza http://kanea-iza.be
     * @license   Open source
     * @link      https://github.com/izaKanea
     * 
     */

    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ LES CONSTANTES ++++++++++++++++++++++++++++++++++++++++++++++++++ */

    define("TABULATION", "&emsp;");
    define("PLUS", "+");
    define("MINUS", "-");
    define("TIMES", "*");
    define("DIVIDED", "/");
    define("EXPONENT", "^");
    define("LEFT_BRACKET", "(");
    define("RIGHT_BRACKET", ")");

    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ LES ITEMS ++++++++++++++++++++++++++++++++++++++++++++++++++ */
    /*
     * MyItem peut être : "+", "-", "*", "/", "^", "(", ")" ou un nombre
     */
    abstract class MyItem {
        protected $value;
        function __construct($value) {
            $this->value = $value;
        }
        function isOperator(){
            return false;
        }
        function isNumber(){
            return false;
        }
        function isRightBracket(){
            return false;
        }
        function isLeftBracket(){
            return false;
        }
        function getValue(){
            return $this->value;
        }
    }
    
    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ LES OPERAEURS */
    /*
     * Les Opérateurs sont : "+", "-", "*", "/", "^"
     * Ils possèdent une priorité : [ + - (1)] [ * / (10)] [ ^ (20)]
     */
    abstract class Operator extends MyItem{
        protected $prior = 0;
        function isOperator(){
            return true;
        }
        function getPrior(){
            return $this->prior;
        }
        function isPrior(Operator $operator){
            if($this->prior > $operator->getPrior()){
                return true;
            }
            return false;
        }
        function isPriorEgal(Operator $operator){
            if($this->getPrior() == $operator->getPrior())
                return true;
            return false;
        }
        abstract protected function calcul($val1,$val2);
    }


    class Plus extends Operator{
        protected $prior = 1;
        function calcul($val1,$val2){
            return new Number ($val2->getValue() + $val1->getValue());
        }
    }

    class Moins extends Operator{
     protected $prior = 1;
     function calcul($val1,$val2){
            return new Number ($val2->getValue() - $val1->getValue());
     }
    }

    class Division extends Operator{
       protected $prior = 10;
      function calcul($val1,$val2){
          return new Number ($val2->getValue() / $val1->getValue());
      }
    }

    class Times extends Operator{
        protected $prior = 10;
        function calcul($val1,$val2){
         return new Number ($val2->getValue() * $val1->getValue());
        }
    }

    class Exponent extends Operator{
        protected $prior = 20;
        function calcul($val1,$val2){
            return new Number ( pow($val2->getValue() , $val1->getValue()));
     }
    }

    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ NOMBRE, PARENTHESE GAUCHE ET DROITE */
    class Number extends MyItem{
        function isNumber(){
            return true;
        }
    }
    class RightBracket extends MyItem{
        function isRightBracket(){
            return true;
        }
    }
    class LeftBracket extends MyItem{
        function isLeftBracket(){
            return true;
        }
    }


    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ LA CLASS Calculette ++++++++++++++++++++++++++++++++++++++++++++++++++ */
    class Calculette {
        private $input;
        private $tabInput = array();
        private $pile;
        private $sortie;
    
    
        /*
         * Parse les données en entrée dans un array
         */
        function setInput($input){
            $this->input = trim( $input );
            $temp = explode(" ",trim( $input ));
            foreach ($temp as $item){
                if(is_numeric($item)){
                    array_push($this->tabInput,new Number($item));
                } else {
                    switch($item){
                        case PLUS : array_push($this->tabInput,new Plus($item));
                            break;
                        case MINUS : array_push($this->tabInput,new Moins($item));
                            break;
                        case TIMES : array_push($this->tabInput,new Times($item));
                            break;
                        case DIVIDED : array_push($this->tabInput,new Division($item));
                            break;
                        case EXPONENT : array_push($this->tabInput,new Exponent($item));
                            break;
                        case LEFT_BRACKET : array_push($this->tabInput,new LeftBracket($item));
                            break;
                        case RIGHT_BRACKET : array_push($this->tabInput,new RightBracket($item));
                            break;
                    }
                }
            }
        }
    
        function afficherPile(){
            echo TABULATION."Pile : ";
            echo $this->pile->afficher();
            echo "<br />";
        }
        function afficherSortie(){
            echo TABULATION."Sortie : ";
            echo $this->sortie->afficher();
            echo "<br />";
        }
    
    
        function afficherInput(){
            $t = $this->tabInput;
            $retour = "";
            foreach($t as $item){
                $retour .= $item->getValue()." ";
            }
            $retour .= "<br />";
            return $retour;
        }

        /*
         * Vérifie si vous devez calculer ou non. Cette fonction a été ajouté pour prendre en compte la règle d'ordre de priorité de calcul des exposants
         */
        private function checkCalculationRules($lastElement,$input){
            if($lastElement->isLeftBracket() ){
                return false;
            }
            if($input->isPrior($lastElement)){
                return false;
            }
            return true;
        }
        function resolution(){
            $this->pile = new MyPile;
            $this->sortie = new MyPile;
            $tabInput = $this->tabInput;
            // Boucle un fois sur toutes les valeurs en entrée
            for($i=0;$i<count($tabInput);$i++){
                $tempLastElementPile = $this->pile->getLastElement();
                /* SI le dernier élément de la pile et l'élément en entrée sont des opérateurs
                 *   ET
                 *    la priorité de l'opérateur de la pile est >= à la priorité l'opérateur en entrée et que la valeur en entrée n'est pas un exposant
                 */
                   
                if( ($tempLastElementPile != null && $tempLastElementPile->isOperator() && $tabInput[$i]->isOperator())
                     &&
                          ( $tempLastElementPile->isPrior($tabInput[$i]) || ($tempLastElementPile->isPriorEgal($tabInput[$i])
                            && $tabInput[$i]->getValue() != EXPONENT ))){
                    // Si l'opérateur de la pile est un exposant
                    if($tempLastElementPile->getValue() == EXPONENT){
                    //pour calculer ( 2 ^ 2 ^ 3 ^ 1 ) car l'odre de priorité est de droite à gauche ( 2 ^ ( 2 ^ ( 3 ^ 1 ) ) )
                    //il n'est possible de commencé à calculer qu'au moment où l'on lit le 1
                        while ( $this->checkCalculationRules($this->pile->getLastElement() ,$tabInput[$i])){    
                                $tempOperator = $this->pile->pop();
                                $this->sortie->push($tempOperator->calcul($this->sortie->pop(),$this->sortie->pop()));
                        }
                    } else {
                            $tempOperator = $this->pile->pop();
                            $this->sortie->push($tempOperator->calcul($this->sortie->pop(),$this->sortie->pop()));
                    }
		}
    
                // Si l'entrée est un nombre
                if($tabInput[$i]->isNumber()){
		    $this->sortie->push($tabInput[$i]);
		} else {
                    // Si l'entrée est une parenthèse de droite ")"                
                    if ($tabInput[$i]->isRightBracket()){
		        // On effectue les calculs entre parenthèses ( 2 / 5 + 7 ), ( 9 ), ( 4^3 )    
		        $tempLastElementPile = $this->pile->getLastElement();
                        while (!$this->pile->isEmpty() &&  !$tempLastElementPile->isLeftBracket()){
		           $tempOperator = $this->pile->pop();
                            $this->sortie->push($tempOperator->calcul($this->sortie->pop(),$this->sortie->pop()));
                            $tempLastElementPile = $this->pile->getLastElement();
                        }
                        // retirer la parenthèse de gauche de la pile "("
		        $this->pile->pop();
		    } else {
		    // DANS TOUT LES AUTRES CAS si l'entrée est "(" ou un opérateur(+ - * / ^)
		        $this->pile->push($tabInput[$i]);
		    }
		}
            }
	
            // Vider la pile s'il reste du travail à effectuer
            while(!$this->pile->isEmpty()){
                $tempLastElementPile = $this->pile->getLastElement();
                if($tempLastElementPile->isRightBracket()){
                    $this->pile->pop();
                } else {
                    $tempOperator = $this->pile->pop();
                    $this->sortie->push($tempOperator->calcul($this->sortie->pop(),$this->sortie->pop()));

                }
            }
            $this->afficherSortie();
        }
    }

    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ LA CLASS MyPILE ++++++++++++++++++++++++++++++++++++++++++++++++++ */
    class MyPile{
        private $pile;
        function __construct() {
            $this->pile = array();
        }
        function push($valeur){
            array_push($this->pile,$valeur );
        }
        function pop(){
            return array_pop( $this->pile);
        }
        function getLastElement(){
            return end($this->pile);
        }
        function isEmpty(){
            return empty($this->pile);
        }
        function afficher(){
            foreach($this->pile as $item){
                echo $item->getValue()." ";
            }
        }
    }


    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ DES INPUTS ++++++++++++++++++++++++++++++++++++++++++++++++++ */
    /*
     * Exemple d'input avec le détail du calcul.
     * -> [4 + 5]
     * ->    9
     */

    $input = "( 56 * ( ( ( 6 + 2 ) / ( 8 - 7 ) ) * ( 2 ^ 3 ) ) )";
    /*  ( 56 * ( ( ( 6 + 2 ) / ( 8 - 7 ) ) * ( 2 ^ 3 ) ) )
     *  ( 56 * ( (     8     /     1     ) *     8     ) )
     *  ( 56 * (             8             *     8     ) )
     *  ( 56 *                             64            )
     *  3584
     */


    $input = "( 2 ^ 4 ^ 1 * 2 / 2 ^ 2 * 3 + 4 / 2 * 3 )";
    /* Entre [] l'expression qui sera calculé dans la ligne suivante
     *
     *  ( 2 ^ [4 ^ 1] * 2 / [2 ^ 2] * 3 + 4 / 2 * 3 )
     *  ( [2 ^ 4] * 2 / 4 * 3           + 4 / 2 * 3 )
     *  ( [16 * 2] / 4 * 3 )            + ( [4 / 2] * 3 )
     *  [32 / 4] * 3                    +    [ 2 * 3]   
     *  [8 * 3]                         +       6
     *  [24                             +       6]
     *  30
     */

    //$input = "( 1 + 2 ^ 4 ^ 1 * 3 / 3 * 2 / 2 ^ 2 ^ 1 * 3 + 4 / 2 * 3 * 4 ^ 2 / 16 )";


    $input = "2 - 4 + 6 + 2 ^ 4 ^ 1 * 3 / 3 * 2 / 2 ^ 2 ^ 1 * 3 + 4 / 2 * 3 * 4 ^ 2 / 16";

    /* ++++++++++++++++++++++++++++++++++++++++++++++++++ UN RUN ++++++++++++++++++++++++++++++++++++++++++++++++++ */
    
    $cal = new Calculette;
    $cal->setInput(trim($input));
    $cal->resolution();
