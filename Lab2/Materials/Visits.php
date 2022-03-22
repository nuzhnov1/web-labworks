<?php
    // Размеры изображения диаграмм:
    const IM_WIDTH = 1920;
    const IM_HEIGHT = 1080;
    const TEXT_HEIGHT = 40;
    const VALUE_HEIGHT = 40;
    const COLUMN_OFFSET_PERCENT = 10;

    class Visits {
        private $db_ref;

        public function __construct(Database $db) {
            $this->db_ref = $db;
        }

        private function get_visits_array($begin = null, $end = null) {
            if ($begin == null)
                $begin = '1000-01-01';
            if ($end == null)
                $end = '9999-12-31';
            if ($begin > $end)
                throw new RuntimeException("начальная дата не может быть больше конечной");

            $query = "
                SELECT page, SUM(count) AS visits_count
                FROM visits
                WHERE day BETWEEN '$begin' AND '$end'
                GROUP BY page
                ORDER BY visits_count DESC;
            ";

            $query_result = $this->db_ref->execute_query($query);
            if (!$query_result)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            for ($result = array(); $row = $query_result->fetch_assoc();)
                $result[] = $row;

            mysqli_free_result($query_result);
            if ($this->db_ref->get_errno())
                throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

            return $result;
        }

        public function get_visits_table($begin = null, $end = null) {
            $table = $this->get_visits_array($begin, $end);

            $view = "";
            foreach ($table as $row) {
                $page = $row['page'];
                $visits_count = $row['visits_count'];

                $view .=
                    "<tr>" .
                    "<td>$page</td>" .
                    "<td>$visits_count</td>" .
                    "<td><a href='page_hist.php?page=$page'>Хронология посещения страницы</a></td>" .
                    "</tr>";
            }

            return $view;
        }

        public function get_visits_diagram($begin = null, $end = null) {
            putenv("GDFONTPATH=" . realpath("."));

            $table = $this->get_visits_array($begin, $end);

            $im = imagecreate(IM_WIDTH, IM_HEIGHT);

            $BACKGROUND_COLOR = imagecolorallocate($im, 255, 255, 255);
            $TEXT_COLOR = imagecolorallocate($im, 0, 0, 0);

            $ROWS_COUNT = count($table);
            $HEIGHT = IM_HEIGHT - (TEXT_HEIGHT + VALUE_HEIGHT);
            $COLUMN_WIDTH_PLUS_OFFSET = floor(IM_WIDTH / $ROWS_COUNT);
            $COLUMN_WIDTH = round($COLUMN_WIDTH_PLUS_OFFSET * (1 - COLUMN_OFFSET_PERCENT / 100));
            $OFFSET = $COLUMN_WIDTH_PLUS_OFFSET - $COLUMN_WIDTH;
            $MAX_VISITS = $table[0]['visits_count'];

            imagefill($im, 0, 0, $BACKGROUND_COLOR);

            for ($i = 0, $x1 = -($COLUMN_WIDTH + $OFFSET); $i < $ROWS_COUNT; $i++) {
                $row = $table[$i];
                $page = $row['page'];
                $visits_count = $row['visits_count'];

                $red = rand(0, 200);
                $green = rand(0, 200);
                $blue = rand(0, 200);
                $column_color = imagecolorallocate($im, $red, $green, $blue);

                $x1 += $COLUMN_WIDTH + $OFFSET;
                $y1 = (IM_HEIGHT - 1) - TEXT_HEIGHT;
                $x2 = $x1 + $COLUMN_WIDTH;
                $y2 = $y1 - floor(($visits_count / $MAX_VISITS) * ($HEIGHT));

                $name_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $page);
                $name_offset = round((($x2 - $x1) - ($name_box[2] - $name_box[0])) / 2);
                $name_height = $name_box[7] - $name_box[1];
                $price_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $visits_count);
                $price_offset = round((($x2 - $x1) - ($price_box[2] - $price_box[0])) / 2);

                imagefilledrectangle($im, $x1, $y1, $x2, $y2, $column_color);
                imagettftext($im, 18, 0, $x1 + $name_offset, $y1 - $name_height, $TEXT_COLOR, "TimesNewRoman.ttf", $page);
                imagettftext($im, 18, 0, $x1 + $price_offset, $y2, $TEXT_COLOR, "TimesNewRoman.ttf", $visits_count);
            }

            return $im;
        }

        private function get_visitors_array() {
            $query = "SELECT DISTINCT user FROM visits;";

            $query_result = $this->db_ref->execute_query($query);
            if (!$query_result)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            for ($result = array(); $row = $query_result->fetch_assoc();)
                $result[] = $row;

            mysqli_free_result($query_result);
            if ($this->db_ref->get_errno())
                throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

            return $result;
        }

        public function get_visitors_table() {
            $table = $this->get_visitors_array();

            $view = "";
            foreach ($table as $row) {
                $user = $row['user'];

                $view .=
                    "<tr>" .
                    "<td>$user</td>" .
                    "<td><a href='user_stat.php?user=$user'>Статистика посещений пользователя</a></td>" .
                    "<td><a href='user_hist.php?user=$user'>Хронология посещений пользователя</a></td>" .
                    "</tr>";
            }

            return $view;
        }

        private function get_user_stat_array($user, $begin = null, $end = null) {
            if ($begin == null)
                $begin = '1000-01-01';
            if ($end == null)
                $end = '9999-12-31';
            if ($begin > $end)
                throw new RuntimeException("начальная дата не может быть больше конечной");


            $query = "
                SELECT page, SUM(count) AS visits_count
                FROM visits
                WHERE (day BETWEEN '$begin' AND '$end') AND (user = '$user')
                GROUP BY page
                ORDER BY visits_count DESC;
            ";

            $query_result = $this->db_ref->execute_query($query);
            if (!$query_result)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            for ($result = array(); $row = $query_result->fetch_assoc();)
                $result[] = $row;

            mysqli_free_result($query_result);
            if ($this->db_ref->get_errno())
                throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

            return $result;
        }

        public function get_user_stat_table($user, $begin = null, $end = null) {
            $table = $this->get_user_stat_array($user, $begin, $end);

            $view = "";
            foreach ($table as $row) {
                $page = $row['page'];
                $visits_count = $row['visits_count'];

                $view .=
                    "<tr>" .
                    "<td>$page</td>" .
                    "<td>$visits_count</td>" .
                    "</tr>";
            }

            return $view;
        }

        public function get_user_stat_diagram($user, $begin = null, $end = null) {
            putenv("GDFONTPATH=" . realpath("."));

            $table = $this->get_user_stat_array($user, $begin, $end);

            $im = imagecreate(IM_WIDTH, IM_HEIGHT);

            $BACKGROUND_COLOR = imagecolorallocate($im, 255, 255, 255);
            $TEXT_COLOR = imagecolorallocate($im, 0, 0, 0);

            $ROWS_COUNT = count($table);
            $HEIGHT = IM_HEIGHT - (TEXT_HEIGHT + VALUE_HEIGHT);
            $COLUMN_WIDTH_PLUS_OFFSET = floor(IM_WIDTH / $ROWS_COUNT);
            $COLUMN_WIDTH = round($COLUMN_WIDTH_PLUS_OFFSET * (1 - COLUMN_OFFSET_PERCENT / 100));
            $OFFSET = $COLUMN_WIDTH_PLUS_OFFSET - $COLUMN_WIDTH;
            $MAX_VISITS = $table[0]['visits_count'];

            imagefill($im, 0, 0, $BACKGROUND_COLOR);

            for ($i = 0, $x1 = -($COLUMN_WIDTH + $OFFSET); $i < $ROWS_COUNT; $i++) {
                $row = $table[$i];
                $page = $row['page'];
                $visits_count = $row['visits_count'];

                $red = rand(0, 200);
                $green = rand(0, 200);
                $blue = rand(0, 200);
                $column_color = imagecolorallocate($im, $red, $green, $blue);

                $x1 += $COLUMN_WIDTH + $OFFSET;
                $y1 = (IM_HEIGHT - 1) - TEXT_HEIGHT;
                $x2 = $x1 + $COLUMN_WIDTH;
                $y2 = $y1 - floor(($visits_count / $MAX_VISITS) * ($HEIGHT));

                $name_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $page);
                $name_offset = round((($x2 - $x1) - ($name_box[2] - $name_box[0])) / 2);
                $name_height = $name_box[7] - $name_box[1];
                $price_box = imagettfbbox(18, 0, "TimesNewRoman.ttf", $visits_count);
                $price_offset = round((($x2 - $x1) - ($price_box[2] - $price_box[0])) / 2);

                imagefilledrectangle($im, $x1, $y1, $x2, $y2, $column_color);
                imagettftext($im, 18, 0, $x1 + $name_offset, $y1 - $name_height, $TEXT_COLOR, "TimesNewRoman.ttf", $page);
                imagettftext($im, 18, 0, $x1 + $price_offset, $y2, $TEXT_COLOR, "TimesNewRoman.ttf", $visits_count);
            }

            return $im;
        }

        private function get_user_hist_array($user, $begin = null, $end = null) {
            if ($begin == null)
                $begin = '1000-01-01';
            if ($end == null)
                $end = '9999-12-31';
            if ($begin > $end)
                throw new RuntimeException("начальная дата не может быть больше конечной");


            $query = "
                SELECT page, day, count
                FROM visits
                WHERE (day BETWEEN '$begin' AND '$end') AND (user = '$user')
                ORDER BY day DESC;
            ";

            $query_result = $this->db_ref->execute_query($query);
            if (!$query_result)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            for ($result = array(); $row = $query_result->fetch_assoc();)
                $result[] = $row;

            mysqli_free_result($query_result);
            if ($this->db_ref->get_errno())
                throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

            return $result;
        }

        public function get_user_hist_table($user, $begin = null, $end = null) {
            $table = $this->get_user_hist_array($user, $begin, $end);

            $view = "";
            foreach ($table as $row) {
                $page = $row['page'];
                $day = $row['day'];
                $count = $row['count'];

                $view .=
                    "<tr>" .
                    "<td>$page</td>" .
                    "<td>$day</td>" .
                    "<td>$count</td>" .
                    "</tr>";
            }

            return $view;
        }

        private function get_page_hist_array($page, $begin = null, $end = null) {
            if ($begin == null)
                $begin = '1000-01-01';
            if ($end == null)
                $end = '9999-12-31';
            if ($begin > $end)
                throw new RuntimeException("начальная дата не может быть больше конечной");

            $query = "
                SELECT user, day, count
                FROM visits
                WHERE (day BETWEEN '$begin' AND '$end') AND (page = '$page')
                ORDER BY day DESC;
            ";

            $query_result = $this->db_ref->execute_query($query);
            if (!$query_result)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            for ($result = array(); $row = $query_result->fetch_assoc();)
                $result[] = $row;

            mysqli_free_result($query_result);
            if ($this->db_ref->get_errno())
                throw new RuntimeException("Ошибка на сервере: не удалось извлечь данные");

            return $result;
        }

        public function get_page_hist_table($page, $begin = null, $end = null) {
            $table = $this->get_page_hist_array($page, $begin, $end);

            $view = "";
            foreach ($table as $row) {
                $user = $row['user'];
                $day = $row['day'];
                $count = $row['count'];

                $view .=
                    "<tr>" .
                    "<td>$user</td>" .
                    "<td>$day</td>" .
                    "<td>$count</td>" .
                    "</tr>";
            }

            return $view;
        }

        public function add_visit($page) {
            session_start();

            if (isset($_SESSION['username'])) {
                if ($_SESSION['username'] == 'guest')
                    $user = $_SERVER['REMOTE_ADDR'];
                else
                    $user = $_SESSION['username'];
            }
            else
                $user = $_SERVER['REMOTE_ADDR'];

            $cur_date = date("Y-m-d");
            $query_result = $this->db_ref->execute_query("
                SELECT COUNT(*) FROM visits
                WHERE (user = '$user') AND (page = '$page') AND (day = '$cur_date');
            ");
            $visits_count = $query_result->fetch_array();
            if (!$query_result && !$visits_count)
                throw new RuntimeException("Ошибка на сервере: " . $this->db_ref->get_error());

            $visits_count = $visits_count[0];

            if ($visits_count > 0) {
                $is_ok = $this->db_ref->execute_query("
                    UPDATE visits SET count = count + 1
                    WHERE (page = '$page') AND (user = '$user') AND (day = '$cur_date');
                ");
            }
            else {
                $is_ok = $this->db_ref->execute_query("
                    INSERT INTO visits(page, user, day) VALUE ('$page', '$user', '$cur_date');
                ");
            }

            if (!$is_ok)
                throw new RuntimeException("Ошибка на сервере: не удалось добавить посещение");
        }

        public function truncate() {
            $is_ok = $this->db_ref->execute_query("
                TRUNCATE TABLE visits;
            ");

            if (!$is_ok)
                throw new RuntimeException("Ошибка на сервере: не удалось очистить таблицу посещений");
        }
    }
