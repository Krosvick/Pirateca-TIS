<?php

namespace Models;


//alo commit
    class Movie{


        public $id;
        public $original_title;
        public $overview;
        public $genres;
        public $belongs_to_collection;
        public $adult;
        public $original_language;
        public $release_date;
        public $poster_path;


        function __construct($id,$original_title,$overview,$genres,$belongs_to_collection,$adult,$original_language,$release_date){
            $this->id=$id;
            $this->original_title=$original_title;
            $this->overview=$overview;
            $this->genres=$genres;
            $this->belongs_to_collection=$belongs_to_collection;
            $this->adult=$adult;
            $this->original_language=$original_language;
            $this->release_date=$release_date;
        }

        public function get_movie_id(){
            return $this->id;
        }

        public function get_original_title(){
            return $this->original_title;
        }

        public function get_overview(){
            return $this->overview;
        }

        public function get_genres(){
            return $this->genres;
        }

        public function get_belongs_to_collection(){
            return $this->belongs_to_collection;
        }

        public function get_adult(){
            return $this->adult;
        }

        public function get_original_language(){
            return $this->original_language;
        }

        public function get_release_date(){
            return $this->release_date;
        }
        
    }
    ?>