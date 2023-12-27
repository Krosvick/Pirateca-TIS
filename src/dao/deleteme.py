import pandas as pd
import sys
import time
from surprise.dump import dump, load
from surprise import Dataset, Reader
from surprise.model_selection import train_test_split
from surprise import accuracy
import os

sys.path.append('src')
from models.Algorithm import Algorithm

time1 = time.time()

# Load all movies
movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', usecols=['id', 'original_title'], memory_map=True)

# Load all ratings
ratings_df = pd.read_csv('datasets/processed_ratings.csv', memory_map=True)
reader = Reader()

# Load the model 
model = load('Algorithm_fixed.pkl')[1]
predictions = load('Algorithm_fixed.pkl')[0]

accuracy.rmse(predictions)

time2 = time.time()
print('Data loaded in ' + str(time2 - time1) + ' seconds.')

time1 = time.time()


UserRecommendations = []
# Get the top 100 movie recommendations for each 70 random users
for i in range(70):
    # Get a random user
    user = ratings_df.userId.sample().values[0]
    # Get the top 70 movie recommendations for the user
    top_recommendations = Algorithm.get_user_recommendations(user, ratings_df, movies_df, model, predictions, top_n=100)
    # Add the recommendations to the list
    UserRecommendations.append(top_recommendations)

# Count the number of times a movie is recommended UserRecommendations format = [ [ {'movie_id': movie_id, 'rating': rating}, {'movie_id': movie_id, 'rating': rating} ], [{}, {}, {}]]
MoviesAvg = []
for i in range(len(UserRecommendations)):
    for j in range(len(UserRecommendations[i])):
        movie_id = UserRecommendations[i][j]['movie_id']
        rating = UserRecommendations[i][j]['rating']
        if len(MoviesAvg) == 0:
            MoviesAvg.append({'movie_id': movie_id, 'rating': rating, 'count': 1})
        else:
            found = False
            for k in range(len(MoviesAvg)):
                if MoviesAvg[k]['movie_id'] == movie_id:
                    MoviesAvg[k]['rating'] += rating
                    MoviesAvg[k]['count'] += 1
                    found = True
                    break
            if not found:
                MoviesAvg.append({'movie_id': movie_id, 'rating': rating, 'count': 1})

# Sort MoviesAvg by the count from highest to lowest
MoviesAvg.sort(key=lambda x: x['count'], reverse=True)

for i in range(len(MoviesAvg)):
    print("Movie Id: ", MoviesAvg[i]['movie_id'] , "    Rating_total: ", MoviesAvg[i]['rating'], "    Count: ", MoviesAvg[i]['count'])


# Sort MoviesAvg by the count from lowest to highest
MoviesAvg.sort(key=lambda x: x['count'], reverse=False)

print("\n\n\n\n")
print("The 50 least recommended movies are: ")
for i in range(50):
    print("Movie Id: ", MoviesAvg[i]['movie_id'] , "    Rating: ", MoviesAvg[i]['rating'], "    Count: ", MoviesAvg[i]['count'])