class AlgorithmContent():
    _instance = None

    def __new__(cls, *args, **kwargs):
        if not cls._instance:
            cls._instance = super(AlgorithmContent, cls).__new__(cls, *args, **kwargs)
        return cls._instance

    def __init__(self):
        import pandas as pd
        # hardcoded paths for better loading performance (memory_map)
        self.ratings_df = pd.read_csv('datasets/reprocessed_ratings.csv', memory_map=True)
        self.movies_df = pd.read_csv('datasets/movies_metadata_cleaned.csv', usecols=['id', 'original_title', 'belongs_to_collection', 'genres'], memory_map=True)
        

    def generate_model(self, chunk_size=100000): #all file input is in csv format, remember to save the model
        return self.model
    
    def tune_model(self,  chunk_size=100000):
        return self.model
    
    def get_user_recommendations(self, user_id, top_n=10, include_rating=True):
        return self.model
    
    def get_all_predictions(self):
        return self.model