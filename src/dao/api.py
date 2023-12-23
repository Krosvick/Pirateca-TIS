from http.server import BaseHTTPRequestHandler, HTTPServer
import pandas as pd
from urllib.parse import urlparse, parse_qs
import requests
import json
import time
import threading
import sys
import pydao
from surprise.dump import dump, load
import os



sys.path.append('src')
from models.Algorithm import Algorithm

class SimpleAPI(BaseHTTPRequestHandler):
    """
        Handle GET requests to the API.

        Parses the URL to determine the requested endpoint and query parameters.
        If the endpoint is '/recommendations', calls the 'get_top_n_recommendations'
        function from the 'Algorithm' module with the provided user ID,
        number of recommendations, predictions, and movies data. Returns a JSON
        response containing the top recommended movies for the user.
        If the endpoint is not recognized, returns a 404 error.

        Query params are expected to be in the format:
        localhost:8001/recommendations?userId=1&n=10
        where 'userId' is the ID of the user for whom to generate recommendations,
        and 'n' is the number of recommendations to generate.

        Args:
            self (SimpleAPI): The instance of the SimpleAPI class.
        
        Returns:
            None
    """


    """
    Disclaimer:
    The API works as a sv simulator, but due to latency issues, we fist read the ratings from the db
    So the first data is hardcoded BUT it's because we are emulating something
    we can not do unless this code works in the same server as the db (and planetscale doens't allow that)

    Due to this solution, all write in the planetscale db will be also writed in the local db
    only for it to load a first time well (also planetscale has a limit on querys)
    
    AFTER THE FIRST LOAD EVERYTHING WILL BE DONE IN THE PLANETSCALE DB
    """
    ratings_df = pd.read_csv('datasets/processed_ratings.csv', memory_map=True)
    model = load('Algorithm.pkl')[1]
    movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', memory_map=True)
    predictions = load('Algorithm.pkl')[0]

    def do_GET(self):
        parsed_url = urlparse(self.path)
        query_params = parse_qs(parsed_url.query)

        endpoints_info = {
            '/recommendations': 'Returns top n movie recommendations for the user with the provided user ID. Defaults to 10 recommendations.',
            '/': 'Returns a list of available endpoints.',
            '/new_endpoint': 'Returns a message with the received variable from a POST request.'
        }

        if parsed_url.path == '/recommendations':
            userId = int(query_params['userId'][0])
            n = int(query_params.get('n', [10])[0])
            top_movies = Algorithm.get_user_recommendations(userId, SimpleAPI.ratings_df, SimpleAPI.movies_df, SimpleAPI.model, SimpleAPI.predictions, 10)
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
        elif parsed_url.path == '/recommendations/ids':
            userId = int(query_params['userId'][0])
            n = int(query_params.get('n', [10])[0])
            top_movies = Algorithm.get_user_recommendations(userId, SimpleAPI.ratings_df, SimpleAPI.movies_df, SimpleAPI.model, SimpleAPI.predictions, 10)
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
        elif parsed_url.path == '/':
            response = {'endpoints_info': endpoints_info}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response).encode('utf-8'))
        else:
            self.send_response(404)
            self.end_headers()

    def do_POST(self):
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length).decode('utf-8')
        parsed_data = json.loads(post_data)

        #/ratings will receive all the ratings from the db and update the model
        #/from the data we need user_id, movie_id, rating
        if self.path == '/ratings':
            #parsed_data[0] is a individual rating like this
            #{'id': 1, 'rating': '2.5', 'review': None, 'movie_id': 1371, 'user_id': 1}
            # we need to iterate over the whole list and update the model
            for rating in parsed_data:
                #get the data
                user_id = rating['user_id']
                movie_id = rating['movie_id']
                rating = rating['rating']
            #update the model
            #add the rating to the ratings_df using concat
            self.ratings_df = pd.concat([self.ratings_df, pd.DataFrame([[user_id, movie_id, rating]], columns=['user_id', 'movie_id', 'rating'])])
            #regenerate the model
            SimpleAPI.model = Algorithm.tune_model(self.ratings_df, self.model)
            #return a message
            response = {'message': 'Model updated.'}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response).encode('utf-8'))

    def obtain_new_ratings(): #this is the adapter function
        test = pydao.DAO()
        rows = []
        for row in test.get_all_new():
            user_id = row[4]
            movie_id = row[3]
            rating = float(row[1])
            rows.append({'userId': user_id, 'movieId': movie_id, 'rating': rating})
        #concatenate the rows with the ratings_df saved in the api
        if len(rows) > 0:
            SimpleAPI.ratings_df = pd.concat([SimpleAPI.ratings_df, pd.DataFrame(rows)])
            return pd.DataFrame(rows)
        else:
            return None
            
import numpy as np

class NpEncoder(json.JSONEncoder):

    def default(self, obj):
        if isinstance(obj, np.integer):
            return int(obj)
        if isinstance(obj, np.floating):
            return float(obj)
        if isinstance(obj, np.ndarray):
            return obj.tolist()
        return super(NpEncoder, self).default(obj)

def generate_model_periodically():
    while True:
        if SimpleAPI.obtain_new_ratings() is not None:  # Check if ratings_df is defined
            # Call the generate_model function
            time1 = time.time()
            SimpleAPI.model = Algorithm.tune_model(SimpleAPI.ratings_df, SimpleAPI.model)
            time2 = time.time()
            print('Model regenerated in ' + str(time2 - time1) + ' seconds.')
        else:
            print('No new ratings.')
        
        time.sleep(100) #change to trigger
        SimpleAPI.ratings_df = SimpleAPI.obtain_new_ratings()  # Update ratings_df


if __name__ == '__main__':
    SimpleAPI.ratings_df =  SimpleAPI.obtain_new_ratings() #initialize the ratings_df
    model_thread = threading.Thread(target=generate_model_periodically)
    model_thread.daemon = True
    model_thread.start()

    server_address = ('', 8001)
    httpd = HTTPServer(server_address, SimpleAPI)
    print('Starting server...')
    httpd.serve_forever()