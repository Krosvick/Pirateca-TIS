import pandas as pd
import sys
import time
sys.path.append('src')
from models.Algorithm import Algorithm

#read the processed_ratings.csv file
time1 = time.time()
ratings_df = pd.read_csv('datasets/processed_ratings.csv' , memory_map=True)
time2 = time.time()
print('Time to read file: ', time2-time1, ' seconds')
#drop the timestamp column
ratings_df = ratings_df.drop(columns=['timestamp'])

#generate model
time1 = time.time()
model = Algorithm.generate_model(ratings_df)
time2 = time.time()
print('Time to generate model: ', time2-time1, ' seconds')
