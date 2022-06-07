<?php

    namespace MF\Model;

    abstract Class Model{
        protected $db;

        function __construct(\PDO $db){
            $this->db = $db;   
        }
    }