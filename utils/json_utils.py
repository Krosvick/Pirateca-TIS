#function that takes a json file and returns the count of objects in it
def json_count(file):
    import json
    with open(file) as f:
        data = json.load(f)
    return len(data)

#function that takes a json file with ids that relate to another json file and returns the count of objects in the second file that have the ids in the first file

def top_5(main_file, relative_file, id_main, id_relative, wanted_cols=None):
    import pandas as pd
    import json
    df = pd.read_json(main_file)
    df2 = pd.read_json(relative_file)
    df = df[id_main].value_counts().sort_values(ascending=False)
    #now go to df2 and remove from df the ids that are not in df2
    #first check that the id_relative is a int or a string
    #if not then convert it to a int, if the string cant be converted to a int then remove that row and try again
    if df2[id_relative].dtype != 'int64':
        try:
            #print type of id_main
            df2[id_relative] = df2[id_relative].astype('int64')
        except:
            df2 = df2[df2[id_relative].apply(lambda x: x.isnumeric())]
            df2[id_relative] = df2[id_relative].astype('int64')
    df = df[df.index.isin(df2[id_relative])]
    df = df.head(5)
    df = df.reset_index()
    df.columns = [id_main, 'count']    
    df = df.merge(df2, left_on=id_main, right_on=id_relative, how='left')
    df = df.drop(id_relative, axis=1)
    if wanted_cols != None:
        df = df[wanted_cols]
    return df


def save_df_to_json(df, file_name):
    import json
    df.to_json(file_name, orient='records') 
    with open(file_name) as f:
        data = json.load(f)
    with open(file_name, 'w') as f:
        json.dump(data, f, indent=4)

#Function that receives a json file path
#the json file is badly foramtted and has the entire content in one line
#this function will fix that
def json_format(file):
    import json
    with open(file) as f:
        data = json.load(f)
    with open(file, 'w') as f:
        json.dump(data, f, indent=4)
        


json_format('json_files/error_rows.json')


