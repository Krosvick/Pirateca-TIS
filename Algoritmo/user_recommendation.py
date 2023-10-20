from surprise import dump
import pandas as pd
#import time

#start_time = time.time()

# Load data
model_file = 'svd_model_biased.pkl'
loaded_model = dump.load(model_file)[1]
df = pd.read_csv("/home/kiwi/Escritorio/Movies/ratings_small.csv")

#half_time = time.time()

def Get_User_Recommendations(user_id, df, loaded_model, top_N=30, to_json=False): #all file input is in csv format, user_id and top_N are integers
    movie_ids = df['movieId'].unique()

    # Create a list of movie IDs that the user has not rated
    user_rated_movies = df[df['userId'] == user_id]['movieId']
    movies_to_recommend = [movie_id for movie_id in movie_ids if movie_id not in user_rated_movies]
    recommendations = []

    for movie_id in movies_to_recommend:
        estimated_rating = loaded_model.predict(user_id, movie_id).est
        recommendations.append((movie_id, estimated_rating))

    recommendations.sort(key=lambda x: x[1], reverse=True)

    top_recommendations = recommendations[:top_N]

    for i, (movie_id, estimated_rating) in enumerate(top_recommendations, 1):
        print(f"Recommendation {i}: Movie ID {movie_id}, Estimated Rating: {estimated_rating}")
    
    
    if to_json:
        top_recommendations = pd.DataFrame(top_recommendations, columns=['movieId', 'estimated_rating'])
        top_recommendations.to_json(f'datasets/json_files/user_{user_id}_recommendations.json', orient='records')

    return top_recommendations

user = int(input("Enter the id of the user you want to recommend movies to: "))
print(Get_User_Recommendations(user, df, loaded_model))

#end_time = time.time()
#print("Time: ", end_time - start_time)
#print("Time to load: ", half_time - start_time)