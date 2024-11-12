import mysql.connector
import requests
from mysql.connector import Error

# Token de tu bot de Telegram (lo obtuviste de BotFather)
TELEGRAM_TOKEN = 'TU_BOT_TOKEN'
# chat_id donde quieres enviar el mensaje (tu chat_id o el del grupo)
CHAT_ID = 'TU_CHAT_ID'

def enviar_mensaje_telegram(mensaje):
    """Envía un mensaje al canal o chat de Telegram."""
    url = f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendMessage"
    payload = {
        'chat_id': CHAT_ID,
        'text': mensaje
    }
    response = requests.post(url, data=payload)
    if response.status_code == 200:
        print("Mensaje enviado exitosamente a Telegram.")
    else:
        print("Error al enviar el mensaje a Telegram.")

def conectar_bd():
    """Establece la conexión a la base de datos MySQL."""
    try:
        conexion = mysql.connector.connect(
            host="#",
            port=3306,
            user="#",
            password="#",
            database="#"
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

        if tareas:
            mensaje = "Tareas de la semana:\n"
            for tarea in tareas:
                mensaje += (f"ID: {tarea['id']}, Título: {tarea['titulo']}, "
                            f"Descripción: {tarea['descripcion']}, Fecha: {tarea['fecha']}, "
                            f"Materia: {tarea['materia_nombre']}\n")
            enviar_mensaje_telegram(mensaje)
        else:
            enviar_mensaje_telegram("No se encontraron tareas para esta semana.")

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
