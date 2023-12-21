import pandas as pd
import sys
import time
from surprise.dump import load, dump
from surprise import Dataset, Reader
from collections import defaultdict
import os

sys.path.append('src')
from models.Algorithm import Algorithm

# Load all movies
all_movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', memory_map=True)

# Read the processed_ratings.csv file
time1 = time.time()
ratings_df = pd.read_csv('datasets/test_user.csv')
ratings_df2 = pd.read_csv('datasets/processed_ratings.csv', memory_map=True)
ratings_df = pd.concat([ratings_df2, ratings_df]) #add ratings_df to ratings_df2
del ratings_df2 #delete unnecessary variable

# Get the list of movies the user has seen
user_seen_movies = ratings_df[ratings_df['userId'] == 270897]['movieId'].unique()

# Get the list of movies the user hasn't seen
unseen_movies = all_movies_df[~all_movies_df['id'].isin(user_seen_movies)]

# Assuming 'userId' is the user for whom we want to make predictions
user_id = 270897

# Placeholder for the true rating
true_rating = 0.0  # or whatever is appropriate in your context

# Prepare the testset
unseen_movies_testset = [(user_id, movie_id, true_rating) for movie_id in unseen_movies['id']]


model = load('svd_model_biased_big_test_with_predictions.pkl')[1]
predictions = load('svd_model_biased_big_test_with_predictions.pkl')[0]
time2 = time.time()
print('Data loaded in ' + str(time2 - time1) + ' seconds.')

def get_top_n(predictions, n=10):
    # First map the predictions to each user.
    top_n = defaultdict(list)
    for uid, iid, true_r, est, _ in predictions:
        top_n[uid].append((iid, est))

    # Then sort the predictions for each user and retrieve the k highest ones.
    for uid, user_ratings in top_n.items():
        user_ratings.sort(key=lambda x: x[1], reverse=True)
        top_n[uid] = user_ratings[:n]

    return top_n

# Make predictions only on the unseen movies
predictions = model.test(unseen_movies_testset)

top_n = get_top_n(predictions, n=30)

# Print the recommended items for user 270897
print('User 270897')
for movie_id, rating in top_n[270897]:
    print(all_movies_df[all_movies_df['id'] == movie_id]['original_title'].values[0])