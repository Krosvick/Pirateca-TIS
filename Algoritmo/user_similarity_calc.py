import pandas as pd

from surprise import Dataset 
from surprise import Reader
from surprise import KNNBasic
from surprise.model_selection import train_test_split
from surprise.model_selection import GridSearchCV
from surprise.dump import dump, load

#el path a los datos es relativo mientras no podamos subir la bdd, falta funcion adapter
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")

reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state = 42)


knn_model = KNNBasic(random_state = 42,verbose = False)
knn_model.fit(train_ratings)

#la lectura de matrices no esta optimizada a continuacion

param_grid = {'k': list(range(10,45,5)),
             'min_k' : list(range(5,11))}

gs = GridSearchCV(KNNBasic, param_grid, measures=['rmse'], return_train_measures = True, cv = 5)

gs.fit(ratings) #aqui está la busqueda por similaridad

gs.best_params['rmse']

tuned_knn_model = KNNBasic(k = 15, min_k= 5,random_state = 42, verbose = False)
tuned_knn_model.fit(train_ratings)
train_predictions = tuned_knn_model.test(train_ratings.build_testset())
test_predictions = tuned_knn_model.test(test_ratings)

model_file = 'knn_model.pkl'
dump(model_file, algo=tuned_knn_model)