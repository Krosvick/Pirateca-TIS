import pandas as pd

from surprise import Dataset 
from surprise import Reader
from surprise.model_selection import train_test_split
from surprise import BaselineOnly

#el path a los datos es relativo mientras no podamos subir la bdd, falta funcion adapter
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")

reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state = 42)

baseline_model = BaselineOnly(verbose = False)
baseline_model.fit(train_ratings)

train_predictions = baseline_model.test(train_ratings.build_testset())
test_predictions = baseline_model.test(test_ratings)

movies = pd.read_csv("/home/kiwi/Escritorio/Movies/movies_metadata.csv")
movies.head()

def get_top_n_recommendations(userId,predictions, n=5):
    predict_ratings = {}
    # loop for getting predictions for the user
    for uid, iid, true_r, est, _ in predictions:
        if (uid==userId):
            predict_ratings[iid] = est
    predict_ratings = sorted(predict_ratings.items(), key=lambda kv: kv[1],reverse=True)[:n]
    top_movies = [i[0] for i in predict_ratings]
    top_movies = [str(i) for i in top_movies]
    print("="*10,"Recommended movies for user {} :".format(userId),"="*10)
    print(movies[movies["id"].isin(top_movies)]["original_title"].to_string(index=False))

#get_top_n_recommendations(450,test_predictions)
