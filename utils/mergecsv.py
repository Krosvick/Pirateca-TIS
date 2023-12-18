import pandas as pd
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))


def load_ratings_big_cleaned():
    df = pd.read_csv('datasets/ratings_big_cleaned.csv', low_memory=False)
    return df

def load_ratings_small_cleaned():
    df = pd.read_csv('datasets/ratings_small_cleaned.csv', low_memory=False)
    return df


def main():
    ratings_big_cleaned = load_ratings_big_cleaned()
    ratings_small_cleaned = load_ratings_small_cleaned()
    #add ratings_big_cleaned to ratings_small_cleaned
    ratings = pd.concat([ratings_small_cleaned, ratings_big_cleaned])
    ratings.to_csv('datasets/processed_ratings.csv', index=False)

if __name__ == '__main__':
    main()