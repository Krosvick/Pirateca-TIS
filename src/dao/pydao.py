"""
Due to php vs python sincronization problems, this file will contain a specific DAO for the algorithm usage
this way, the algorithm can function on it's complete own and the user should not be able to influence it if any bypass occurs
This file will be called by the algorithm and will be the only one to access the database for the algorithm
"""

import os
from dotenv import load_dotenv
import MySQLdb

load_dotenv()


class Database:
    _instance = None
    _connection = None
    statement = None

    def __init__(self):
        # Create the connection object
        self.connection = MySQLdb.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USERNAME"),
            passwd=os.getenv("DB_PASSWORD"),
            db=os.getenv("DB_NAME"),
            ssl_mode="VERIFY_IDENTITY",
            ssl={
                'ca': os.getenv("SSL_CERT")
            }
        )

    @classmethod
    def get_instance(cls):
        if cls._instance is None:
            cls._instance = cls()
        return cls._instance
    
    def get_connection(self):
        return self.connection


class DAO():
    _connection = None
    _table = "ratings" #hardcoded for now can be changed later adding a table parameter to the constructor

    def __init__(self):
        try:
            self._connection = Database.get_instance()
        except Exception as e:
            print(e)

    def get_all(self):
        try:
            cursor = self._connection.get_connection().cursor()
            cursor.execute(f"SELECT * FROM {self._table}")
            rows = cursor.fetchall()
            return rows
        except Exception as e:
            print(e)

#test = DAO()
#print(test.get_all())