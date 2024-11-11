import mysql.connector
from mysql.connector import Error

def conectar_bd():
    """Establece la conexión a la base de datos MySQL."""
    try:
        conexion = mysql.connector.connect(
            host="172.26.0.2",
            port=3306,
            user="root",
            password="root",
            database="HistorialPartidos"
        )
        if conexion.is_connected():
            print("Conexión exitosa a la base de datos")
            return conexion
    except Error as e:
        print(f"Error al conectar a la base de datos: {e}")
        return None

def cerrar_conexion(conexion):
    """Cierra la conexión a la base de datos."""
    if conexion and conexion.is_connected():
        conexion.close()
        print("Conexión cerrada")

def obtener_tareas_semana(conexion):
    """Obtiene y muestra las tareas de la semana actual con el nombre de la materia."""
    consulta = """
        SELECT t.id, t.titulo, t.descripcion, t.fecha, m.nombre AS materia_nombre
        FROM tarea t
        JOIN materia m ON t.materia_id = m.id
        WHERE 
            t.fecha >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
            AND t.fecha < DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY);
    """
    try:
        cursor = conexion.cursor(dictionary=True)
        cursor.execute(consulta)
        tareas = cursor.fetchall()
        
        # Imprimir resultados de manera más estructurada
        if tareas:
            print("Tareas de la semana:")
            for tarea in tareas:
                print(f"Título: {tarea['titulo']}, "
                      f"Descripción: {tarea['descripcion']}, Fecha: {tarea['fecha']}, "
                      f"Materia: {tarea['materia_nombre']}")
        else:
            print("No se encontraron tareas para esta semana.")

    except Error as e:
        print(f"Error al obtener las tareas de la semana: {e}")
    finally:
        cursor.close()

# Ejecución principal del script
if __name__ == "__main__":
    conexion = conectar_bd()
    if conexion:
        obtener_tareas_semana(conexion)
        cerrar_conexion(conexion)
