class algorithm_controller():

    def __init__(self, algorithm):
        self.algorithm = algorithm

    def generate_model(df): #all file input is in csv format, remember to save the model
        from surprise import SVD
        import numpy as np
        import pandas as pd

        from surprise import Dataset 
        from surprise import Reader
        from surprise.model_selection import train_test_split
        from surprise.model_selection import RandomizedSearchCV
        from surprise.dump import dump
        from surprise import accuracy
        # Define the reader
        reader = Reader()
        ratings = Dataset.load_from_df(df[['userId', 'movieId', 'rating']], reader)

        # Split data into training and testing sets
        train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state=42)

        # Initialize and fit the SVD model
        svd_model = SVD(random_state=42)
        svd_model.fit(train_ratings)

        # Perform hyperparameter tuning
        param_distributions = {
            'n_factors': list(range(50, 160, 10)),
            'reg_all': np.arange(0.02, 0.2, 0.02),
            'n_epochs': list(range(1, 51))
        }
        # ------------------------------------ LOG PERFORMANCE, MORE THAN 2 ARE NOT REQUIRED ------------------------------------
        rs = RandomizedSearchCV(SVD, param_distributions, measures=['rmse'], return_train_measures=True, cv=5, n_iter=1)
        rs.fit(ratings)

        # Get the best hyperparameters
        best_params = rs.best_params['rmse']

        # Initialize and fit the tuned SVD model
        tuned_svd_model = SVD(n_factors=best_params['n_factors'], reg_all=best_params['reg_all'], n_epochs=best_params['n_epochs'], random_state=42, verbose=False)
        tuned_svd_model.fit(train_ratings)

        # Evaluate the model on training and testing sets
        train_predictions = tuned_svd_model.test(train_ratings.build_testset())
        test_predictions = tuned_svd_model.test(test_ratings)

        # Biased SVD
        biased_svd_model = SVD(biased=True, random_state=42)
        biased_svd_model.fit(train_ratings)

        # Evaluate the model on training and testing sets
        train_predictions = biased_svd_model.test(train_ratings.build_testset())
        test_predictions = biased_svd_model.test(test_ratings)
        print("Biased SVD Model Train RMSE:", accuracy.rmse(train_predictions, verbose=False))
        print("Biased SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=False))

        return biased_svd_model
    
    def get_user_recommendations(user_id, df, loaded_model, top_N=30, to_json=False): #all file input is in csv format, user_id and top_N are integers
        import pandas as pd
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

        #top_recommendations is a list of tuples
        """
            [(318, 4.327501001837994), (4973, 4.289579757337473), (4226, 4.288369094103607), (2692, 4.2516131662793315), (923, 4.2428413377628305), (898, 4.239507008315757), (3683, 4.237841176249015), (1254, 4.237667303613109), (1283, 4.224226310670601), (58559, 4.215303761456968)]
        """
        #i need to serialize this list of tuples into a json file
        result = []
        for movie_id, estimated_rating in top_recommendations:
            result.append({'movieId': movie_id, 'estimated_rating': estimated_rating})
        
        return result
        
        if to_json:
            top_recommendations = pd.DataFrame(top_recommendations, columns=['movieId', 'estimated_rating'])
            top_recommendations.to_json(f'datasets/json_files/user_{user_id}_recommendations.json', orient='records')

        return top_recommendations
    
    def get_liked_movies(userId, df, movies):
        user_likes = df[(df['userId'] == userId) & (df['rating'] >= 4.0)]
        
        if user_likes.empty:
            print("No liked movies found for user {}.".format(userId))
            return []
        
        liked_movie_ids = user_likes['movieId'].astype(int).tolist()
        
        print("\n", "=" * 10, "Movies that user {} likes: ".format(userId), "=" * 10)
        
        liked_movies_titles = []
        
        for movie_id in liked_movie_ids:
            movie_id_str = str(movie_id)
            
            if movie_id_str in movies['id'].astype(str).values:
                movie_title = movies[movies['id'].astype(str) == movie_id_str]['original_title'].values[0]
                liked_movies_titles.append(movie_title)
        
        for title in liked_movies_titles:
            print(title)
        
        return liked_movie_ids

    def get_top_n_recommendations(userId, movies, ratings, n=10):
        from surprise.model_selection import train_test_split
        from surprise import BaselineOnly
        predict_ratings = {}
        train_ratings, test_ratings = train_test_split(ratings, test_size=.20, random_state = 42)
        baseline_model = BaselineOnly(verbose = False)
        baseline_model.fit(train_ratings)
        predictions = baseline_model.test(test_ratings)
        # loop for getting predictions for the user
        for uid, iid, true_r, est, _ in predictions:
            if (uid==userId):
                predict_ratings[iid] = est
        predict_ratings = sorted(predict_ratings.items(), key=lambda kv: kv[1],reverse=True)[:n]
        top_movies = [i[0] for i in predict_ratings]
        top_movies = [str(i) for i in top_movies]
        print("="*10,"Recommended movies for user {} :".format(userId),"="*10)
        print(movies[movies["id"].isin(top_movies)]["original_title"].to_string(index=False))
        return top_movies #the ID of the top movies for the user, sorted by possible match
    
