import pandas as pd
import sys
import time
from surprise.dump import load, dump
from surprise import Dataset, Reader
from collections import defaultdict
import os


sys.path.append('src')
from models.Algorithm import Algorithm

#read the processed_ratings.csv file
time1 = time.time()
ratings_df = pd.read_csv('datasets/test_user.csv')
ratings_df2 = pd.read_csv('datasets/processed_ratings.csv', memory_map=True)
ratings_df = pd.concat([ratings_df2, ratings_df]) #add ratings_df to ratings_df2
del ratings_df2 #delete unnecessary variable
model = load('svd_model_biased_big_test.pkl')[1]
time2 = time.time()
print('Data loaded in ' + str(time2 - time1) + ' seconds.')

#tune algorithm
time1 = time.time()
model = Algorithm.tune_model(ratings_df, model)
time2 = time.time()
print('Model tuned in ' + str(time2 - time1) + ' seconds.')

#turn ratings_df into a surprise dataset
reader = Reader()
reader = Dataset.load_from_df(ratings_df[['userId', 'movieId', 'rating']], reader)
trainset = reader.build_full_trainset()
# Compute predictions of the 'original' algorithm.
predictions = model.test(trainset.build_testset())
dump('svd_model_biased_big_test_with_predictions.pkl', algo=model, predictions=predictions)