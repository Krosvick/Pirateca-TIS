import pandas as pd

from surprise import Reader
from surprise import Dataset 
from surprise.dump import dump, load

loaded_model = load("knn_model.pkl")
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")
reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

def get_top_n_recommendations(userId, model, n=30):
    items_unrated_by_user = [item for item in ratings.build_full_trainset().all_items() if item not in ratings.build_full_trainset().ur[userId]]
    predicted_ratings = [(item, model.predict(userId, item).est) for item in items_unrated_by_user]
    top_n_items = sorted(predicted_ratings, key=lambda x: x[1], reverse=True)[:n]
    top_n_item_ids = [item[0] for item in top_n_items]

    return top_n_item_ids

# Usage:
user_id = 1  # Replace with the target user's ID
recommended_items = get_top_n_recommendations(user_id, loaded_model, n=10)
