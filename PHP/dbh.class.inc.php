<?php
class DatabaseHelper
{
    public static function connect($values = array())
    {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];

        $pdo = new PDO($connString, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
    public static function runQuery($pdo, $sql, $parameters = array())
    {
        // Ensure parameters are in an array
        if (!is_array($parameters)) {
            $parameters = array($parameters);
        }
        $statement = null;
        if (count($parameters) > 0) { // used for queries that need sanitization
            $statement = $pdo->prepare($sql);
            $executedOk = $statement->execute($parameters);
            if (!$executedOk) {
                throw new PDOException;
            } // if query finds no match throw error
        } else {
            $statement = $pdo->query($sql); // used for queries that need no sanitization
            if (!$statement) {
                throw new PDOException;
            } // if query finds no match throw error
        }
        return $statement;
    }
}
