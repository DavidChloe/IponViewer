<?php

// For V2 : work with POO to optimize the code
// IMPORTS
// Entities
@require "./model/base.php";


// Class to get 
class ResultSearchColumn {
    // Type string
    var $name;
    // Type array(string)
    var $allValue;

    // Initialization class
    function __construct(string $name)
    {
        $this->name = $name; 
        $this->allValue = array();
    }

    public function addValue($value)
    {
        array_push($this->allValue, $value);
        return true;
    }
}

// Get the list of all header of the array
$allColumn = [];
// Filter data from a request to create several column entity
function filteredResultSearch(array $data) {
    // Call global variable $allColumn
    global $allColumn;
    foreach ($data as $array) {
        // Get element in the first array
        $arrayData = [];
            foreach ($array as $key => $value) {
                if (property_exists("ResultSearchColumn", $key) == false) {
                    $newColumn = new ResultSearchColumn($key);
                    $newColumn->addValue($value);
                    array_push($arrayData, $newColumn);
                } else {
                    // TODO : retrouver l'objet dans allHeader et ajouter la valeur au tableau
                    echo "AJOUTER NOUVEL OBJET";
                }
                //array_push($allHeader, $key);
            }
        array_push($allColumn, $arrayData);
    }
    
    //foreach ($allColumn as $object) {
        //echo $object->allValue[0] . "<br/>";
    //}
    //$age = 40;

    //phpDevsOver40 = array_filter($data["PHPDevelopers"], function ($value) use ($age) {

    //return ($value["Age"] > $age);
}

?>