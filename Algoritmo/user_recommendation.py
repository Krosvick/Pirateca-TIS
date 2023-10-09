from surprise import dump, Dataset, Reader
import pandas as pd

#load data
model_file = 'svd_model_better.pkl'
loaded_model = dump.load(model_file)[1]
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")
reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)


# WE NEED ADAPTER
user_id = 43


movie_ids = df['movieId'].unique()

# create a list of movie IDs that the user has not rated
user_rated_movies = df[df['userId'] == user_id]['movieId']
movies_to_recommend = [movie_id for movie_id in movie_ids if movie_id not in user_rated_movies]
recommendations = []

for movie_id in movies_to_recommend:
    estimated_rating = loaded_model.predict(user_id, movie_id).est
    recommendations.append((movie_id, estimated_rating))

recommendations.sort(key=lambda x: x[1], reverse=True)

#ALSO WE NEED ADAPTER
top_N = 30
top_recommendations = recommendations[:top_N]

for i, (movie_id, estimated_rating) in enumerate(top_recommendations, 1):
    print(f"Recommendation {i}: Movie ID {movie_id}, Estimated Rating: {estimated_rating}")