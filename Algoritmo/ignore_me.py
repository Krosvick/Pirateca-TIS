from surprise import dump, Dataset, Reader
import pandas as pd

model_file = 'svd_model_better.pkl'
loaded_model = dump.load(model_file)[1]
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")
reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)


