import pandas as pd
import sys
from surprise.dump import load

sys.path.append('src')
from models.Algorithm import Algorithm

ratings_df = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)
movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', memory_map=True)

#print all the ratings of the user 229
print(ratings_df[ratings_df['userId'] == 229])

model = load('algoritmo2.pkl')[1]
predictions = load('algoritmo2.pkl')[0]

print(Algorithm.get_user_recommendations(229, ratings_df, movies_df, model, predictions))
