<!DOCTYPE html>
   <html lang="es">

   <head>
       <link rel="stylesheet" href="../../src/css/estilo.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
           integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
           crossorigin="anonymous" referrerpolicy="no-referrer" />
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
           integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
           integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
           crossorigin="anonymous"></script>
       <title>TrueChange</title>
   </head>
<?php 
require 'db_config.php';


$id_comprador = CURRENT_USER_ID;
$id_vendedor = $_SESSION['vendedor_chat_id'];
$sql = "UPDATE messages SET is_read = 1 
        WHERE receiver_id = ? AND sender_id = ? AND is_read = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_comprador, $id_vendedor]);

// --- NUEVA CONSULTA: Obtener el nombre del partner ---
$stmtNombre = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmtNombre->execute([$id_vendedor]);
$usuarioPartner = $stmtNombre->fetch();
$nombrePartner = $usuarioPartner ? $usuarioPartner['nombre'] : "Chat";

// Marcar como leído
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Chat Básico</title>
    <style>
      .chat-header {
    border-radius: 8px 8px 0 0;
    z-index: 10;
}
.chat-header h5 {
    color: #333;
    margin: 0;
}
.fa-circle {
    font-size: 0.6rem;
    vertical-align: middle;
}
#chat-box {
    height: 400px; /* Ajusta según necesites */
    background: #fdfdfd;
}
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .chat-container { 
            max-width: 600px; margin: 50px auto; background: #fff; 
            border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        #chat-box { 
            height: 400px; overflow-y: auto; padding: 15px; border-bottom: 1px solid #eee; 
            display: flex; flex-direction: column;
        }
        .chat-message { 
            padding: 8px 12px; margin-bottom: 8px; border-radius: 18px; 
            max-width: 80%; line-height: 1.4; word-wrap: break-word; 
            position: relative;
        }
        .message-sent { 
            background-color: #007bff; color: white; align-self: flex-end; 
            border-bottom-right-radius: 4px;
        }
        .message-received { 
            background-color: #494c4aff; color: #f6f1f1ff; align-self: flex-start; 
            border-bottom-left-radius: 4px;
        }
        .chat-message .time { 
            font-size: 0.7em; margin-left: 10px; opacity: 0.7; display: block;
            text-align: right; margin-top: 2px;
        }
        #message-form { display: flex; padding: 15px; }
        #message-input { 
            flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
            resize: none; 
        }
        #message-form button { 
            padding: 10px 15px; margin-left: 10px; background-color: #28a745; 
            color: white; border: none; border-radius: 4px; cursor: pointer;
        }
        #message-form button:hover { background-color: #218838; }
        /* Este es el selector principal para el fondo del chat */
#chat-box {
    position: relative;
    background-color: #e5ddd5; /* Color de seguridad */
    background-image: url('../../src/imagenes/fondoChat.png'); /* Asegúrate de que la ruta sea correcta */
    background-repeat: repeat;   /* La imagen se repite como un mosaico */
    background-size: 350px;      /* Esto controla el tamaño de los dibujos. 
                                    Bájalo a 200px si los quieres más pequeños 
                                    o súbelo si los quieres más grandes. */
    
    background-position: top left;
    background-attachment: local; /* El fondo se mueve con el scroll de los mensajes */
    
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #ddd;
    min-height: 500px;
    z-index: 1;
}

/* Opcional: Si quieres que el fondo tenga un toque texturizado o sea más suave */
#chat-box::before {
    content: "";
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(255, 255, 255, 0.2); /* Capa blanquecina muy transparente */
    z-index: 0;
    pointer-events: none;
}

/* Asegúrate de que los mensajes individuales estén por encima del fondo */
.message {
    position: relative;
    z-index: 1;
}
    </style>
</head>


<body>

<div class="chat-container">
    <div class="chat-header d-flex align-items-center p-3 border-bottom bg-white sticky-top">
        <a href="listado_chats.php" class="btn btn-sm btn-outline-secondary me-3">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
        <div>
            <h5 class="mb-0">Chat con: <strong><?php echo htmlspecialchars($nombrePartner); ?></strong></h5>
            <small class="text-success"><i class="fa fa-circle"></i> En línea</small>
        </div>
    </div>
    
    <div id="chat-box">
        </div>
    
    <form id="message-form">
        <input type="hidden" id="receiver-id" value="<?php echo PARTNER_ID; ?>">
        <input type="hidden" id="sender-id" value="<?php echo CURRENT_USER_ID; ?>">

        <textarea id="message-input" placeholder="Escribe tu mensaje..." required></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const receiverId = document.getElementById('receiver-id').value;
    const currentUserId = document.getElementById('sender-id').value;

    let lastMessageId = 0; // Para la carga incremental (polling)
    const POLLING_INTERVAL = 2000; // Recargar cada 2 segundos

    // Función para añadir un mensaje al DOM
    function displayMessage(message) {
        // Formatear la hora
        const timePart = new Date(message.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const isSender = message.sender_id == currentUserId;
        const messageClass = isSender ? 'message-sent' : 'message-received';
        
        const messageElement = document.createElement('div');
        messageElement.classList.add('chat-message', messageClass);
        messageElement.innerHTML = `
            <span class="text">${message.message_text}</span>
            <span class="time">${timePart}</span>
        `;
        chatBox.appendChild(messageElement);
        
        // Actualizar el ID del último mensaje
        lastMessageId = message.id;
    }

    // Función para desplazar el chat hasta el último mensaje
    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // ----------------------------------------------------
    // FUNCIÓN PARA OBTENER MENSAJES
    // ----------------------------------------------------
    function fetchMessages(isInitialLoad = false) {
        const url = `get_messages.php?partner_id=${receiverId}&last_id=${lastMessageId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.messages.length > 0) {
                    const shouldScroll = (isInitialLoad || chatBox.scrollHeight - chatBox.scrollTop < chatBox.offsetHeight + 50); // Comprueba si el usuario está cerca del final
                    
                    data.messages.forEach(displayMessage);
                    
                    if (shouldScroll) {
                        scrollToBottom();
                    }
                }
            })
            .catch(error => {
                console.error('Error al obtener mensajes:', error);
            });
    }

    // ----------------------------------------------------
    // FUNCIÓN PARA ENVIAR MENSAJES
    // ----------------------------------------------------
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const messageText = messageInput.value.trim();
        if (messageText === '') return;

        // Deshabilitar el botón para evitar envíos dobles
        const submitButton = messageForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData();
        formData.append('receiver_id', receiverId);
        formData.append('message_text', messageText);

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = ''; // Limpiar el input
                // Forzar una actualización para mostrar el mensaje recién enviado
                fetchMessages(); 
            } else {
                console.error('Error al enviar:', data.message);
                alert('Error al enviar el mensaje.');
            }
        })
        .catch(error => {
            console.error('Error de red al enviar:', error);
            alert('Error de conexión.');
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });

    // Iniciar la carga inicial de mensajes
    fetchMessages(true); 
    
    // Iniciar el Polling: Recargar mensajes nuevos cada 2 segundos
    setInterval(fetchMessages, POLLING_INTERVAL); 
});
</script>

</body>
</html>