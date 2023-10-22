<?php

require_once __DIR__ .'/Core/Database.php';
//alo commit
    class pelicula_model{

        private $conexion;
        public $adult;
        public $belongs_to_collection;
        public $budget;
        public $genres;
        public $homepage;
        public $id;
        public $imdb_id;
        public $original_language;
        public $original_title;
        public $overview;
        public $popularity;
        public $poster_path;
        public $production_companies;
        public $production_countries;
        public $release_date;
        public $revenue;
        public $runtime;
        public $spoken_languages;
        public $status;
        public $tagline;
        public $title;
        public $video;
        public $vote_average;
        public $vote_count;


        function __construct(){
            try
            {
                $this->conexion = new Database();
                $this->conexion = $this->conexion->getInstance();
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }

        public function register(peliculas $data){
            try{
                $sql = "INSERT INTO peliculas (adult, belongs_to_collection, budget, genres, homepage, id, imdb_id, original_language, original_title, overview, popularity, poster_path, production_companies, production_countries, release_date, revenue, runtime, spoken_languages, status, tagline, title, video, vote_average, vote_count)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $this->conexion->prepare($sql)->execute(array(
                    $data->adult,
                    $data->belongs_to_collection,
                    $data->budget,
                    $data->genres,
                    $data->homepage,
                    $data->id,
                    $data->imdb_id,
                    $data->original_language,
                    $data->original_title,
                    $data->overview,
                    $data->popularity,
                    $data->poster_path,
                    $data->production_companies,
                    $data->production_countries,
                    $data->release_date,
                    $data->revenue,
                    $data->runtime,
                    $data->spoken_languages,
                    $data->status,
                    $data->tagline,
                    $data->title,
                    $data->video,
                    $data->vote_average,
                    $data->vote_count
                ));
            }
            catch(Exception $e){
                die($e->getMessage());
            }
        }

        public function actualizar($data){
            try
            {
                $sql = "UPDATE peliculas SET 
                            adult = ?,
                            belongs_to_collection = ?,
                            budget = ?,
                            genres = ?,
                            homepage = ?,
                            id = ?,
                            imdb_id = ?,
                            original_language = ?,
                            original_title = ?,
                            overview = ?,
                            popularity = ?,
                            poster_path = ?,
                            production_companies = ?,
                            production_countries = ?,
                            release_date = ?,
                            revenue = ?,
                            runtime = ?,
                            spoken_languages = ?,
                            status = ?,
                            tagline = ?,
                            title = ?,
                            video = ?,
                            vote_average = ?,
                            vote_count = ?
                        WHERE id = ?";

                $this->conexion->prepare($sql)->execute(array(
                    $data->adult,
                    $data->belongs_to_collection,
                    $data->budget,
                    $data->genres,
                    $data->homepage,
                    $data->id,
                    $data->imdb_id,
                    $data->original_language,
                    $data->original_title,
                    $data->overview,
                    $data->popularity,
                    $data->poster_path,
                    $data->production_companies,
                    $data->production_countries,
                    $data->release_date,
                    $data->revenue,
                    $data->runtime,
                    $data->spoken_languages,
                    $data->status,
                    $data->tagline,
                    $data->title,
                    $data->video,
                    $data->vote_average,
                    $data->vote_count,
                    $data->id
                ));

            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }

        }

        public function eliminar($id){
            try
            {
                $sql = "DELETE FROM peliculas WHERE id = ?";
                $this->conexion->prepare($sql)->execute(array($id));
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }

        public function obtener($id_movie){
            try {
                $stm = $this->conexion->prepare("SELECT * FROM peliculas WHERE id = ?");
                $stm->execute(array($id_movie));
                $r = $stm->fetch(PDO::FETCH_OBJ);
                return $r;
            }
            catch (Exception $e)
            {
                die($e->getMessage());
            }
        }

        public function obtenerTodos(){
            try
            {
                $result = array();

                $stm = $this->conexion->prepare("SELECT * FROM peliculas");
                $stm->execute();

                return $stm->fetchAll(PDO::FETCH_OBJ);
            }
            catch(Exception $e)
            {
                die($e->getMessage());
            }
        }
        
    }
    ?>