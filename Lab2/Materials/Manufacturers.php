<?php
class Manufacturers {
    private $db_ref;

    public function __construct(Database $db) {
        $this->db_ref = $db;
    }

    /**
     * Получить HTML представление списка производителей
     * @param int|null $selected - какое выбрано поле
     * @return string - HTML представление списка производителей
     * @throws RuntimeException - происходит, если возникла ошибка при выполнении запроса или при извлечении данных
     */
    public function get_html_list($selected = null) {
        $query_result = $this->db_ref->execute_query("SELECT * FROM manufacturers;");
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

        for ($view = ""; $row = $query_result->fetch_row();) {
            if ($selected != null && $row[0] == $selected)
                $view .= "<option value='$row[0]' label='$row[1]' selected></option>";
            else
                $view .= "<option value='$row[0]' label='$row[1]'></option>";
        }

        mysqli_free_result($query_result);
        if ($this->db_ref->get_errno() != 0)
            throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

        return $view;
    }
}
