import pandas as pd
import numpy as np
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))

unnullable_cols = ['title', 'release_date', 'genres']

def load_data():
    df = pd.read_csv('datasets/movies_metadata.csv', low_memory=False)
    return df

def null_remover(df):
    #use dropna to remove rows with null values in the columns specified in unnullable_cols
    #also save a json with the dropped rows
    #return the df with the dropped rows
    dropped_rows = df[df[unnullable_cols].isnull().any(axis=1)]
    df = df.dropna(subset=unnullable_cols)
    dropped_rows.to_json('datasets/json_files/dropped_rows_movies.json', orient='records')
    return df

def check_null(df):
    for col in unnullable_cols:
        if df[col].isnull().sum() > 0:
            print(col, df[col].isnull().sum())


def main():
    df = load_data()
    df = null_remover(df)
    check_null(df)
    df.to_csv('datasets/movies_metadata_cleaned.csv', index=False)

if __name__ == '__main__':
    main()