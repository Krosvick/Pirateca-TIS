class Algorithm():
    import sys
    sys.path.append('src')
    import models.Algo_config as Algo_config

    def __init__(self, algorithm):
        """
        Initializes an instance of the Algorithm class with the specified algorithm.

        Args:
            algorithm (str): The name of the algorithm.

        Returns:
            None
        """
        self.algorithm = algorithm

    def generate_model(df, chunk_size=100000): #all file input is in csv format, remember to save the model
        """
        Input a ratings dataframe, should contain userId (int), movieId (int) and rating (float) .
        Returns and save a SVDpp model with the best hiperparameters.

        Makes the SVDpp model from 0 with pagination.
        Predictions should be made with get_all_predictions() method due to pagination incombatibility.
        """
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
    
    def tune_model(ratings_df, tuned_svd_model, OFFSET = Algo_config.OFFSET,  chunk_size=100000):
        """
        Tune the SVD model incrementally by fitting it on chunks of the ratings dataframe.
        """
        from surprise import Dataset 
        from surprise import Reader
        from surprise.model_selection import train_test_split
        from surprise import accuracy
        from surprise.dump import dump

        # Define the reader
        reader = Reader()
        #save a new offset var before changing the ratings_df len
        OFFSET1 = OFFSET + ratings_df.shape[0]
        # Split the dataframe into chunks based on the OFFSET
        ratings_df = ratings_df[OFFSET:]
        ratings_df = [ratings_df[i:i + chunk_size] for i in range(0, ratings_df.shape[0], chunk_size)]
        #change the OFFSET value in the config file



        with open('src/models/Algo_config.py', 'w') as f:
                f.write(f'OFFSET = {OFFSET1}')
        print('New algorithm offset: ' + str(OFFSET1))

        # Convert each chunk to a Dataset object
        ratings_df = [Dataset.load_from_df(chunk[['userId', 'movieId', 'rating']], reader) for chunk in ratings_df]

        for chunk in ratings_df:
            # Split data into training and testing sets
            train_ratings, test_ratings = train_test_split(chunk, test_size=0.5, random_state=42)

            # Fit the model incrementally on the current chunk
            tuned_svd_model.fit(train_ratings)
            del chunk

        # Evaluate the model on the last chunk's test set
        test_predictions = tuned_svd_model.test(test_ratings)

        print("Tuned SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=True))
        dump('test_algorithm.pkl', algo=tuned_svd_model)

        return tuned_svd_model
    
    def get_user_recommendations(user_id, ratings_df, movies_df, model, predictions, top_n=10, include_rating=True):
        """
        Generate movie recommendations for a given user based on their ratings and a trained model.

        Args:
            user_id (int): The ID of the user for whom recommendations are generated. If set to 1 or None, random movie recommendations are returned.
            ratings_df (DataFrame): The dataframe containing user ratings data.
            movies_df (DataFrame): The dataframe containing movie data.
            model (object): The trained recommendation model.
            predictions (object): The predictions object generated by the model.
            top_n (int, optional): The number of top recommendations to return. Defaults to 30.
            include_rating (bool, optional): Whether to include the rating in the recommendations. Defaults to True.

        Returns:
            list: A list of dictionaries containing movie IDs and ratings for the recommended movies.
        """
        from collections import defaultdict

        # exception control for default user
        if (user_id == 1) or (user_id is None):
            # user 1 is the default user, return random movies in format [(int movieId, float estimated_rating), ...]
            import random
            recommendations = []
            for i in range(0, top_n):
                # get random id from movies_df['id']
                random_id = random.choice(movies_df['id'].values)
                # get random rating from 0 to 5
                random_rating = random.uniform(0, 5)
                recommendations.append((random_id, random_rating))

            result = []
            for movie_id, rating in recommendations:
                if include_rating:
                    result.append({'movie_id': movie_id, 'rating': rating})
                else:
                    result.append({'movie_id': movie_id})

            return result

        # Get the list of movies the user has seen
        user_seen_movies = ratings_df[ratings_df['userId'] == user_id]['movieId'].unique()
        # Get the list of movies the user hasn't seen
        unseen_movies = movies_df[~movies_df['id'].isin(user_seen_movies)]
        # Placeholder for the true rating
        true_rating = 0.0
        # Prepare the testset
        unseen_movies_testset = [(user_id, movie_id, true_rating) for movie_id in unseen_movies['id']]

        # Make predictions only on the unseen movies
        predictions = model.test(unseen_movies_testset)

        recommendations = defaultdict(list)
        for uid, iid, true_r, est, _ in predictions:
            recommendations[uid].append((iid, est))
        # Then sort the predictions for each user and retrieve the k highest ones.
        for uid, user_ratings in recommendations.items():
            user_ratings.sort(key=lambda x: x[1], reverse=True)
            recommendations[uid] = user_ratings

        """
        top_recommendations is a list of tuples
            [(318, 4.327501001837994), (4973, 4.289579757337473), (4226, 4.288369094103607), (2692, 4.2516131662793315), (923, 4.2428413377628305), (898, 4.239507008315757), (3683, 4.237841176249015), (1254, 4.237667303613109), (1283, 4.224226310670601), (58559, 4.215303761456968)]
        i need to serialize this list of tuples into a json file
        """
        result = []
        for movie_id, rating in recommendations[user_id]:
            if include_rating:
                result.append({'movie_id': movie_id, 'rating': rating})
            else:
                result.append({'movie_id': movie_id})

        return result
    
    def get_liked_movies(userId, df, movies):
        """
        Retrieve a list of movie IDs that a specific user has liked based on their ratings.
        Also prints the titles of the liked movies.

        Args:
            userId (int): The ID of the user for whom the liked movies are retrieved.
            df (DataFrame): The dataframe containing user ratings data.
            movies (DataFrame): The dataframe containing movie data.

        Returns:
            list: A list of movie IDs that the user has liked.
        """
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
    
    def get_all_predictions(ratings_df, model):
        """
        Get predictions for all users and all movies.
        """
        from collections import defaultdict
        from surprise import Dataset, Reader
        from surprise.dump import dump

        reader = Reader()
        data = Dataset.load_from_df(ratings_df[['userId', 'movieId', 'rating']], reader)
        trainset = data.build_full_trainset()
        predictions = model.test(trainset.build_testset())
        dump('test_algorithm.pkl', algo=model, predictions=predictions)