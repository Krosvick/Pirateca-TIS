#this file will load ..datasets/movies_metadata.csv and find all the rows with null values and print them
import pandas as pd
import numpy as np
import os
import sys
sys.path.append(os.path.abspath(os.path.join('..')))

def load_data():
    df = pd.read_csv('datasets/movies_metadata.csv')
    print(df)
    return df

loadd_data()
