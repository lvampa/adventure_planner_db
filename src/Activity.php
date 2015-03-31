<?php

    class Activity
    {
        private $id;
        private $name;

// Constructor for instances of the class
        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }
// Getters and Setters for private properties of class
        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setId($new_id)
        {
            $this->id = (int) $new_id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }
// Methods to interact with the Database

    // General CRUD methods
        function save()
        {
            $statement = $GLOBALS['DB']->query("INSERT INTO activities (name) VALUES ('{$this->getName()}') RETURNING id;");
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $this->setId($result['id']);
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE activities SET name = '{$new_name}' WHERE id = '{$this->getId()}';");
            $this->setName($new_name);
        }
        // Make sure to delete associations in join tables as well
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM activities WHERE id = '{$this->getId()}';");
            $GLOBALS['DB']->exec("DELETE FROM activities_countries WHERE id = '{$this->getId()}';");
            $GLOBALS['DB']->exec("DELETE FROM activities_adventures WHERE id = '{$this->getId()}';");
        }
    // Adding associations and droping them without deleting the whole activity
        function addCountry($country)
        {
            $GLOBALS['DB']->exec("INSERT INTO activites_countries (activity_id, country_id) VALUES ({$this->getId()}, {$country->getId()});");
        }

        function addAdventure($adventure)
        {
            $GLOBALS['DB']->exec("INSERT INTO activites_adventures (activity_id, country_id) VALUES ({$this->getId()}, {$adventure->getId()});");
        }

        function dropCountry($country)
        {
            $GLOBALS['DB']->exec("DELETE FROM activities_countries WHERE activity_id = {$this->getId()} AND country_id = {$country->getId()};");
        }

        function dropAdventure($adventure)
        {
            $GLOBALS['DB']->exec("DELETE FROM activities_adventures WHERE activity_id = {$this->getId()} AND adventure_id = {$adventure->getId()};");
        }
    // General delete all and get all methods
        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM activities *;");
        }

        static function getAll()
        {
            $statement = $GLOBALS['DB']->query("SELECT * FROM activities;");
            $activities = $statement->fetchAll(PDO::FETCH_ASSOC);

            $returned_activities = array();
            foreach($activities as $thing){
                $name = $thing['name'];
                $id = $thing['id'];
                $new_activity = new Activity($name, $id);
                array_push($returned_activities, $new_activity);
            }
            return $returned_activities;
        }
    // Find method to get Activity Id
        static function find($search_id)
        {
            $found_activity = null;
            $all_activities = Activity::getAll();
            foreach($all_activities as $thing){
                if($thing->getId() == $search_id){
                    $found_activity = $thing;
                }
            }
            return $found_activity;
        }

    // Methods for getting all countries and adventures which include this activity
        function getCountries()
        {
            
        }

        function getAdventures()
        {

        }



    }

 ?>