import pandas as pd
import numpy as np
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))

unnullable_cols = ['title', 'release_date', 'genres', 'overview']

def load_data():
    df = pd.read_csv('datasets/movies_metadata.csv', low_memory=False)
    return df

def null_remover(df):
    for col in unnullable_cols:
        df = df[df[col].notnull()]
    return df

def check_null(df):
    for col in df.columns:
        print(col, df[col].isnull().sum())


def main():
    df = load_data()
    df = null_remover(df)
    check_null(df)
    df.to_csv('datasets/movies_metadata_cleaned.csv', index=False)

if __name__ == '__main__':
    main()