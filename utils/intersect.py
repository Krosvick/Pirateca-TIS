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
