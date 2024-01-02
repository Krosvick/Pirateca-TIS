import pandas as pd
import ast
import numpy as np

class AlgorithmContent():
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super(AlgorithmContent, cls).__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        import pandas as pd
        self.ratings_df = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)
        self.movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', usecols=['id', 'original_title', 'belongs_to_collection', 'genres'], memory_map=True)
    
    def get_movie_recommendations_recursive(self, movie_id_list, top_n):
        """
        examples of data:
        movie_id = 862
        belongs_to_collection = {'id': 10194, 'name': 'Toy Story Collection', 'poster_path': '/7G9915LfUQ2lVfwMEEhDsn3kT4B.jpg', 'backdrop_path': '/9FBwqcd9IRruEDUrTdcaafOMKUq.jpg'} or NaN
        genres = [{'id': 16, 'name': 'Animation'}, {'id': 35, 'name': 'Comedy'}, {'id': 10751, 'name': 'Family'}]
        """
        if top_n <= 0 or len(movie_id_list) == 0:
            return []

        movie_row = self.movies_df[self.movies_df['id'] == movie_id_list[0]] #due to preprocessing, the row always exists
        
        if movie_row['belongs_to_collection'].empty or pd.isna(movie_row['belongs_to_collection'].values[0]):
            genres = ast.literal_eval(movie_row['genres'].values[0])
            genres = [genre['name'] for genre in genres]
            mask = self.movies_df['genres'].apply(lambda x: any(genre in x for genre in genres))
            mask = mask.fillna(False)
            movies = self.movies_df[mask]
            movies = movies[movies['id'] != movie_id_list[0]]
            movies['genre_similarity'] = movies['genres'].apply(lambda x: len(set(genre['name'] for genre in ast.literal_eval(x)).intersection(genres)))
            movies = movies.sort_values(by='genre_similarity', ascending=False)
            movies = movies['id'].tolist()

            if len(movie_id_list) > top_n: # if there are more movies in the list than the top_n, return only the best top_n
                movies = movies[:1]
            else: # if there are less movies in the list than the top_n, return the top_n and finallize the recursion
                movies = movies[:top_n]
            
            movie_id_list.pop(0)
            top_n = top_n - len(movies)
            return movies + (self.get_movie_recommendations_recursive(movie_id_list, top_n))
        
        else:
            collection_id = movie_row['belongs_to_collection'].values[0].split(':')[1].split(',')[0]
            collection_id = int(collection_id)
            collection_mask = self.movies_df['belongs_to_collection'].str.contains(str(collection_id))
            collection_mask = collection_mask.fillna(False)
            collection = self.movies_df[collection_mask]
            collection = collection[collection['id'] != movie_id_list[0]]
            collection = collection['id'].tolist() #if belongs to collection, weights more than genres, return all movies from the collection
            movie_id_list.pop(0)
            top_n = top_n - len(collection)
            return collection + (self.get_movie_recommendations_recursive(movie_id_list, top_n))



    def get_user_recommendations(self, user_id, top_n=10):
        # get all movies rated by the user
        movies_rated = self.ratings_df[self.ratings_df['userId'] == user_id]
        # before calling the recursive function, order by priority, first delete the movies with bad ratings (< 3)
        movies_rated = movies_rated[movies_rated['rating'] >= 3]
        movies_rated = movies_rated['movieId'].tolist()
        ordered_movies = []
        # now order by collection, if the liked movie belongs to a collection, put them first in the list
        for movie in movies_rated:
            movie_row = self.movies_df[self.movies_df['id'] == movie]
            if movie_row.empty:
                continue
            elif movie_row['belongs_to_collection'].empty or pd.isna(movie_row['belongs_to_collection'].values[0]):
                ordered_movies.append(movie)
            else:
                ordered_movies.insert(0, movie)

        # call the recursive function
        recommendations = self.get_movie_recommendations_recursive(ordered_movies, top_n)
        # remove duplicates
        recommendations = list(set(recommendations))
        # remove movies already rated by the user
        recommendations = [movie for movie in recommendations if movie not in movies_rated]
        #shuffle the list
        np.random.shuffle(recommendations)
        # return the recommendations
        result = []
        for movie_id in recommendations[:top_n]:
            result.append({'movie_id': movie_id})

        return result
