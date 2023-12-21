class Algorithm():
    import models.Algo_config as Algo_config

    def __init__(self, algorithm):
        self.algorithm = algorithm

    def generate_model(df, chunk_size=100000): #all file input is in csv format, remember to save the model
        from numpy import arange
        from surprise import SVDpp
        from surprise import Dataset 
        from surprise import Reader
        from surprise.model_selection import train_test_split
        from surprise.model_selection import RandomizedSearchCV
        from surprise.dump import dump
        from surprise import accuracy

        # Define the reader
        reader = Reader()

        # Split the dataframe into chunks
        df = [df[i:i + chunk_size] for i in range(0, df.shape[0], chunk_size)]

        # Convert each chunk to a Dataset object
        df = [Dataset.load_from_df(chunk[['userId', 'movieId', 'rating']], reader) for chunk in df]

        param_distributions = {
            'n_factors': list(range(20, 81, 10)), 
            'reg_all': arange(0.005, 0.0151, 0.001),
            'n_epochs': list(range(1, 31)),
        }
        rs = RandomizedSearchCV(SVDpp, param_distributions, measures=['rmse'], return_train_measures=True, cv=7, n_iter=6, n_jobs=-1, pre_dispatch='2*n_jobs', joblib_verbose=1)
        del param_distributions

        for dataset in df:
            rs.fit(dataset)

        # Get the best parameters from our random search
        best_params = rs.best_params['rmse']
        del rs
        # Initialize and fit the tuned SVD model
        tuned_svd_model = SVDpp(n_factors=best_params['n_factors'], reg_all=best_params['reg_all'], n_epochs=best_params['n_epochs'], random_state=42, verbose=True)

        for chunk in df:
            # Split data into training and testing sets
            train_ratings, test_ratings = train_test_split(chunk, test_size=0.20, random_state=42)

            # Fit the model incrementally on the current chunk
            tuned_svd_model.fit(train_ratings)
            del chunk


        # Evaluate the model on the last chunk's test set
        test_predictions = tuned_svd_model.test(test_ratings)

        model_file = 'svd_model_biased_big_test.pkl' #the path and name of file wich will be saved
        dump(model_file, algo=tuned_svd_model) 

        print("Tuned SVD Model Test MAE:", accuracy.mae(test_predictions, verbose=False))
        print("Tuned SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=False))
        print("Tuned SVD Model Test MSE:", accuracy.mse(test_predictions, verbose=False))
        print("Tuned SVD Model Test FCP:", accuracy.fcp(test_predictions, verbose=False))

        return tuned_svd_model
    
    def tune_model(df, tuned_svd_model, OFFSET = Algo_config.OFFSET,  chunk_size=100000):
        from surprise import Dataset 
        from surprise import Reader
        from surprise.model_selection import train_test_split
        from surprise import accuracy
        from surprise.dump import dump

        # Define the reader
        reader = Reader()

        # Split the dataframe into chunks based on the OFFSET
        df = df[OFFSET:]
        df = [df[i:i + chunk_size] for i in range(0, df.shape[0], chunk_size)]
        #change the OFFSET value in the config file
        Algorithm.Algo_config.OFFSET += (len(df) - Algorithm.Algo_config.OFFSET)

        # Convert each chunk to a Dataset object
        df = [Dataset.load_from_df(chunk[['userId', 'movieId', 'rating']], reader) for chunk in df]

        for chunk in df:
            # Split data into training and testing sets
            train_ratings, test_ratings = train_test_split(chunk, test_size=0.25, random_state=42)

            # Fit the model incrementally on the current chunk
            tuned_svd_model.fit(train_ratings)
            del chunk

        # Evaluate the model on the last chunk's test set
        test_predictions = tuned_svd_model.test(test_ratings)
        print("Tuned SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=True))
        dump('svd_model_biased_big_test_tune.pkl', algo=tuned_svd_model)

        return tuned_svd_model

    
    def get_user_recommendations(user_id, df, loaded_model, top_N=30, include_rating=True): #all file input is in csv format, user_id and top_N are integers
        movie_ids = df['movieId'].unique()

        # Create a list of movie IDs that the user has not rated
        user_rated_movies = df[df['userId'] == user_id]['movieId']
        movies_to_recommend = [movie_id for movie_id in movie_ids if movie_id not in user_rated_movies]
        print(movies_to_recommend)
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
        for movie_id, rating in top_recommendations:
            if include_rating:
                result.append({'movie_id': movie_id, 'rating': rating})
            else:
                result.append({'movie_id': movie_id})
        return result
    
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
    
