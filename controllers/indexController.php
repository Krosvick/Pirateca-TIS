<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use GuzzleHttp\Client;

class IndexController extends BaseController
{
    public function index()
    {
        $dummy_user = new User();
        $dummy_user->user_id = 1;
        $user_movies = $dummy_user->get_recommended_movies(6);
        $client = new Client();
        foreach($user_movies as $movie){
            $poster_url = 'https://image.tmdb.org/t/p/original'.$movie['poster_path'];
            try{
                $response = $client->request('GET', $poster_url);
                #throw an error always
            }catch(\Exception $e){
                if($e->getCode() == 404){
                    $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movie['id'].'/images', [
                        'headers' => [
                            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJhMDNjYzg3YmQyZjg5YmVkZDM3MGI5MTJkYzMxZDU1MCIsInN1YiI6IjY1MGRjMzk4OTNkYjkyMDBlMTc4YWVhMSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.czSUap8DkmDxZy1UDMMq5angEIJz4A0SLY20WL84P28',
                            'accept' => 'application/json',
                        ]
                    ]);
                    $new_poster_response = json_decode($new_poster_request->getBody(), true);
                    $new_poster_url = 'https://image.tmdb.org/t/p/original'.$new_poster_response['posters'][0]['file_path'];
                    $movie['poster_path'] = $new_poster_url;
                    #update the movie poster path in the database
                    $movieDAO = new moviesDAO();
                    $movieDAO->update($movie['id'], $movie, ['poster_path']);
                }
            }
        }
        
        $peliculas = new moviesDAO();
        $result = $peliculas->get_some(3, 0);
        $result2 = $peliculas->find(6);

        $data = [
            'title' => 'Home',
            'result' => $result,
            'result2' => $result2,
            'user_movies' => $user_movies
        ];
        $this->render('testing_page', $data);
    }
}