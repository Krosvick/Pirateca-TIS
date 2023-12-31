class AlgorithmSVDpp():
    _instance = None
    ratings_df = None
    movies_df = None
    

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super(AlgorithmSVDpp, cls).__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        import sys
        import pandas as pd
        from surprise.dump import dump, load
        from surprise.model_selection import train_test_split
        from surprise import Dataset, Reader
        sys.path.append('src')
        import models.Algo_config as Algo_config

        # Load the dataframes only if they have not been loaded before
        if AlgorithmSVDpp.ratings_df is None:
            AlgorithmSVDpp.ratings_df = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)
        if AlgorithmSVDpp.movies_df is None:
            AlgorithmSVDpp.movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', usecols=['id', 'original_title', 'belongs_to_collection', 'genres'], memory_map=True)

        self.model = load('algoritmo2.pkl')[1]

    def generate_model(self, chunk_size=100000): #all file input is in csv format, remember to save the model
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
        df = [df[i:i + chunk_size] for i in range(0, AlgorithmSVDpp.ratings_df.shape[0], chunk_size)]

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
    
    def tune_model(self,  chunk_size=100000):
        """
        Tune the SVD model incrementally by fitting it on chunks of the ratings dataframe.
        """
        import pandas as pd
        from surprise import Dataset 
        from surprise import Reader
        from surprise.model_selection import train_test_split
        from surprise import accuracy
        from surprise.dump import dump
        import sys
        sys.path.append('src')
        import models.Algo_config as algo_config

        # load again ratings_df to get the new rows
        AlgorithmSVDpp.ratings_df = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)

        OFFSET = algo_config.OFFSET
        # Define the reader
        reader = Reader()
        #save a new offset var before changing the ratings_df len
        OFFSET1 = AlgorithmSVDpp.ratings_df.shape[0]
        # Split the dataframe into chunks starting from OFFSET
        AlgorithmSVDpp.ratings_df = AlgorithmSVDpp.ratings_df[OFFSET:]
        print(AlgorithmSVDpp.ratings_df.head())
        AlgorithmSVDpp.ratings_df = [AlgorithmSVDpp.ratings_df[i:i + chunk_size] for i in range(0, AlgorithmSVDpp.ratings_df.shape[0], chunk_size)]
        
        algo_config.OFFSET = OFFSET1
        with open('src/models/Algo_config.py', 'w') as f:
            f.write(f'OFFSET = {OFFSET1}')

        # Convert each chunk to a Dataset object
        AlgorithmSVDpp.ratings_df = [Dataset.load_from_df(chunk[['userId', 'movieId', 'rating']], reader) for chunk in AlgorithmSVDpp.ratings_df]

        for chunk in AlgorithmSVDpp.ratings_df:
            # Split data into training and testing sets
            train_ratings = chunk.build_full_trainset()

            # Fit the model incrementally on the current chunk
            self.model.fit(train_ratings)
            del chunk

        # Evaluate the model on the last chunk's test set
        # PREDICTIONS HANDLER SHOULD BE DONE AS SURPRISE DOCS SAYS
        test_ratings = train_ratings.build_testset()
        test_predictions = self.model.test(test_ratings)

        print("Tuned SVD Model Test RMSE:", accuracy.rmse(test_predictions, verbose=True))
        dump('algoritmo2.pkl', algo=self.model)

        return self.model
    
    def get_user_recommendations(self, user_id, top_n=10, include_rating=True):
        import time
        time1 = time.time()
        """
        Generate movie recommendations for a given user based on their ratings and a trained model.

        Args:
            user_id (int): The ID of the user for whom recommendations are generated. If set to 1 or None, random movie recommendations are returned.
            top_n (int, optional): The number of top recommendations to return. Defaults to 30.
            include_rating (bool, optional): Whether to include the rating in the recommendations. Defaults to True.

        Returns:
            list: A list of dictionaries containing movie IDs and ratings for the recommended movies.
        """
        from collections import defaultdict
        from surprise import accuracy
        from surprise import Dataset 
        from surprise import Reader

        # exception control for default user
        if (user_id == 1) or (user_id is None):
            # user 1 is the default user, return random movies in format [(int movieId, float estimated_rating), ...]
            import random
            recommendations = []
            for i in range(0, top_n):
                # get random id from AlgorithmSVDpp.movies_df['id']
                random_id = random.choice(AlgorithmSVDpp.movies_df['id'].values)
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
        user_seen_movies = AlgorithmSVDpp.ratings_df[AlgorithmSVDpp.ratings_df['userId'] == user_id]['movieId'].unique()
        # Get the list of movies the user hasn't seen
        unseen_movies = AlgorithmSVDpp.movies_df[~AlgorithmSVDpp.movies_df['id'].isin(user_seen_movies)]
        # Placeholder for the true rating
        true_rating = 0.0
        # Prepare the testset
        unseen_movies_testset = [(user_id, movie_id, true_rating) for movie_id in unseen_movies['id']]

        # Make predictions only on the unseen movies
        predictions = self.model.test(unseen_movies_testset)
        print(accuracy.rmse(predictions))

        recommendations = defaultdict(list)
        for uid, iid, true_r, est, _ in predictions:
            recommendations[uid].append((iid, est))
        # Then sort the predictions for each user and retrieve the k highest ones.
        for uid, user_ratings in recommendations.items():
            user_ratings.sort(key=lambda x: x[1], reverse=True)
            recommendations[uid] = user_ratings[:top_n]

        result = []
        for movie_id, rating in recommendations[user_id]:
            if include_rating:
                result.append({'movie_id': movie_id, 'rating': rating})
            else:
                result.append({'movie_id': movie_id})
        print('Real time for get_user_recommendations: ' + str(time.time() - time1) + ' seconds.')
        return result
    
    def get_all_predictions(self):
        """
        Get predictions for all users and all movies.
        """
        from collections import defaultdict
        from surprise import Dataset, Reader
        from surprise.dump import dump

        reader = Reader()
        data = Dataset.load_from_df(AlgorithmSVDpp.ratings_df[['userId', 'movieId', 'rating']], reader)
        trainset = data.build_full_trainset()
        predictions = self.model.test(trainset.build_testset())
        dump('test_algorithm.pkl', algo=self.model, predictions=predictions)