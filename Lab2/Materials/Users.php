<?php
class Users {
    private $db_ref;

    public function __construct(Database $db) {
        $this->db_ref = $db;
    }

    /**
     * Добавляет в таблицу нового пользователя
     * @param string $username - имя пользователя
     * @param string $login - логин
     * @param string $hash - хеш пароля
     * @param int $user_level - уровень привилегий пользователя
     * @throws RuntimeException - происходит, если возникла ошибка при выполнении запроса или при извлечении данных,
     * а также при попытке добавить пользователя с уже существующим логином
     * @return void
     */
    public function add_user($username, $login, $hash, $user_level) {
        if ($this->find($login))
            throw new RuntimeException("Ошибка: пользователь с таким логином уже существует!");

        $query_result = $this->db_ref->execute_query("
            INSERT INTO users(id, username, login, hash, user_level)
            SELECT MAX(id) + 1, '$username', '$login', '$hash', $user_level FROM users;
        ");

        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());
    }

    /**
     * Ищет пользователя с заданным логином
     * @param string $login -
     * @return array|false|null
     * @throws RuntimeException - если возникла ошибка при выполнении запроса
     */
    public function find($login) {
        $query_result = $this->db_ref->execute_query("SELECT * FROM users WHERE login = '$login';");
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

        return $query_result->fetch_assoc();
    }
}
