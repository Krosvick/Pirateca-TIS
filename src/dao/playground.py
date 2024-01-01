from surprise import Reader, Dataset, SVD, evaluate
from surprise.dump import dump
import pandas as pd


reader = Reader()
ratings = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)

data = Dataset.load_from_df(ratings[['userId', 'movieId', 'rating']], reader)
data.split(n_folds=100)


svd = SVD()
evaluate(svd, data, measures=['RMSE', 'MAE'])

trainset = data.build_full_trainset()
svd.fit(trainset)

dump('svd_model.pkl', algo=svd)