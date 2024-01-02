from http.server import BaseHTTPRequestHandler, HTTPServer
import pandas as pd
from urllib.parse import urlparse, parse_qs
import requests
import json
import time
import threading
import sys
import pydao
import os
import numpy as np


sys.path.append('src')
from models.Algorithm_SVDpp import AlgorithmSVDpp
from models.Algorithm_content import AlgorithmContent

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
        
            

        Disclaimer:
        The API works as a sv simulator, but due to latency issues, we fist read the ratings from the db
        So the first data is hardcoded BUT it's because we are emulating something
        we can not do unless this code works in the same server as the db (and planetscale doens't allow that)

        Due to this solution, all write in the planetscale db will be also writed in the local db
        only for it to load a first time well (also planetscale has a limit on querys)
        
        AFTER THE FIRST LOAD EVERYTHING WILL BE DONE IN THE PLANETSCALE DB
    """

    #__init__ singleton
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)

    def do_GET(self):
        """
        Handles GET requests to the API.

        Parses the URL to determine the requested endpoint and query parameters.
        If the endpoint is recognized, it performs the corresponding action and returns a response.
        If the endpoint is not recognized, it returns a 404 error.
        
        #############   SHOULD CALL A STRATEGY PATTERN WHICH CALLS THE ALGORITHM CORRESPONDING METHOD    #############

        :return: JSON response containing the requested data or an error message.
        """
        parsed_url = urlparse(self.path)
        query_params = parse_qs(parsed_url.query)

        endpoints_info = {
            '/recommendations': 'Returns top n movie recommendations for the user with the provided user ID. Defaults to 10 recommendations.',
            '/': 'Returns a list of available endpoints.',
            '/new_endpoint': 'Returns a message with the received variable from a POST request.'
        }

        if parsed_url.path == '/recommendations':
            time1 = time.time()
            print(query_params, '/recommendations')
            userId = int(query_params['userId'][0])
            #print every data in same "print" function to check if it's working, all df should print sample
            n = int(query_params['n'][0])
            top_movies = Strategy([userId, n]).execute() #TODO: change n of ratings dinamically in context (strategy pattern)
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
            print('Recommendations generated in ' + str(time.time() - time1) + ' seconds.')
        elif parsed_url.path == '/recommendations/ids':
            #this is the principal request handler, same as upper but with different url to avoid conflicts with upcoming
            #data extraction from the request
            time1 = time.time()
            print(query_params, '/recommendations/ids')
            userId = int(query_params['userId'][0])
            n = int(query_params['n'][0])
            #data processing via algorithm
            top_movies = Strategy([userId, n]).execute() #TODO: change n of ratings dinamically in context (strategy pattern)
            print(top_movies)
            #json serialization and response
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
            print('Recommendations generated in ' + str(time.time() - time1) + ' seconds.')
        elif parsed_url.path == '/':
            time1 = time.time()
            print(query_params, '/')
            response = {'endpoints_info': endpoints_info}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response).encode('utf-8'))
            print('Endpoints info generated in ' + str(time.time() - time1) + ' seconds.')
        else:
            self.send_response(404)
            self.end_headers()



class NpEncoder(json.JSONEncoder):

    def default(self, obj):
        """
        Customizes the JSON encoding process for NumPy objects.

        Converts NumPy integers, floats, and arrays to their corresponding Python types before encoding them as JSON.
        
        Could be considered an adapter pattern.

        Args:
            obj: The object to be encoded as JSON.

        Returns:
            The encoded JSON representation of the input object.
        """
        if isinstance(obj, np.integer):
            return int(obj)
        if isinstance(obj, np.floating):
            return float(obj)
        if isinstance(obj, np.ndarray):
            return obj.tolist()
        return super(NpEncoder, self).default(obj)
    

class Semi_factory:

    _algoritmo = None

    def __init__(self, algoritmo = None):
        self._algoritmo = algoritmo

    def get_new_ratings(self):
        """
        Adapter function that obtains new ratings from the database and convert them to the algorithm format.

        Returns:
        - If there are new ratings, returns a DataFrame containing the new ratings.
        - If there are no new ratings, returns None.
        """
        dao = pydao.DAO()
        rows = []
        for row in dao.get_all_new():
            user_id = row[4]
            movie_id = row[3]
            rating = float(row[1])
            rows.append({'userId': user_id, 'movieId': movie_id, 'rating': rating})
        
        if len(rows) > 1:
            print('New ratings found.')
            self._algoritmo.ratings_df = pd.concat([self._algoritmo.ratings_df, pd.DataFrame(rows)])
            return True
        else:
            return False

    def generate_model_periodically(self):
        """
        Periodically generates a recommendation model based on new ratings data.
        
        It continuously checks for new ratings, and if there are any, it updates the existing ratings dataframe and tunes the model using the `tune_model` function from the `Algorithm` class. The function then sleeps for a specified period of time before checking for new ratings again.
        
        Example Usage:
        ```python
        # Start generating the model periodically
        generate_model_periodically()
        ```
        
        Inputs: None
        
        Flow:
        1. The function enters an infinite loop.
        2. It calls the `get_new_ratings` function from the `SimpleAPI` class to get new ratings data.
        3. If new ratings data is available, it updates the existing ratings dataframe by concatenating the new data.
        4. It calls the `tune_model` function from the `Algorithm` class to tune the model using the updated ratings dataframe.
        5. The function prints the time taken to regenerate the model.
        6. If no new ratings data is available, the function prints a message indicating that.
        7. The function sleeps for a specified period of time.
        8. The loop continues from step 2.
        
        Outputs: None
        """
        while True:
            new_ratings = self.get_new_ratings()

            if new_ratings:  # Check if there are new ratings
                # Update the ratings dataframe (for complete use in recommendation, not complete use in tuning)
                self._algoritmo.ratings_df.to_csv('datasets/reprocessed_ratings.csv', index=False)
                time1 = time.time()
                # Tune the model with the new ratings (only using the new ratings, has own offset)
                self._algoritmo.model = self._algoritmo.tune_model() 
                time2 = time.time()
                print('Model regenerated in ' + str(time2 - time1) + ' seconds.')
            else:
                print('No new ratings.')
            
            # wait 100 seconds before checking for new ratings again
            del new_ratings
            time.sleep(100) # TODO: change to 100 in presentation



class Strategy:
    """
    This is strategy pattern half applied to the algorithm class, not fully applied because we merge context and strategy in the same
    merge is only due to the scale of the project, can unmerge if needed later

    can add more vars to context if needed, like the list of movies rated by the user 
    

    Inputs: context [id_user, n_ratings]

    Outputs: top_n_movies

    Example Usage:
    strategy = Strategy([1, 0])
    """
    context = None

    def __init__(self, context):
        self.context = context
        self.algorithmSVDpp = AlgorithmSVDpp()
        self.algorithm_content = AlgorithmContent()
    
    def execute(self): # Here we can change functioning of the algorithm depending on the context 
        if self.context[1] <= 20:
            return self.algorithm_content.get_user_recommendations(self.context[0])
        else:
            return self.algorithmSVDpp.get_user_recommendations(self.context[0])
        

if __name__ == '__main__':
    """
    Starts a new thread that periodically tunes a recommendation model based on new ratings data.
    Then starts an HTTP server that listens for incoming requests and serves the API.
    Both process run in parallel and only communicate through the ratings dataframe and the model.
    Semi_factory uses the strategy pattern to universally train models from different algorithms, only needs the algorithm to have the same name of methods.
    Can thread more than one model at the same time.

    Example Usage:
    python3 script.py

    Inputs:
    None

    Outputs:
    None
    """
    semi_factory = Semi_factory(AlgorithmSVDpp()) # due to algorithm being a singleton class we can use it as a parameter
    #we dont factory the content algorithm because it doesn't need to be trained

    model_thread = threading.Thread(target=semi_factory.generate_model_periodically)
    model_thread.daemon = True
    model_thread.start()

    server_address = ('', 8001)
    httpd = HTTPServer(server_address, SimpleAPI)
    print('Starting server...')
    httpd.serve_forever()