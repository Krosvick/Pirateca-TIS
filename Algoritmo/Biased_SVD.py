from surprise import SVD
import numpy as np
import pandas as pd

from surprise import Dataset 
from surprise import Reader
from surprise.model_selection import train_test_split
from surprise.model_selection import RandomizedSearchCV
from surprise.dump import dump
from surprise import accuracy
import time

start_time = time.time()
# Load data
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")

# Define the reader
reader = Reader()
ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

# Split data into training and testing sets
train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state=42)

# Initialize and fit the SVD model
svd_model = SVD(random_state=42)
svd_model.fit(train_ratings)

# Perform hyperparameter tuning
param_distributions = {
    'n_factors': list(range(50, 160, 10)),
    'reg_all': np.arange(0.02, 0.2, 0.02),
    'n_epochs': list(range(1, 51))
}
# ------------------------------------ CHANGE ITERATIONS FOR PERFORMANCE ------------------------------------
rs = RandomizedSearchCV(SVD, param_distributions, measures=['rmse'], return_train_measures=True, cv=5, n_iter=40)
rs.fit(ratings)

# Get the best hyperparameters
best_params = rs.best_params['rmse']

# Initialize and fit the tuned SVD model
tuned_svd_model = SVD(n_factors=best_params['n_factors'], reg_all=best_params['reg_all'], n_epochs=best_params['n_epochs'], random_state=42, verbose=False)
tuned_svd_model.fit(train_ratings)

# Evaluate the model on training and testing sets
train_predictions = tuned_svd_model.test(train_ratings.build_testset())
test_predictions = tuned_svd_model.test(test_ratings)

# Biased SVD
biased_svd_model = SVD(biased=True, random_state=42)
biased_svd_model.fit(train_ratings)

# Evaluate the model on training and testing sets
train_predictions = biased_svd_model.test(train_ratings.2build_testset())
test_predictions = biased_svd_model.test(test_ratings)
print("Biased SVD Model Train RMSE:", accuracy.rmse(train_predictions, verbose=False))
print("Biased SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=False))

# Save the tuned model to a file
model_file = 'svd_model_biased.pkl'
dump(model_file, algo=biased_svd_model)

end_time = time.time()
print("Execution time: ", end_time - start_time)