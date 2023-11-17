"""
Due to php vs python sincronization problems, this file will contain a specific DAO for the algorithm usage
this way, the algorithm can function on it's complete own and the user should not be able to influence it if any bypass occurs

This file will be called by the algorithm and will be the only one to access the database for the algorithm
"""

import mysql.connector
import os

class Database:
    _instance = None
    _connection = None
    statement = None

    def __init__(self):

        self._connection = mysql.connector.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USERNAME"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
        )

    @classmethod
    def get_instance(cls):
        if cls._instance is None:
            cls._instance = cls()
        return cls._instance


class DAO():
    _connection = None
    _table = None
    _relations = []

    def __init__(self):
        try:
            self._connection = Database.get_instance()
        except Exception as e:
            print(e)

    def get_all(self):
        try:
            cursor = self._connection.cursor()
            cursor.execute(f"SELECT * FROM {self._table}")
            rows = cursor.fetchall()
            return rows
        except Exception as e:
            print(e)