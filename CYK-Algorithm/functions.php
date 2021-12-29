<?php 

function read_file($path){
    $file = fopen($path,"r");
    $container = [];
    while ( $line = fgets($file) ){
        $container[] =  trim(strtolower($line));
    }
    fclose($file);
    return array_unique($container);
}

function get_rules($path){
    $clean_rules = [];
    $file = fopen($path,"r");
    $leksikon = ['BdLeksikon', 'SfLeksikon', 'BilLeksikon', 'GtLeksikon', 'KjLeksikon', 'PnLeksikon', 'PsLeksikon'];
    // explode tanda panah
    while( $rule = fgets($file) ){
        $new_rule = explode("->", $rule);
        $nonTerminal = trim($new_rule[0]); // bersihin spasi
        $rhs = trim($new_rule[1]); // bersihin spasi

        // jika rhs adalah leksikon
        if( in_array($rhs, $leksikon) ){
            $rhs = read_file("./assets/" . $rhs . ".txt");
            $clean_rules[$nonTerminal] =  $rhs;
        }else{
            $clean_rules[$nonTerminal][] =  $rhs;
        }
    }
    return $clean_rules;
}

function part_of_speech($rules, $value){

    $arr = [];
    foreach($rules as $nonTerminal => $rhs){
        if( in_array($value, $rhs) ){
            $arr[] = $nonTerminal;
        }
    }
    return $arr;
}

function combine($left, $right){
    // ubah ke array
    $left = explode(" ", $left);
    $right = explode(" ", $right);

    // kombinasi nested loop
    $new = [];
    foreach( $left as $l ){
        foreach( $right as $r ){
            $new[] = $l . " " . $r;
        }
    }

    return $new;
}