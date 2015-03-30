<?php

    class Adventure
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function save()
        {
            $query = $GLOBALS['DB']->query("INSERT INTO adventures (name) VALUES ('{$this->getName()}') RETURNING id;");
            $query_fetched = $query->fetch(PDO::FETCH_ASSOC);
            $this->setId($query_fetched['id']);

        }

        static function getAll()
        {
            $query = $GLOBALS['DB']->query("SELECT * FROM adventures");
            $query_fetched = $query->fetchAll(PDO::FETCH_ASSOC);
            $return_adventures = array();

            foreach($query_fetched as $element)
            {
                $name = $element['name'];
                $id = $element['id'];
                $new_adventure = new Adventure($name, $id);
                array_push($return_adventures, $new_adventure);
            }
            return $return_adventures;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM adventures *;");
        }


    }


 ?>