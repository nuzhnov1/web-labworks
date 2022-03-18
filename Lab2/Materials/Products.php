<?php
class Products {
    private $db_ref; // Ссылка на БД

    public function __construct(Database $db) {
        $this->db_ref = $db;
    }

    /**
     * Получить представление таблицы продуктов в виде таблицы HTML (только теги "tr" и "td")
     * @param bool $update - если активно, то в таблицу включается возможность обновления строк
     * @param bool $delete - если активно, то в таблицу включается возможность удаления строк
     * @param int|null $count - если активен, то в таблицу записывается не более $count строк
     * @param string|null $name - если активен, то в таблицу записываются только строки с наименованием товара $name
     * @throws RuntimeException - если возникла ошибка при выполнении запроса или извлечении данных
     * @return string - последовательность html-тегов "tr" и "td" для отображения таблицы
     */
    public function get_html_view($update = false, $delete = false, $count = null, $name = null)
    {
        if ($count == null)
            $LIMIT = "";
        else
            $LIMIT = "LIMIT $count";

        if ($name == null)
            $WHERE = "";
        else
            $WHERE = "WHERE p.name = '$name'";

        $query = "
            SELECT
                p.id AS id,
                p.name AS name,
                p.price AS price,
                p.release_date AS release_date,
                m.name AS manufacturer_name,
                v.name AS vendor_name
            FROM products AS p
                INNER JOIN manufacturers AS m ON p.manufacturer_id = m.id
                INNER JOIN vendors AS v ON p.vendor_id = v.id
            $WHERE
            $LIMIT;
        ";

        $query_result = $this->db_ref->execute_query($query);
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

        for ($view = ""; $row = $query_result->fetch_assoc(); $view .= "</tr>") {
            $view .=
                "<tr>" .
                "<td>" . $row['name'] . "</td>" .
                "<td>" . $row['price'] . "</td>" .
                "<td>" . $row['release_date'] . "</td>" .
                "<td>" . $row['manufacturer_name'] . "</td>" .
                "<td>" . $row['vendor_name'] . "</td>";

            if ($update)
                $view .= "<td><a href='update.php?id=" . $row['id'] . "'>Обновить</a></td>";
            if ($delete)
                $view .= "<td><a href='delete.php?id=" . $row['id'] . "'>Удалить</a></td>";
        }

        mysqli_free_result($query_result);
        if ($this->db_ref->get_errno() != 0)
            throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

        return $view;
    }

    /**
     * Получить продукт по ИД
     * @param int $id - ИД продукта
     * @return array|null - строка (если продукт найден), иначе null - если такой строки нет
     * @throws RuntimeException - если возникла ошибка при выполнении запроса или извлечении данных
     * или если продукта с таким ИД нет
     */
    public function get_product_by_id($id) {
        $query = "
            SELECT id, name, price, release_date, manufacturer_id, vendor_id
            FROM products
            WHERE id = $id;
        ";

        $query_result = $this->db_ref->execute_query($query);
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

        $result = $query_result->fetch_assoc();
        mysqli_free_result($query_result);
        if (!$result)
            throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");
        else
            return $result;
    }

    /**
     * Получить таблицу средних цен товаров от каждого производителя
     * @throws RuntimeException - если возникла ошибка при выполнении запроса или при извлечении данных
     * @return array|mixed - таблица средних цен товаров от каждого производителя
     */
    public function get_avg_price_for_each_manufacturer() {
        $query = "
            SELECT
                manufacturers.name AS name,
                AVG(price) AS avg_price
            FROM products INNER JOIN manufacturers ON products.manufacturer_id = manufacturers.id
            GROUP BY manufacturers.name
            ORDER BY avg_price DESC;
        ";

        $query_result = $this->db_ref->execute_query($query);
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

        return $query_result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Получить таблицу средних цен товаров от каждого поставщика
     * @throws RuntimeException - если возникла ошибка при выполнении запроса или при извлечении данных
     * @return array|mixed - таблица средних цен товаров от каждого поставщика
     */
    public function get_avg_price_for_each_vendor() {
        $query = "
            SELECT
                vendors.name AS name,
                AVG(price) AS avg_price
            FROM products INNER JOIN vendors ON products.vendor_id = vendors.id
            GROUP BY vendors.name
            ORDER BY avg_price DESC;
        ";

        $query_result = $this->db_ref->execute_query($query);
        if (!$query_result)
            throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

        return $query_result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Добавляет новый товар в таблицу
     * @param string $name - наименование товара
     * @param double $price - цена товара
     * @param string $release_date - дата выпуска
     * @param int $manufacturer_id - id производителя
     * @param int $vendor_id - id поставщика
     * @throws RuntimeException - если произошла ошибка при выполнении запроса
     * @return void
     */
    public function add_product($name, $price, $release_date, $manufacturer_id, $vendor_id) {
        $is_ok = $this->db_ref->execute_query("
            INSERT INTO products(id, name, price, release_date, manufacturer_id, vendor_id)
            SELECT MAX(id) + 1, '$name', $price, '$release_date', $manufacturer_id, $vendor_id  FROM products;
        ");

        if (!$is_ok)
            throw new RuntimeException("Ошибка на сервере: не удалось добавить товар");
    }

    /**
     * Обновляет товар в таблице
     * @param int $id - ИД товара
     * @param string $name - наименование товара
     * @param double $price - цена товара
     * @param string $release_date - дата выпуска
     * @param int $manufacturer_id - ИД производителя
     * @param int $vendor_id - ИД поставщика
     * @throws RuntimeException - если произошла ошибка при выполнении запроса
     * @return void
     */
    public function update_product($id, $name, $price, $release_date, $manufacturer_id, $vendor_id) {
        $is_ok = $this->db_ref->execute_query("
            UPDATE products SET 
                name = '$name', price = $price, release_date = '$release_date',
                manufacturer_id = $manufacturer_id, vendor_id = $vendor_id
            WHERE id = $id;
        ");

        if (!$is_ok)
            throw new RuntimeException("Ошибка на сервере: не удалось обновить содержимое товара");
    }

    /**
     * Удаляет товар из таблицы
     * @param int $id - ИД удаляемого продукта
     * @throws RuntimeException - если произошла ошибка при выполнении запроса
     * @return void
     */
    public function delete_product($id) {
        if (!$this->db_ref->begin())
            throw new RuntimeException("Ошибка на сервере: не удалось удалить товар из таблицы");

        $delete_query = "DELETE FROM products WHERE id = $id;";
        $update_query = "UPDATE products SET id = id - 1 WHERE id > $id;";

        if (!$this->db_ref->execute_query($delete_query) || !$this->db_ref->execute_query($update_query)) {
            $this->db_ref->rollback();
            throw new RuntimeException("Ошибка на сервере: не удалось удалить товар из таблицы");
        }
        else if (!$this->db_ref->commit()) {
            throw new RuntimeException("Ошибка на сервере: не удалось удалить товар из таблицы");
        }
    }
}
