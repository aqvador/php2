<?php

/**
 *  Каталог товаров. возвращает товары из каталога
 *
 *  @param start С какой позиции брать товар из списка
 *  @param stop Конечная позиция товара
 * 
 *  @throws return Возаращает  массив с товарами
 *  @author aqvador
 */
class Catalog{
    // метод получения товара из базы данных для страницы
       public static function getCatalog($categoty = 1, $start = 0, $stop = 16){
            $sql = 'SELECT * FROM `personal`.`catalog` WHERE `show` = \'Y\' AND `category` = ? LIMIT ?,?';
            $param = [$categoty, $start, $stop]; //Параметры вставки
        return db::getInstance()->Select($sql, $param);
    }
    public static function random($start = 0, $stop = 6){
        $sql = 'SELECT `img` FROM `personal`.`catalog` WHERE `show` = \'Y\' LIMIT ?,?';
        $param = [$start, $stop]; //Параметры вставки
        return db::getInstance()->Select($sql, $param);
    }

    public static function Detailed($param) {
        $sql = 'SELECT * FROM `personal`.`catalog` WHERE `show` = \'Y\' AND id = ?';
        $param = [$param[0]]; //Параметры вставки
        // logger::write()
        return db::getInstance()->Select($sql, $param)[0];

    }

    public static function Category($param) {
        $sql = 'SELECT * FROM `personal`.`categories` WHERE `id_category` = ?';
        $row =  db::getInstance()->Select($sql, [$param[0]]);
        if(count($row) !== 1) return false;
        $a['h1'] = $row[0]['name'];
        $a['content'] =  self::getCatalog($row[0]['id_category']);
        logger::write($row);
        return $a;

    }

}