<?php
require 'db_config.php'; // Para usar la conexión $pdo y las constantes
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mensajes | TrueChange</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --tc-primary: #0d6efd;
            --tc-primary-dark: #0b5ed7;
            --tc-bg: #f4f7fb;
            --tc-card: #ffffff;
            --tc-text: #212529;
            --tc-muted: #6c757d;
            --tc-border: #e9ecef;
            --tc-danger: #dc3545;
            --tc-success: #198754;
            --tc-shadow: 0 10px 30px rgba(13, 110, 253, 0.08);
            --tc-radius: 20px;
        }

        body {
            background: linear-gradient(180deg, #f8fbff 0%, #f4f7fb 100%);
            color: var(--tc-text);
            min-height: 100vh;
        }

        .page-wrapper {
            padding: 30px 12px 60px;
        }

        .chat-shell {
            max-width: 1100px;
            margin: 0 auto;
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(13,110,253,0.10), rgba(13,110,253,0.03));
            border: 1px solid rgba(13,110,253,0.08);
            border-radius: 28px;
            padding: 28px;
            box-shadow: var(--tc-shadow);
            margin-bottom: 24px;
        }

        .hero-icon {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--tc-primary), var(--tc-primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            box-shadow: 0 12px 25px rgba(13,110,253,0.22);
            flex-shrink: 0;
        }

        .hero-title {
            font-size: 1.9rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .hero-text {
            color: var(--tc-muted);
            margin-bottom: 0;
        }

        .main-card {
            background: var(--tc-card);
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 28px;
            box-shadow: var(--tc-shadow);
            overflow: hidden;
        }

        .main-card-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--tc-border);
            background: #fff;
        }

        .main-card-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .main-card-subtitle {
            margin: 4px 0 0;
            color: var(--tc-muted);
            font-size: 0.95rem;
        }

        .chat-list {
            padding: 12px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px;
            border-radius: 18px;
            border: 1px solid transparent;
            transition: all 0.25s ease;
            cursor: pointer;
            margin-bottom: 10px;
            background: #fff;
        }

        .chat-item:hover {
            transform: translateY(-2px);
            background-color: #f8fbff;
            border-color: rgba(13,110,253,0.12);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        }

        .chat-item.unread {
            background: linear-gradient(90deg, rgba(220,53,69,0.05), rgba(255,255,255,1));
            border-left: 5px solid var(--tc-danger);
        }

        .chat-avatar {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e9f2ff, #dbe9ff);
            color: var(--tc-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
            border: 1px solid rgba(13,110,253,0.10);
        }

        .chat-avatar.unread {
            background: linear-gradient(135deg, #ffe5e8, #fff3f4);
            color: var(--tc-danger);
            border-color: rgba(220,53,69,0.15);
        }

        .chat-content {
            min-width: 0;
            flex-grow: 1;
        }

        .chat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px;
        }

        .chat-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--tc-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-time {
            font-size: 0.80rem;
            color: var(--tc-muted);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .chat-message {
            margin: 0;
            color: var(--tc-muted);
            font-size: 0.92rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .chat-extra {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .status-icon {
            font-size: 1.15rem;
        }

        .status-icon.read {
            color: #adb5bd;
        }

        .status-icon.unread {
            color: var(--tc-danger);
        }

        .badge-unread {
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border-radius: 999px;
            background: var(--tc-danger);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            color: var(--tc-muted);
        }

        .empty-state i {
            font-size: 3rem;
            color: #c8d4e3;
            margin-bottom: 16px;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .btn-back-tc {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .footer-note {
            color: var(--tc-muted);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 20px 10px 40px;
            }

            .hero-panel {
                padding: 20px;
                border-radius: 22px;
            }

            .hero-title {
                font-size: 1.45rem;
            }

            .main-card {
                border-radius: 22px;
            }

            .main-card-header {
                padding: 18px;
            }

            .chat-list {
                padding: 10px;
            }

            .chat-item {
                padding: 14px;
                gap: 12px;
                align-items: flex-start;
            }

            .chat-avatar {
                width: 48px;
                height: 48px;
                font-size: 1.1rem;
            }

            .chat-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 3px;
            }

            .chat-time {
                font-size: 0.75rem;
            }

            .chat-extra {
                align-self: center;
            }
        }

        @media (max-width: 480px) {
            .hero-panel {
                padding: 18px;
            }

            .hero-icon {
                width: 56px;
                height: 56px;
                font-size: 1.3rem;
            }

            .hero-title {
                font-size: 1.25rem;
            }

            .chat-item {
                padding: 12px;
                border-radius: 16px;
            }

            .chat-name {
                font-size: 0.95rem;
            }

            .chat-message {
                font-size: 0.86rem;
            }

            .badge-unread {
                min-width: 24px;
                height: 24px;
                font-size: 0.72rem;
            }
        }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <div class="chat-shell">

            <div class="hero-panel">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                    <div class="hero-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>

                    <div class="flex-grow-1">
                        <h1 class="hero-title">Mis mensajes</h1>
                        <p class="hero-text">
                            Consulta tus conversaciones, revisa mensajes pendientes y accede rápidamente a cada chat.
                        </p>
                    </div>

                    <a href="../../src/php/sesionIniciada.php" class="btn btn-outline-primary btn-back-tc">
                        <i class="fa fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>

            <div class="main-card">
                <div class="main-card-header">
                    <h2 class="main-card-title">
                        <i class="fa-solid fa-envelope-open-text me-2 text-primary"></i>Bandeja de entrada
                    </h2>
                    <p class="main-card-subtitle">Selecciona una conversación para abrir el chat.</p>
                </div>

                <div id="lista-conversaciones" class="chat-list">
                    <div class="empty-state">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <div>Cargando conversaciones...</div>
                    </div>
                </div>
            </div>

            <div class="actions-bar">
                
                <div class="footer-note">
                    TrueChange · Centro de mensajes
                </div>
            </div>

        </div>
    </div>

<script>
function escaparHTML(texto) {
    const div = document.createElement('div');
    div.textContent = texto ?? '';
    return div.innerHTML;
}

// Función para cargar la lista de personas con las que hay chat
function cargarBandeja() {
    fetch('get_chat_list.php')
        .then(res => res.json())
        .then(data => {
            const contenedor = document.getElementById('lista-conversaciones');
            if (!contenedor) return;

            contenedor.innerHTML = "";

            if (data.chats && Array.isArray(data.chats) && data.chats.length > 0) {
                data.chats.forEach(chat => {
                    const totalNoLeidos = parseInt(chat.total_no_leidos) || 0;
                    const nombreAMostrar = chat.nombre_usuario ? chat.nombre_usuario : "Usuario Desconocido";
                    const ultimoMensaje = chat.ultimo_mensaje ? chat.ultimo_mensaje : "Sin mensajes todavía";
                    const fechaMostrar = chat.fecha ? chat.fecha : "";
                    const inicial = nombreAMostrar.trim().charAt(0).toUpperCase();

                    const claseNoLeido = totalNoLeidos > 0 ? 'unread' : '';
                    const iconoSobre = totalNoLeidos > 0 ? 'fa-envelope unread' : 'fa-envelope-open read';
                    const avatarClase = totalNoLeidos > 0 ? 'unread' : '';

                    contenedor.innerHTML += `
                        <div onclick="abrirChat(${chat.partner_id})" class="chat-item ${claseNoLeido}">
                            <div class="chat-avatar ${avatarClase}">
                                ${inicial ? inicial : '<i class="fa-solid fa-user"></i>'}
                            </div>

                            <div class="chat-content">
                                <div class="chat-top">
                                    <h6 class="chat-name ${totalNoLeidos > 0 ? 'fw-bold' : ''}">
                                        ${escaparHTML(nombreAMostrar)}
                                    </h6>
                                    <small class="chat-time">${escaparHTML(fechaMostrar)}</small>
                                </div>

                                <p class="chat-message ${totalNoLeidos > 0 ? 'fw-semibold text-dark' : ''}">
                                    ${escaparHTML(ultimoMensaje)}
                                </p>
                            </div>

                            <div class="chat-extra">
                                <i class="fa-solid ${iconoSobre} status-icon"></i>
                                ${totalNoLeidos > 0 ? `<span class="badge-unread">${totalNoLeidos}</span>` : ''}
                            </div>
                        </div>
                    `;
                });
            } else {
                contenedor.innerHTML = `
                    <div class="empty-state">
                        <i class="fa-regular fa-comments"></i>
                        <h5 class="mb-2">Todavía no tienes conversaciones</h5>
                        <p class="mb-0">Cuando contactes con otros usuarios, tus chats aparecerán aquí.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            const contenedor = document.getElementById('lista-conversaciones');
            if (!contenedor) return;

            contenedor.innerHTML = `
                <div class="empty-state">
                    <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                    <h5 class="mb-2">No se pudo cargar la bandeja</h5>
                    <p class="mb-0">Ha ocurrido un error al obtener las conversaciones.</p>
                </div>
            `;
            console.error(error);
        });
}

// Esta función es la que se ejecuta al hacer clic en la conversación
function abrirChat(id) {
    console.log("Intentando abrir chat con ID:", id);

    const formData = new FormData();
    formData.append('id_vendedor', id);

    fetch('db_config.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Error en el servidor');
        return response.text();
    })
    .then(data => {
        console.log("Sesión guardada correctamente:", data);
        window.location.href = 'chat.php';
    })
    .catch(err => {
        console.error("Error al preparar el chat:", err);
        window.location.href = 'chat.php?partner_id=' + id;
    });
}

function guardarPartner(id) {
    const formData = new FormData();
    formData.append('id_vendedor', id);
    fetch('db_config.php', { method: 'POST', body: formData });
}

document.addEventListener('DOMContentLoaded', cargarBandeja);
</script>

</body>
</html>