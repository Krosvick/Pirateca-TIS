
#file that will load movies_metadata_cleaned.csv and ratings_small.csv and will look for the ids in ratings that are not in movies_metadata_cleaned.csv
#then it will remove those rows from ratings_small.csv and save it as ratings_small_cleaned.csv

import pandas as pd
import numpy as np
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))

def load_data():
    df = pd.read_csv('datasets/movies_metadata_cleaned.csv', low_memory=False)
    return df

def load_ratings():
    df = pd.read_csv('datasets/ratings_small.csv', low_memory=False)
    return df

def intersect(df, ratings):
    df = df[['id']]
    df = df.astype(str)
    ratings = ratings[['movieId']]
    ratings = ratings.astype(str)
    df = df.merge(ratings, left_on='id', right_on='movieId', how='inner')
    return df

def main():
    df = load_data()
    ratings = load_ratings()
    df = intersect(df, ratings)
    df.to_csv('datasets/ratings_small_cleaned.csv', index=False)

if __name__ == '__main__':
    main()
