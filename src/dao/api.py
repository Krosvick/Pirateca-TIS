from http.server import BaseHTTPRequestHandler, HTTPServer
import pandas as pd
from urllib.parse import urlparse, parse_qs
import requests
import json
import time
import threading
import sys

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
    ratings_df = pd.DataFrame(columns=['user_id', 'movie_id', 'rating'])
    model = None

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
            top_movies = Algorithm.get_user_recommendations(userId, SimpleAPI.ratings_df, SimpleAPI.model, 10)
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
        elif parsed_url.path == '/recommendations/ids':
            userId = int(query_params['userId'][0])
            n = int(query_params.get('n', [10])[0])
            top_movies = Algorithm.get_user_recommendations(userId, SimpleAPI.ratings_df, SimpleAPI.model, 10, False)
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
            SimpleAPI.model = Algorithm.generate_model(self.ratings_df)
            #return a message
            response = {'message': 'Model updated.'}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response).encode('utf-8'))
            
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
        if SimpleAPI.ratings_df is not None:  # Check if ratings_df is defined
            ratings_df = SimpleAPI.ratings_df  # Access ratings_df
            # Call the generate_model function
            SimpleAPI.model = Algorithm.generate_model(ratings_df)
            print('Model regenerated.')
        else:
            print('ratings_df is not defined yet. Waiting...')
        
        # Wait for 20 seconds before regenerating the model
        time.sleep(6000)
        


if __name__ == '__main__':
    #hard code here, connect with dao later
    csv_pd = pd.read_csv('datasets/ratings_small_cleaned.csv')
    #remove timestamp column
    csv_pd = csv_pd.drop(columns=['timestamp'])
    SimpleAPI.ratings_df =  csv_pd #initialize the ratings_df
    model_thread = threading.Thread(target=generate_model_periodically)
    model_thread.daemon = True
    model_thread.start()

    server_address = ('', 8001)
    httpd = HTTPServer(server_address, SimpleAPI)
    print('Starting server...')
    httpd.serve_forever()