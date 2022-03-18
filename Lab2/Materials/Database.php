<?php
class Database {
    private $connection;

    /**
     * Создаёт соединения с БД
     * @param string $host - хост (доменное имя или IP-адрес)
     * @param string $user - имя пользователя БД
     * @param string $password - пароль пользователя БД
     * @param string $db_name - имя БД
     */
    public function __construct($host, $user, $password, $db_name) {
        $this->connection = mysqli_connect($host, $user, $password, $db_name);
    }

    /**
     * Разрывает соединение с БД
     */
    public function __destruct() {
        mysqli_close($this->connection);
    }

    /**
     * Выполняет запрос к базе данных
     * @param string $query - строка запроса
     * @return bool|mysqli_result - результат
     * @throws RuntimeException - происходит если соединение с БД отсутствует
     */
    public function execute_query($query) {
        if ($this->connection)
            return mysqli_query($this->connection, $query);
        else
            throw new RuntimeException("Ошибка на сервере: не удалось соединиться с базой данных");
    }

    /**
     * Начинает транзакционный блок
     * @return bool|mysqli_result - удалось ли начать транзакционный блок
     * @throws RuntimeException - происходит если соединение с БД отсутствует
     */
    public function begin() {
        if ($this->connection)
            return mysqli_query($this->connection, "BEGIN;");
        else
            throw new RuntimeException("Ошибка на сервере: не удалось соединиться с базой данных");
    }

    /**
     * Фиксирует изменения транзакционного блока и завершает его
     * @return bool|mysqli_result - удалось ли зафиксировать изменения в транзакционном блоке
     * @throws RuntimeException - происходит если соединение с БД отсутствует
     */
    public function commit() {
        if ($this->connection)
            return mysqli_query($this->connection, "COMMIT;");
        else
            throw new RuntimeException("Ошибка на сервере: не удалось соединиться с базой данных");
    }

    /**
     * Откатывает изменения в транзакционном блоке и завершает его
     * @return bool|mysqli_result - удалось ли откатить изменения в транзакционном блоке
     * @throws RuntimeException - происходит если соединение с БД отсутствует
     */
    public function rollback() {
        if ($this->connection)
            return mysqli_query($this->connection, "ROLLBACK;");
        else
            throw new RuntimeException("Ошибка на сервере: не удалось соединиться с базой данных");
    }

    /**
     * Возвращает код ошибки последней операции
     * Код 0 означает, что ошибок нет, -1 - соединения с БД нет
     * @return int - код ошибки
     */
    public function get_errno() {
        if ($this->connection)
            return mysqli_errno($this->connection);
        else
            return -1;
    }

    /**
     * Возвращает текстовое представление последней ошибки
     * @return string - текстовое представление ошибки
     */
    public function get_error() {
        if ($this->connection)
            return mysqli_error($this->connection);
        else
            return "не удалось соединиться с базой данных";
    }
}
