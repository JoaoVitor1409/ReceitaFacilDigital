<?php

    namespace MF\Model;

use App\Connection;

Class Container{

        public static function getModel($model){
            $class = "\\App\\Models\\" . ucfirst($model);
            $conn = Connection::getDb();

            return new $class($conn);
        }
    }