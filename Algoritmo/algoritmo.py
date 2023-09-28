
import numpy as np 
import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

from surprise import Dataset 
from surprise import Reader
from surprise.model_selection import train_test_split
from surprise import accuracy
from surprise.model_selection import GridSearchCV,RandomizedSearchCV
from sklearn.metrics import confusion_matrix, precision_score, recall_score,classification_report

#el path a los datos es relativo mientras no podamos subir la bdd
df = pd.read_csv("")

df.head()
