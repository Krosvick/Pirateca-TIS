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
ratings_df = pd.concat([pd.read_csv('datasets/processed_ratings.csv', memory_map=True), pd.read_csv('datasets/test_user.csv', memory_map=True)])

# Load the model 
model = load('Algorithm.pkl')[1]
predictions = load('Algorithm.pkl')[0]

time2 = time.time()
print('Data loaded in ' + str(time2 - time1) + ' seconds.')

time1 = time.time()
# Get recommendations for a user
user = 270897
user_recommendations = Algorithm.get_user_recommendations(user, ratings_df, movies_df, model, predictions, top_n = 30)
time2 = time.time()
print('Recommendations generated in ' + str(time2 - time1) + ' seconds.')

time1 = time.time()
# Print the user's recommendations
for movie_id, rating in user_recommendations:
        print(movies_df[movies_df['id'] == movie_id]['original_title'].values[0])
time2 = time.time()
print('Recommendations printed in ' + str(time2 - time1) + ' seconds.')