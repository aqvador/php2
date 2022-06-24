<?php

/**
 *  Приложение подключенич к БД
 *
 * @param getInstance возвращает создает единственный экземпляр объекта данного класса
 *
 * @return возвращает создает единственный экземпляр объекта данного класса
 *
 * @author Not Found
 */
class db
{
    private static $_instance = null;

    private $db; // Ресурс работы с БД

    /*
     * Получаем объект для работы с БД
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }

    /*
     * Запрещаем копировать объект
     */
    private function __construct()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

    /*
     * Выполняем соединение с базой данных
     */
    public function Connect($user, $password, $base, $host = 'localhost', $port = 3306)
    {
        // Формируем строку соединения с сервером
        $connectString = 'mysql:host=' . $host . ';port= ' . $port . ';dbname=' . $base . ';charset=UTF8;';
        $this->db = new PDO($connectString, $user, $password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // возвращать ассоциативные массивы
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // возвращать Exception в случае ошибки
                PDO::ATTR_EMULATE_PREPARES, false // отключить режим эмуляции
            ]
        );
    }

    /*
     * Выполнить запрос к БД
     */
    public function Query($query, $params = array())
    {
        $res = $this->db->prepare($query);
        $res->execute($params);
        return $res;
    }

    /*
     * Выполнить запрос с выборкой данных
     */
    public function Select($query, $params = array())
    {
        $result = $this->Query($query, $params);
        if ($result) {
            return $result->fetchAll();

        }
    }

    public function exec($data)
    {
        $this->db->exec($data);
    }

    public function Insert($query, $params = array())
    {
        $res = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $res->execute($value);
            $return[] = $this->db->lastInsertId();
        }
        return $return;
    }
}

?>
