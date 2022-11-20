<?php

    namespace App;

    class Connection{

        public static function getDb(){
            try{
                $conn = new \PDO(
                    "mysql:host=localhost;dbname=rfd;charset=UTF8",
                    "root",
                    ""
                );

                return $conn;
                
            }catch(\PDOException $e){
                echo $e;
            }
        }        

    }