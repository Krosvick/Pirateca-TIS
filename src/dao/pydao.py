"""
Due to php vs python sincronization problems, this file will contain a specific DAO for the algorithm usage
this way, the algorithm can function on it's complete own and the user should not be able to influence it if any bypass occurs
This file will be called by the algorithm and will be the only one to access the database for the algorithm
"""

import os
from dotenv import load_dotenv
import pymysql
import time
import sys
sys.path.append('src')
import dao.py_config as py_config

load_dotenv()


class Database:
    _instance = None
    _connection = None
    statement = None

    def __init__(self):
        # Create the connection object
        self.connection = pymysql.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USERNAME"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
            ssl_ca=os.getenv("SSL_CERT"),
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

    def get_all_new(self):
        try:
            limit = 100000
            offset = py_config.OFFSET
            all_rows = []
            time3 = time.time()
            while True:
                cursor = self._connection.get_connection().cursor()
                cursor.execute(f"SELECT * FROM {self._table} WHERE id > {offset} ORDER BY id LIMIT {limit}")
                rows = cursor.fetchall()

                if not rows:
                    break

                all_rows.extend(rows)
                #dinamically change the offset using the lenght of the rows getted
                offset += len(rows)
            
            # Write the new offset back to the config.py file
            #HARDCODED FILE PATH
            with open('src/dao/py_config.py', 'w') as f:
                f.write(f'OFFSET = {offset}')
            print('New dao: ' + str(offset))

            time4 = time.time()
            print('Data fetched in ' + str(time4 - time3) + ' seconds.')
            return all_rows
        
        except Exception as e:
            print(e)

    def get_head(self, num_rows):
        try:
            cursor = self._connection.get_connection().cursor()
            cursor.execute(f"SELECT * FROM {self._table} LIMIT {num_rows}")
            rows = cursor.fetchall()
            return rows
        except Exception as e:
            print(e)
