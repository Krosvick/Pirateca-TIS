import pandas as pd
import sys
import time
from surprise.dump import load
import os

sys.path.append('src')
from models.Algorithm import Algorithm

time1 = time.time()

# Load all movies
movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', memory_map=True)

# Load all ratings
ratings_df = pd.read_csv('datasets/processed_ratings.csv', memory_map=True)

# Load the model 
model = load('Algorithm.pkl')[1]
predictions = Algorithm.get_all_predictions(ratings_df, model)

time2 = time.time()
print('Data loaded in ' + str(time2 - time1) + ' seconds.')

time1 = time.time()


# Get recommendations for a user
user = 909
user_recommendations = Algorithm.get_user_recommendations(user, ratings_df, movies_df, model, predictions, top_n = 10)
print(user_recommendations)
time2 = time.time()
print('Recommendations generated in ' + str(time2 - time1) + ' seconds.')