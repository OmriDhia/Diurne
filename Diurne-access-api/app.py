from flask import Flask, jsonify
import pandas as pd
import pyodbc

app = Flask(__name__)

def read_mdb_file(file_path, query):
    """
    Lire les données depuis un fichier .mdb et retourne un DataFrame.
    Note: Cette fonction suppose que vous avez configuré une source de données ODBC
    pour lire les fichiers .mdb.
    """
    try:
        conn_str = (
            r'DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};'
            f'DBQ={file_path};'
        )
        conn = pyodbc.connect(conn_str)
        return pd.read_sql_query(query, conn)
    except Exception as e:
        print(f"Erreur lors de la lecture du fichier .mdb: {e}")
        return pd.DataFrame()

@app.route('/data', methods=['GET'])
def get_data():
    file_path = 'db_access/db.mdb'
    query = "SELECT * FROM T_CLIENT"
    data = read_mdb_file(file_path, query)
    return jsonify(data.to_dict(orient='records'))

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8585)
