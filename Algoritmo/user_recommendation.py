from surprise import dump, Dataset, Reader
import pandas as pd
import time

start_time = time.time()

# Load data
model_file = 'svd_model_biased.pkl'
loaded_model = dump.load(model_file)[1]
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")
reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

half_time = time.time()


# WE NEED INPUT
user_id = 3


movie_ids = df['movieId'].unique()

# Create a list of movie IDs that the user has not rated
user_rated_movies = df[df['userId'] == user_id]['movieId']
movies_to_recommend = [movie_id for movie_id in movie_ids if movie_id not in user_rated_movies]
recommendations = []

for movie_id in movies_to_recommend:
    estimated_rating = loaded_model.predict(user_id, movie_id).est
    recommendations.append((movie_id, estimated_rating))

recommendations.sort(key=lambda x: x[1], reverse=True)

#ALSO WE NEED INPUT
top_N = 15
top_recommendations = recommendations[:top_N]

for i, (movie_id, estimated_rating) in enumerate(top_recommendations, 1):
    print(f"Recommendation {i}: Movie ID {movie_id}, Estimated Rating: {estimated_rating}")

end_time = time.time()
print("Time: ", end_time - start_time)
print("Time to load: ", half_time - start_time)