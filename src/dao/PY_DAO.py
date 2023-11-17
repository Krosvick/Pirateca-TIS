"""
Due to php vs python sincronization problems, this file will contain a specific DAO for the algorithm usage
this way, the algorithm can function on it's complete own and the user should not be able to influence it if any bypass occurs

This file will be called by the algorithm and will be the only one to access the database for the algorithm
"""

import mysql.connector
import os
import platform

class Database:
    _instance = None
    _connection = None
    statement = None

    def __init__(self):
        if platform.system() == 'Windows':
            ssl_ca = 'path_to_cacert.pem'
        else:
            ssl_ca = '/etc/ssl/certs/ca-certificates.crt'  # default path in Linux

        self._connection = mysql.connector.connect(
            host=os.getenv("DB_HOST"),
            user=os.getenv("DB_USERNAME"),
            password=os.getenv("DB_PASSWORD"),
            database=os.getenv("DB_NAME"),
            ssl_ca=ssl_ca
        )

    @classmethod
    def get_instance(cls):
        if cls._instance is None:
            cls._instance = cls()
        return cls._instance

print("DAO initialized")