import pandas as pd

from surprise import Dataset 
from surprise import Reader
from surprise.model_selection import train_test_split

#el path a los datos es relativo mientras no podamos subir la bdd, falta funcion adapter
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")

reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state = 42)

#las funciones para matrix factorization de surprise han sido eliminadas despues de la ultima version, adaptarse con otra libreria