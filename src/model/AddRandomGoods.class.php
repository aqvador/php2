<?php

class AddRandomGoods {
    
    function index(){
        return;
        $name = 100;
        $photos = Catalog::random(0,17);
        $sql = "INSERT INTO `catalog` (`name`, `price`, `img`, `discr`, `full_discr`) VALUES ";

        for($i=0; $i<1000; $i++){
        $name +=1;
        $m = 'Кроссовки № '. $name;
        $price = rand(1200,3450);
        $disct = Lorem::ipsum(rand(3,7));
        $full_disct = Lorem::ipsum(rand(10,25));
        $photo = $photos[array_rand($photos)]['img'];
        $sql .= " ('$m', $price, '$photo', '$disct', '$full_disct'),"; 
        }
        db::getInstance()->Select(substr($sql, 0,-1).';');
    }
}