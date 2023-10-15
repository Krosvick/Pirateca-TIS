
#file that will load movies_metadata_cleaned.csv and ratings_small.csv and will look for the ids in ratings that are not in movies_metadata_cleaned.csv
#then it will remove those rows from ratings_small.csv and save it as ratings_small_cleaned.csv

import pandas as pd
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
    #this will remove from ratings the rows that have movieId that are not in df, and then return ratings
    #also save a json with the dropped rows to know which rows were dropped
    dropped_rows = ratings[~ratings['movieId'].isin(df['id'])]
    ratings = ratings[ratings['movieId'].isin(df['id'])]
    dropped_rows.to_json('datasets/json_files/dropped_rows_ratings.json', orient='records')
    return ratings

def main():
    df = load_data()
    ratings = load_ratings()
    ratings = intersect(df, ratings)
    ratings.to_csv('datasets/ratings_small_cleaned.csv', index=False)

if __name__ == '__main__':
    main()
