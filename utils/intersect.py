import pandas as pd
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))

def load_data():
    df = pd.read_csv('datasets/movies_metadata_cleaned.csv', low_memory=False)
    return df

def load_ratings():
    df = pd.read_csv('datasets/ratings.csv', low_memory=False)
    return df

def load_ratings_small_cleaned():
    df = pd.read_csv('datasets/ratings_small_cleaned.csv', low_memory=False)
    return df

def intersect(movie_df, ratings, ratings_small_cleaned):
    #delete ratings that are not in movie df
    ratings1 = ratings[ratings['movieId'].isin(movie_df['id'])]
    #delete ratings already in ratings_small_cleaned comparing user id
    ratings1 = ratings1[~ratings['userId'].isin(ratings_small_cleaned['userId'])]
    #delete ratings row duplicates
    ratings1 = ratings1.drop_duplicates()
    print(ratings1)
    return ratings1

def main():
    df = load_data()
    ratings = load_ratings()
    ratings_small_cleaned = load_ratings_small_cleaned()
    ratings = intersect(df, ratings, ratings_small_cleaned)
    ratings.to_csv('datasets/ratings_big_cleaned.csv', index=False)

if __name__ == '__main__':
    main()
