<?php

/**
 * #db_connect();
 *
 * @return mysqli|bool
 * @throws Exception
 */
function db_connect()
{
    global $database_config;

    if (!empty($database_config)) {

        $db = $database_config['conection']['mysql'];

        $conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);
        mysqli_set_charset($conn, $db['char_set']);

        return $conn;
    }
    throw new Exception("db config not found");
}

/**
 * #db_select('USER_TB');
 *
 * @param string $fullSelect
 * @param bool $fetchOutArray
 * @return array
 */
function db_select($fullSelect, $fetchOutArray = true)
{
    $items = array();
    $conn = db_connect();
    $d = now('d');

    $query_ = mysqli_query($conn, $fullSelect);
    write_log($fullSelect,  __DIR__ . "/../../logs/process/query_select_{$d}.txt");

    while ($rows = mysqli_fetch_assoc($query_)) {
        array_push($items, $rows);
    }

    mysqli_close($conn);

    if ($fetchOutArray) {
        return $items;
    }

    return !empty($items[0]) ? $items[0] : $items;
}

/**
 * #db_insert('USER_TB', ['NAME' => 'alex', 'PASS' => '1234']);
 *
 * @param string $tbl
 * @param array $data
 * @param string $primary
 * @return array|bool
 */
function db_insert($tbl, $data, $primary = '?')
{
    $conn = db_connect();
    $fields = implode(", ", array_keys($data));
    $values = "'" . implode("', '", array_values($data)) . "'";

    $d = now('d');
    $sql_insert = "INSERT INTO {$tbl}({$fields}) VALUES({$values})";

    $query_ = mysqli_query($conn, $sql_insert);
    write_log($sql_insert,  __DIR__ . "/../../logs/process/query_insert_{$d}.txt");

    mysqli_close($conn);
    if ($query_) {
        if ($primary != '?') {
            return db_select("SELECT * FROM {$tbl} ORDER BY {$primary} DESC LIMIT 1", false);
        }

        return true;
    }
    return false;
}

/**
 * #db_update('USER_TB', ['NAME' => 'alex', 'PASS' => '1234'], "USR_ID = '1'");
 *
 * @param string $tbl
 * @param array $data
 * @param array|string $condi
 * @return bool
 */
function db_update($tbl, $data, $condi)
{
    $fields = '';
    foreach ($data as $fields_ => $values_) {
        $fields .= !empty($fields) ? ', ' : '';
        $fields .= "{$fields_} = '{$values_}'";
    }

    $conn = db_connect();
    $d = now('d');
    $sql_upd = "UPDATE {$tbl} SET {$fields} WHERE {$condi}";
    write_log($sql_upd,  __DIR__ . "/../../logs/process/query_update_{$d}.txt");

    $query_upd = mysqli_query($conn, $sql_upd);

    mysqli_close($conn);
    return ($query_upd) ? true : false;
}

/**
 * #db_delete('USER_TB', ['USR_ID' => '1']);
 * #db_delete('USER_TB', "USR_ID = '1'");
 *
 * @param string $tbl
 * @param array|string $condi
 * @return bool
 */
function db_delete($tbl, $condi)
{
    $conn = db_connect();
    $sql_del = "DELETE FROM {$tbl} WHERE {$condi}";
    $d = now('d');

    $query_del = mysqli_query($conn, $sql_del);
    write_log($sql_del,  __DIR__ . "/../../logs/process/query_delete_{$d}.txt");

    mysqli_close($conn);
    return ($query_del) ? true : false;
}
