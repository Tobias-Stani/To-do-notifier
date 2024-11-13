import mysql.connector
import requests
from mysql.connector import Error
from datetime import datetime

# Token de tu bot de Telegram (lo obtuviste de BotFather)
TELEGRAM_TOKEN = ''
# chat_id donde quieres enviar el mensaje (tu chat_id o el del grupo)
CHAT_ID = ''

def enviar_mensaje_telegram(mensaje):
    """Envía un mensaje al canal o chat de Telegram."""
    url = f"https://api.telegram.org/bot{TELEGRAM_TOKEN}/sendMessage"
    payload = {
        'chat_id': CHAT_ID,
        'text': mensaje,
        'parse_mode': 'HTML'  # Permitir formato HTML en el mensaje
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
            host="",
            port=3,
            user="",
            password="",
            database=""
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

def formatear_fecha(fecha):
    """Convierte la fecha a un formato más amigable."""
    dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
    fecha_dt = datetime.strptime(str(fecha), '%Y-%m-%d')
    dia_semana = dias[fecha_dt.weekday()]
    return f"{dia_semana}, {fecha_dt.strftime('%d/%m/%Y')}"

def obtener_tareas_semana(conexion):
    """Obtiene y muestra las tareas de la semana actual con formato mejorado."""
    consulta = """
        SELECT t.id, t.titulo, t.descripcion, t.fecha, m.nombre AS materia_nombre
        FROM tarea t
        JOIN materia m ON t.materia_id = m.id
        WHERE 
            t.fecha >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
            AND t.fecha < DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY)
        ORDER BY t.fecha, m.nombre;
    """
    try:
        cursor = conexion.cursor(dictionary=True)
        cursor.execute(consulta)
        tareas = cursor.fetchall()

        if tareas:
            mensaje = "🎓 <b>TAREAS DE LA SEMANA</b> 📚\n\n"
            fecha_actual = None

            for tarea in tareas:
                # Agregar separador de fecha si cambia
                if fecha_actual != tarea['fecha']:
                    fecha_actual = tarea['fecha']
                    mensaje += f"\n📅 <b>{formatear_fecha(fecha_actual)}</b>\n"
                
                # Agregar detalles de la tarea con emojis y formato
                mensaje += (
                    f"\n📓Materia: <b>{tarea['materia_nombre']}</b>\n"
                    f"📌Tarea: <b>{tarea['titulo']}</b>\n"
                    f"📝Descripcion: {tarea['descripcion']}\n"
                    f"━━━━━━━━━━━━━━━\n"
                )

            # Agregar pie de mensaje
            mensaje += "\n💪 ¡Ánimo con las tareas! 🚀"
            
            enviar_mensaje_telegram(mensaje)
        else:
            mensaje = "🎉 <b>¡Buenas noticias!</b> 🎊\n\n"
            mensaje += "No hay tareas programadas para esta semana. ¡Aprovecha para descansar! 😊"
            enviar_mensaje_telegram(mensaje)

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