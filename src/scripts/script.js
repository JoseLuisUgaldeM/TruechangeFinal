// Variable para almacenar todos los datos una vez que se cargan
let todosLosDatos = [];
const contenedorResultados = document.getElementById('resultados');
const contenedorResultadosFiltrados = document.getElementById('resultadosFiltrados');
var contador = 0;


// Variable para evitar notificaciones repetidas
// Consultar cada 10 segundos
if (usuarioLogueadoId !== null) {
    verificarNuevosMensajes();
    setInterval(verificarNuevosMensajes, 10000);
}
let mensajesDetectados = 0;

function verificarNuevosMensajes() {
    fetch('../../public/Chat/check_notifications.php')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notif-badge');
            // CAMBIO AQUÍ: de data.nuevos a data.count
            if (data.count > 0) {
                if (badge) {
                    badge.innerText = data.count;
                    badge.style.display = 'flex';
                }
                document.title = `(${data.count}) Mensajes nuevos`;
            } else if (badge) {
                badge.style.display = 'none';
                document.title = "TrueChange";
            }
        });
}



/**
 * Función para mostrar los datos del JSON.
 */
async function inicializarDatos() {

    try {
        contenedorResultados.innerHTML = `<p>Cargando datos...</p>`;
        const respuesta = await fetch('datos_usuario.json');

        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }
        let datosObtenidos = await respuesta.json();

        // 1. Calculamos la fecha y hora exacta de hace 2 días
        const limiteDosDias = new Date();
        limiteDosDias.setDate(limiteDosDias.getDate() - 2);

        // 2. Filtramos el array antes de guardarlo en 'todosLosDatos'
        todosLosDatos = datosObtenidos.filter(articulo => {
            // Comprobamos si el artículo está vendido y si tiene registrada la fecha de cambio
            if (articulo.estadoArticulo === 'vendido' && articulo.fecha_cambio) {

                // Convertimos la fecha_cambio de la BBDD (ej. "2026-03-05 10:00:00") a objeto Date
                const fechaCambio = new Date(articulo.fecha_cambio);

                // Si la fecha en la que se cambió es más antigua que nuestro límite, lo ocultamos
                if (fechaCambio < limiteDosDias) {
                    return false;
                }
            }
            // Si está 'disponible', 'reservado' o fue vendido hace menos de 2 días, lo mostramos
            return true;
        });

        // 3. Muestra los datos 
        mostrarDatos(todosLosDatos, contenedorResultados);
        aplicarFiltros();
    } catch (error) {
        console.error("Hubo un error al cargar o procesar los datos:", error);
        contenedorResultados.innerHTML = `<p style="color: red;">Error: No se pudieron cargar los datos. ${error.message}</p>`;
    }
}




function filtrarPorCategoria(event) {
    event.preventDefault();
    const campoFiltro = document.getElementById('campoCategoria').value;
    const valorFiltro = 'categoria';



    // El filtro de texto debe ser insensible a mayúsculas/minúsculas
    const valorLowerCase = campoFiltro.toLowerCase();

    // 1. Aplicar el filtro a los datos cargados previamente
    const datosFiltrados = todosLosDatos.filter(item => {
        const itemValue = item[valorFiltro];

        if (typeof itemValue === 'string') {
            // Filtrado de texto (insensible a mayúsculas/minúsculas y busca subcadenas)
            return itemValue.toLowerCase().includes(valorLowerCase);
        } else if (typeof itemValue === 'number' && !isNaN(parseFloat(valorLowerCase))) {
            // Filtrado de números (ej. para precios)
            // Esto busca coincidencias exactas con el número introducido.
            return itemValue === parseFloat(valorLowerCase);
        }


        // Si no es un string ni un número (o si el campo no existe), no lo incluimos
        return false;
    });

    // 2. Mostrar los resultados
    mostrarDatos(datosFiltrados, contenedorResultadosFiltrados, campoFiltro, valorFiltro);
}

/**
 * Función que se ejecuta al hacer clic en "Aplicar Filtro".
*/
function aplicarFiltro(event) {
    event.preventDefault();
    const campoFiltro = document.getElementById('campoFiltro').value;
    const valorFiltro = 'titulo';



    // El filtro de texto debe ser insensible a mayúsculas/minúsculas
    const valorLowerCase = campoFiltro.toLowerCase();

    // 1. Aplicar el filtro a los datos cargados previamente
    const datosFiltrados = todosLosDatos.filter(item => {
        const itemValue = item[valorFiltro];

        if (typeof itemValue === 'string') {
            // Filtrado de texto (insensible a mayúsculas/minúsculas y busca subcadenas)
            return itemValue.toLowerCase().includes(valorLowerCase);
        }

        // Si no es un string  (o si el campo no existe), no lo incluimos
        return false;
    });

    // 2. Mostrar los resultados
    mostrarDatos(datosFiltrados, contenedorResultados, campoFiltro, valorFiltro);
}

/**
 * Función para mostrar todos los datos sin filtro.
*/
function mostrarTodos() {
    mostrarDatos(todosLosDatos, contenedorResultados, contador);
}


function mostrarDatos(datos, contenedor, campo = null, valor = null) {

    // 1. Limpiar el contenedor de tarjetas
    contenedor.innerHTML = "";

    // 2. Buscar o crear el contenedor EXCLUSIVO para los modales
    // Esto asegura que los modales vivan fuera de las cards y no se vean afectados por el 'transform'
    let contenedorModales = document.getElementById('contenedor-modales-dinamicos');
    if (!contenedorModales) {
        contenedorModales = document.createElement('div');
        contenedorModales.id = 'contenedor-modales-dinamicos';
        document.body.appendChild(contenedorModales);
    } else {
        // Si ya existe, lo limpiamos para no acumular modales viejos de búsquedas anteriores
        contenedorModales.innerHTML = "";
    }

    if (datos.length === 0) {

        contenedor.innerHTML = `<div class="alert alert-warning col-12" role="alert">No se encontraron artículos.</div>`;
        return;
    }

    datos.forEach(item => {
            if (
        item.estadoArticulo === 'vendido' &&
        item.fecha_venta &&
        (Date.now() - new Date(item.fecha_venta).getTime()) >= (24 * 60 * 60 * 1000)
    ) {
        return;
    }
        // --- Lógica de fechas ---
        const fechaInicio = new Date(item.fecha_publicacion);
        const fechaActual = new Date();
        fechaInicio.setHours(0, 0, 0, 0);
        fechaActual.setHours(0, 0, 0, 0);
        const diferenciaMilisegundos = fechaActual.getTime() - fechaInicio.getTime();
        const milisegundosEnUnDia = 1000 * 60 * 60 * 24;
        let imprimir = "Publicado hoy";
        let intervalo = Math.floor(diferenciaMilisegundos / milisegundosEnUnDia);

        if (intervalo > 1) imprimir = `Publicado hace ${intervalo} dias `;
        if (intervalo == 1) imprimir = `Publicado hace ${intervalo} dia `;

        // 1. Solo comparamos si el usuarioLogueadoId no es nulo
        const esMio = (usuarioLogueadoId !== null) && (String(item.usuario_id) === String(usuarioLogueadoId));

        // 2. Definimos el botón según el estado
        let botonMensajeHtml = "";

        if (usuarioLogueadoId === null) {
            // CASO A: Nadie está logueado (Invitado en index.php)
            // Mostramos el botón pero que abra el modal de login
            botonMensajeHtml = `
            <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#Modal">
            <i class="fa fa-envelope"></i> Inicia sesión para contactar
            </button>`;
        } else if (esMio) {
            // CASO B: Es mi propio anuncio
            botonMensajeHtml = `
            <button class="btn btn-secondary btn-sm w-100" disabled>
            <i class="fa fa-user"></i> Tu anuncio
            </button>`;
        } else {
            // CASO C: Estoy logueado y el anuncio es de otro
            botonMensajeHtml = `
               <button type="button" class="btn btn-primary btn-chat btn-enviar-id" data-id="${item.usuario_id}">Enviar mensaje</button>`

        }

        const imagen = item.ruta_foto ? item.ruta_foto : '../imagenes/uploads/default.png';
        // --- PROCESAR EL ESTADO PARA DARLE ESTILO ---
        const estado = item.estadoArticulo || 'disponible'; // Valor por defecto
        let badgeHtml = '';

        if (estado === 'vendido') {
            badgeHtml = `<span class="badge-estado badge-vendido position-absolute top-0 start-0 m-2">
            <i class="fa fa-handshake-o"></i> Cambiado
            </span>`;
        } else if (estado === 'reservado') {
            badgeHtml = `<span class="badge-estado badge-reservado position-absolute top-0 start-0 m-2">
            <i class="fa fa-clock-o"></i> Reservado
            </span>`;
        } else {
            badgeHtml = `<span class="badge-estado badge-disponible position-absolute top-0 start-0 m-2">
            <i class="fa fa-check-circle"></i> Disponible
            </span>`;
        }

        const estrellasHTML = generarEstrellasHTML(item.valoracion_media);
        // --- PARTE A: SOLO LA TARJETA ---
        // Nota: Mantenemos 'card-efecto' aquí para la animación
        // Lógica para determinar si está vendido
        const esVendido = item.estadoArticulo === 'vendido';
        const claseVendido = esVendido ? 'card-vendido' : '';

        const cardHtml = `
<div class="col-xl-2 col-lg-3 col-sm-12 col mb-4">
    <div class="card h-100 shadow-sm card-efecto border-0 ${claseVendido}">
        <div class="position-relative overflow-hidden">
            <img src="${imagen}" class="card-img-top" alt="${item.titulo}" style="height: 180px; object-fit: cover;">
        </div>

        <div class="card-body d-flex flex-column p-3">
            <h5 class="card-title fw-bold text-dark mb-1">${item.titulo}</h5>
            <p class="card-text text-truncate text-muted small mb-3">${item.descripcion}</p>
            
            <p class="text-primary fw-bold mt-auto mb-2 small text-uppercase">
                ${item.categoria}
            </p>

             <p class="text-primary fw-bold mt-auto mb-2 small text-uppercase">
                ${item.estado}
            </p>

            <div class="border-0 p-3 mb-2 bg-dark bg-gradient rounded-3 shadow-sm">
                <h6 class="text-white-50 small mb-1" style="font-size: 0.7rem;">Cambio por...</h6>
                <p class="text-white fw-bold mb-0 small text-truncate">${item.cambio}</p>
            </div>

            <div class="border rounded-3 p-2 mb-3 bg-light-subtle ${esVendido ? 'disabled-interaction' : ''}">
                <h6 class="text-muted mb-2" style="font-size: 0.7rem;">Subido por:</h6>
                <div class="d-flex align-items-center mb-1" ${!esVendido ? `onclick="verOpiniones(${item.usuario_id})"` : ''} style="cursor:pointer;">
                    <i class="fa fa-user-circle-o text-primary me-2" style="font-size: 1.2rem;"></i>
                    <span class="small fw-semibold text-dark">Opiniones</span>
                </div>
                <p class="text-primary fw-bold mb-0 small">${item.nombre} <span class="ms-1">${estrellasHTML}</span></p>
            </div> 

            <button type="button" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" 
                    data-bs-toggle="modal" data-bs-target="#exampleModalArticulo${contador}">
                ${esVendido ? 'Ver Archivo' : 'Ver artículo'}
                ${badgeHtml}
            </button>
        </div>
    </div>
</div>`;

        // --- PARTE B: EL MODAL ---

        const modalHtml = `
<div class="modal fade" id="exampleModalArticulo${contador}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            
            <div class="modal-header border-0 pb-0">
                <div class="d-flex flex-column">
                    <small class="text-uppercase text-primary fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.75rem;">Detalles del Artículo</small>
                    <h4 class="modal-title fw-bolder text-dark" id="exampleModalLabel">${item.titulo}</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="position-relative overflow-hidden rounded-4 shadow-sm bg-light h-100 d-flex align-items-center justify-content-center">
                            <div class="position-absolute top-0 start-0 m-3">
                                ${badgeHtml} </div>
                            <img src="${imagen}" class="img-fluid rounded-4" alt="${item.titulo}" style="object-fit: cover; width: 100%; max-height: 400px;">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h6 class="text-muted fw-bold small text-uppercase mb-2">Descripción</h6>
                            <p class="text-dark leading-relaxed">${item.descripcion}</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border-start border-primary border-4 shadow-sm">
                                    <h6 class="text-muted small mb-1">Categoría</h6>
                                    <span class="text-dark fw-bold">${item.categoria}</span>
                                </div>
                                   <div class="bg-light p-3 rounded-3 border-start border-primary border-4 shadow-sm">
                                    <h6 class="text-muted small mb-1">Estado</h6>
                                    <span class="text-primary fw-bold text-uppercase">${item.estado}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border-start border-info border-4 shadow-sm">
                                    <h6 class="text-muted small mb-1">Publicado</h6>
                                    <span class="text-dark fw-bold">${imprimir}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-muted fw-bold small text-uppercase mb-3">Ofertado por</h6>
                            <div class="d-flex align-items-center bg-white p-2 rounded-pill border shadow-sm">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    <span class="fw-bold">${item.nombre.charAt(0)}</span>
                                </div>
                                <div>
                                    <p class="mb-0 fw-bold text-dark">${item.nombre}</p>
                                    <div class="text-warning small">
                                        ${estrellasHTML}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cerrar</button>
                ${botonMensajeHtml} </div>
        </div>
    </div>
</div>`;

        // Insertamos cada parte en su contenedor correspondiente
        contenedor.innerHTML += cardHtml;           // La tarjeta va al grid
        contenedorModales.innerHTML += modalHtml;   // El modal va al fondo del body
        contador++;
    });

}

// Iniciar la carga de datos al cargar la página
inicializarDatos();

function mostrarNotificacion() {
    reproducirSonido();
    const toastElement = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

function reproducirSonido() {
    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
    audio.play();
}


// 1. Detectar el clic en el botón de la card
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-enviar-id')) {
        // 1. Obtener el ID del vendedor
        const vendedorId = e.target.getAttribute('data-id');

        // 2. Enviar el ID a PHP
        fetch('../../public/Chat/db_config.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_vendedor=' + encodeURIComponent(vendedorId)
        })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                return response.text();
            })
            .then(data => {
                console.log('Sesión actualizada:', data);
                // 3. AHORA SÍ, redirigimos después de confirmar el éxito
                window.location.href = '../../public/Chat/chat.php';
            })
            .catch(error => {
                console.error('Error al guardar sesión:', error);
                alert('No se pudo iniciar el chat. Intentalo de nuevo.');
            });
    }

   /* fetch('../../public/Chat/verificar_notifications.php')
        .then(res => res.json())
        .then(data => {
            if (data.count > 0) {
                // Mostrar el Toast que ya tienes en tu HTML o un punto rojo en el icono de mensajes
                const toast = new bootstrap.Toast(document.getElementById('liveToast'));
                toast.show();
            }
        }); */
});

document.addEventListener('DOMContentLoaded', () => {
    cargarMisProductos();
});

// 1. MODIFICAMOS LA FUNCIÓN DE CARGAR PARA AÑADIR BOTONES REALES
async function cargarMisProductos() {
    const contenedor = document.getElementById('contenedor-mis-productos');
    if (!contenedor) return;

    try {
        const respuesta = await fetch('../php/api_mis_productos.php');
        const misProductos = await respuesta.json();

        contenedor.innerHTML = '';

        if (misProductos.length === 0) {
            contenedor.innerHTML = '<p class="text-muted">Aún no has subido ningún artículo.</p>';
            return;
        }

        misProductos.forEach(producto => {
            const imagen = producto.ruta_foto;
            const estado = producto.estadoArticulo || 'disponible';

            // Preparamos datos para editar
            const datosProducto = JSON.stringify(producto).replace(/"/g, '&quot;');

            // 1. GENERAR EL BADGE (Etiqueta superior izquierda)
            let badgeHtml = '';
            if (estado === 'vendido') {
                badgeHtml = `<span class="badge-estado badge-vendido position-absolute top-0 start-0 m-2"><i class="fa fa-handshake-o"></i> Cambiado</span>`;
            } else if (estado === 'reservado') {
                badgeHtml = `<span class="badge-estado badge-reservado position-absolute top-0 start-0 m-2"><i class="fa fa-clock-o"></i> Reservado</span>`;
            } else {
                badgeHtml = `<span class="badge-estado badge-disponible position-absolute top-0 start-0 m-2"><i class="fa fa-check-circle"></i> Disponible</span>`;
            }

            // 2. GENERAR LOS BOTONES DE ESTADO (O EL CANDADO)
            let htmlBotonesEstado = '';

            if (estado === 'vendido') {
                // CASO A: Si está vendido, mostramos el candado y BLOQUEAMOS acciones
                htmlBotonesEstado = `
                <div class="mt-2 text-center w-100">
                    <div class="alert alert-secondary mb-0 py-1 shadow-sm" style="font-size: 0.8rem;">
                        <i class="fa fa-lock"></i> <strong>Trato cerrado</strong>
                    </div>
                </div>`;
            } else {
                // CASO B: Si no está vendido, mostramos los botones para cambiar estado
                htmlBotonesEstado = `
                <div>
                    <button class="btn btn-outline-primary btn-sm me-1" title="Editar" onclick='abrirModalEditar(${datosProducto})'>
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" title="Eliminar" onclick="eliminarProducto(${producto.articulo_id})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="btn-group btn-group-sm ms-2" role="group">
                    <button type="button" class="btn btn-outline-success ${estado === 'disponible' ? 'active' : ''}" 
                    onclick="cambiarEstadoArticulo(${producto.articulo_id}, 'disponible')">Disponible</button>
                    <button type="button" class="btn btn-outline-warning ${estado === 'reservado' ? 'active' : ''}" 
                            onclick="cambiarEstadoArticulo(${producto.articulo_id}, 'reservado')">Reservado</button>
                    <button type="button" class="btn btn-outline-danger ${estado === 'vendido' ? 'active' : ''}" 
                            onclick="cambiarEstadoArticulo(${producto.articulo_id}, 'vendido')">Cambiado</button>
                </div>`;
            }

            // 3. HTML DE LA TARJETA
            // Filtro gris a la imagen si está vendido
            const cardHTML = `
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 card-efecto">
                        <div style="position: relative;">
                           ${badgeHtml}
                           <img src="${imagen}" class="card-img-top" alt="${producto.titulo}" 
                                style="height: 200px; object-fit: cover; opacity: ${estado === 'vendido' ? '0.6' : '1'};">
                           <span class="badge bg-primary position-absolute top-0 end-0 m-2">${producto.categoria}</span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="${producto.titulo}">${producto.titulo}</h5>
                            <p class="card-text text-truncate text-muted small">${producto.descripcion}</p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
                                

                                ${htmlBotonesEstado}
        
                            </div>
                        </div>
                    </div>
                </div>`;

            contenedor.innerHTML += cardHTML;
        });

    } catch (error) {
        console.error("Error cargando mis productos:", error);
        contenedor.innerHTML = '<p class="text-danger">Error al cargar tus productos.</p>';
    }
}
// 3. FUNCIÓN PARA ABRIR EL MODAL Y RELLENAR DATOS
var modalBootstrap;

function abrirModalEditar(producto) {
    // Rellenamos los inputs con los datos actuales
    document.getElementById('edit_id').value = producto.articulo_id;
    document.getElementById('edit_titulo').value = producto.titulo;
    document.getElementById('edit_descripcion').value = producto.descripcion;
    document.getElementById('edit_categoria').value = producto.categoria;
    document.getElementById('edit_estado').value = producto.estado;

    // Abrimos el modal usando Bootstrap 5
    var modalElement = document.getElementById('modalEditar');
    modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

// 4. FUNCIÓN PARA GUARDAR LOS CAMBIOS
function guardarCambios() {
    const id = document.getElementById('edit_id').value;
    const titulo = document.getElementById('edit_titulo').value;
    const descripcion = document.getElementById('edit_descripcion').value;
    const categoria = document.getElementById('edit_categoria').value;
    const estado = document.getElementById('edit_estado').value;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('titulo', titulo);
    formData.append('descripcion', descripcion);
    formData.append('categoria', categoria);
    formData.append('estado', estado);

    fetch('../php/editar_articulo.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Artículo actualizado correctamente.");
                modalBootstrap.hide(); // Cerramos modal
                cargarMisProductos(); // Recargamos lista
                inicializarDatos();
                
            } else {
                alert("Error al actualizar: " + (data.message || 'Error desconocido'));
            }
        });
         
}

function eliminarProducto(id) {
    if (!confirm("¿Estás seguro de que quieres eliminar este artículo?")) return;

    const formData = new FormData();
    formData.append('id', id);


    fetch('../php/eliminar_articulo.php', {
        method: 'POST',
        body: formData
    })
        .then(res => {
            // Esto nos ayuda a ver si el archivo existe o da error 404/500
            if (!res.ok) {
                throw new Error("Error en la red: " + res.status + " " + res.statusText);
            }
            return res.json(); // Intentamos leer el JSON
        })
        .then(data => {
            if (data.success) {
                alert("Artículo eliminado correctamente.");
                cargarMisProductos(); // Recargar la lista
                todosLosDatos = todosLosDatos.filter(item => String(item.articulo_id) !== String(id));
                aplicarFiltros();
                
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Ocurrió un error al intentar borrar. Abre la consola (F12) para ver más detalles.");
        });
}


// 1. Seleccionamos los elementos del DOM
const inputGeneral = document.getElementById('buscador-general');
const inputCambio = document.getElementById('buscador-cambio');
const selectOrden = document.getElementById('filtro-orden');
const inputCiudad = document.getElementById('buscador-ciudad');

// 2. Función Maestra: Aplica todos los filtros a la vez
function aplicarFiltros() {
    // Empezamos con una copia de TODOS los datos
    let resultados = [...todosLosDatos];

    // A) FILTRO POR TÍTULO O DESCRIPCIÓN (Buscador General)
    if (inputGeneral && inputGeneral.value.trim() !== "") {
        const texto = inputGeneral.value.toLowerCase();
        resultados = resultados.filter(item =>
            item.titulo.toLowerCase().includes(texto) ||
            item.descripcion.toLowerCase().includes(texto)
        );
    }

    // B) FILTRO POR 'CAMBIO' (Lo que el vendedor busca)
    // Asumimos que la info de qué quiere cambio está en la descripción o un campo específico
    if (inputCambio && inputCambio.value.trim() !== "") {
        const textoCambio = inputCambio.value.toLowerCase();
        resultados = resultados.filter(item => {
            // Buscamos palabras clave como "cambio por", "busco", o si tienes un campo 'preferencia_cambio'
            // Si tienes un campo especifico en tu JSON úsalo aquí: item.preferencia_cambio
            const contenido = item.cambio.toLowerCase();
            return contenido.includes(textoCambio);
        });
    }

    if (inputCiudad && inputCiudad.value.trim() !== "") {
        const textoCiudad = inputCiudad.value.toLowerCase();
        resultados = resultados.filter(item => {
            const ciudad = item.ciudad.toLowerCase();
            return ciudad.includes(textoCiudad);
        });
    }

    // C) ORDENACIÓN
    if (selectOrden) {
        const criterio = selectOrden.value;

        if (criterio === 'reciente') {
            resultados.sort((a, b) => new Date(b.fecha_publicacion) - new Date(a.fecha_publicacion));
        } else if (criterio === 'antiguo') {
            resultados.sort((a, b) => new Date(a.fecha_publicacion) - new Date(b.fecha_publicacion));
        } else if (criterio === 'nombre_asc') {
            resultados.sort((a, b) => a.titulo.localeCompare(b.titulo));
        } else if (criterio === 'nombre_desc') {
            resultados.sort((a, b) => b.titulo.localeCompare(a.titulo));
        }
    }

    // 3. Mostrar los resultados finales
    const contenedor = document.getElementById('resultados');
    mostrarDatos(resultados, contenedor);
}

// 3. Asignar los eventos (Escuchamos cuando el usuario escribe o cambia algo)
if (inputGeneral) inputGeneral.addEventListener('input', aplicarFiltros);
if (inputCambio) inputCambio.addEventListener('input', aplicarFiltros);
if (inputCiudad) inputCiudad.addEventListener('input', aplicarFiltros);
if (selectOrden) selectOrden.addEventListener('change', aplicarFiltros);

// Ejecutar filtro inicial al cargar (para que salga ordenado por reciente)
setTimeout(() => { aplicarFiltros(); }, 500); // Pequeño retardo para asegurar que los datos cargaron

// Variable global para saber qué artículo estamos puntuando
let idArticuloParaReseña = null;

// 1. La función que ya conoces, pero ahora activa el modal
let idArticuloEnProceso = null;

// 1. Modificamos la función principal
function cambiarEstadoArticulo(idArticulo, nuevoEstado) {
    if (nuevoEstado === 'vendido') {
        // PASO A: Si es venta, paramos y pedimos el comprador
        idArticuloEnProceso = idArticulo;
        cargarPosiblesCompradores(); // Función nueva (ver abajo)
        const modalComprador = new bootstrap.Modal(document.getElementById('modalSeleccionarComprador'));
        modalComprador.show();
    } else {
        // Si es "reservado" o "disponible", lo hacemos directo como antes
        procesarCambioEstado(idArticulo, nuevoEstado, null);

    }
}

// 2. Función para llenar el select con usuarios
function cargarPosiblesCompradores() {
    const select = document.getElementById('select-comprador');
    select.innerHTML = '<option disabled selected>Cargando...</option>';


    fetch('../php/obtener_usuarios_chat.php')
        .then(res => res.json())
        .then(usuarios => {
            console.log(usuarios);
            select.innerHTML = '<option disabled selected>Selecciona al usuario </option>';
            usuarios.forEach(u => {
                select.innerHTML += `<option value="${u.id}">${u.nombre}</option>`;
            });
        });
}

// 3. Función al pulsar "Confirmar intercambio" en el modal
function confirmarVenta() {
    const compradorId = document.getElementById('select-comprador').value;

    if (!compradorId) return alert("Debes seleccionar un usuario.");

    // Cerramos modal de comprador
    const modalComprador = bootstrap.Modal.getInstance(document.getElementById('modalSeleccionarComprador'));
    modalComprador.hide();

    // Procesamos la venta enviando el ID del comprador
    procesarCambioEstado(idArticuloEnProceso, 'vendido', compradorId);
}

// 4. La función que finalmente habla con el servidor
function procesarCambioEstado(id, estado, compradorId) {
    
    const formData = new FormData();
    formData.append('id', id);
    formData.append('estadoArticulo', estado);
    if (compradorId) formData.append('receptor_id', compradorId);

    fetch('../php/cambiar_estado.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (estado === 'vendido') {
                    // Ahora sí, abrimos la reseña
                    idArticuloParaReseña = id;
                    // Guardamos también el ID del comprador en una variable global por si la reseña lo necesita
                    receptorReseñaId = compradorId;

                    const modalResena = new bootstrap.Modal(document.getElementById('modalReseña'));
                    modalResena.show();
                } else {
                    inicializarDatos();
                    cargarMisProductos();
                    aplicarFiltros();
                }
            }
        });
}

// Lógica para pintar las estrellas al hacer clic
document.querySelectorAll('.estrellas-rating i').forEach(estrella => {
    estrella.addEventListener('click', function () {
        const valorSeleccionado = this.getAttribute('data-value');

        // Guardamos el valor (ej: 4) en el input oculto
        document.getElementById('puntuacion-valor').value = valorSeleccionado;

        // Recorremos todas las estrellas para pintarlas o despintarlas
        document.querySelectorAll('.estrellas-rating i').forEach(s => {
            if (s.getAttribute('data-value') <= valorSeleccionado) {
                // Si es menor o igual a la que pulsé, la pongo rellena (fa-star)
                s.classList.remove('fa-star-o');
                s.classList.add('fa-star');
            } else {
                // Si es mayor, la pongo vacía (fa-star-o)
                s.classList.remove('fa-star');
                s.classList.add('fa-star-o');
            }
        });
    });
});

function enviarResena() {
    // 1. Capturamos los valores del Modal
    const puntuacionInput = document.getElementById('puntuacion-valor');
    const comentarioInput = document.getElementById('comentario-reseña');

    const puntuacion = puntuacionInput.value;
    const comentario = comentarioInput.value.trim(); // .trim() quita espacios vacíos al inicio y final

    // 2. Validación de seguridad: Obligar a poner estrellas
    if (puntuacion == 0 || puntuacion === "") {
        alert("¡Por favor, selecciona una puntuación de estrellas antes de enviar!");
        return; // Detenemos la función aquí
    }

    // 3. Preparamos los datos para enviar (FormData)
    const formData = new FormData();
    // 'idArticuloEnProceso' es la variable global que guardamos cuando pulsaste "Vender"
    formData.append('articulo_id', idArticuloEnProceso);
    formData.append('puntuacion', puntuacion);
    formData.append('comentario', comentario);

    // 4. Enviamos al servidor (guardar_resena.php)
    fetch('../php/guardar_resena.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ÉXITO:
                alert("¡Gracias! Tu valoración se ha guardado correctamente.");

                // Cerramos el modal limpiamente
                const modalEl = document.getElementById('modalReseña');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();

                // Recargamos la página para ver los cambios (artículo vendido y reputación actualizada)
                location.reload();
            } else {
                // ERROR:
                alert("Hubo un error al guardar la reseña. Inténtalo de nuevo.");
                console.error(data);
            }
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            alert("Error de conexión con el servidor.");
        });
}

function generarEstrellasHTML(puntuacion) {
    let html = '<div class="text-warning" style="font-size: 0.8rem;">';

    // Convertimos null o undefined a 0
    let nota = parseFloat(puntuacion) || 0;

    // Pintamos 5 estrellas
    for (let i = 1; i <= 5; i++) {
        if (nota >= i) {
            // Estrella llena
            html += '<i class="fa fa-star"></i>';
        } else if (nota >= i - 0.5) {
            // Media estrella
            html += '<i class="fa fa-star-half-o"></i>';
        } else {
            // Estrella vacía
            html += '<i class="fa fa-star-o"></i>';
        }
    }

    // Añadimos el número en texto pequeño al lado (ej: 4.5)
    if (nota > 0) {
        html += ` <span class="text-muted small">(${nota})</span>`;
    } else {
        html += ` <span class="text-muted small py-1" style="font-size:0.7rem">Nuevo</span>`;
    }

    html += '</div>';
    return html;
}

function verOpiniones(usuarioId) {
    // 1. Abrimos el modal
    const modal = new bootstrap.Modal(document.getElementById('modalVerOpiniones'));
    modal.show();

    const contenedor = document.getElementById('lista-opiniones');
    contenedor.innerHTML = '<div class="text-center p-3"><i class="fa fa-spinner fa-spin"></i> Cargando experiencias...</div>';

    // 2. Pedimos las reseñas al servidor
    fetch(`obtener_opiniones_vendedor.php?id=${usuarioId}`)
        .then(res => res.json())
        .then(opiniones => {
            if (opiniones.length === 0) {
                contenedor.innerHTML = '<p class="text-center p-4">Este usuario aún no tiene valoraciones. ¡Sé el primero en intercambiar con él!</p>';
                return;
            }

            // 3. Mostramos cada reseña
            contenedor.innerHTML = opiniones.map(op => `
                <div class="list-group-item border-0 border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-primary">${op.nombre_emisor}</strong>
                        <span class="small text-muted">${new Date(op.fecha).toLocaleDateString()}</span>
                    </div>
                    <div class="mb-2">${generarEstrellasHTML(op.puntuacion)}</div>
                    <p class="mb-0 text-secondary italic">"${op.comentario || 'Sin comentario'}"</p>
                </div>
            `).join('');
        })
        .catch(err => {
            contenedor.innerHTML = '<p class="text-danger">Error al cargar las opiniones.</p>';
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn-valoraciones');

    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const miId = e.target.getAttribute('data-id');
        verOpiniones(miId);
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('contenedor-busqueda');
    const carrusel = document.getElementById('carouselExampleInterval');
    const seccionArticulos = document.getElementById('articulos');
    const titulo = contenedor.querySelector('h3');
    const campos = contenedor.querySelectorAll('.campo-busqueda');

    const inputGeneral = document.getElementById('buscador-general');
    const inputCambio = document.getElementById('buscador-cambio');
    const inputCiudad = document.getElementById('buscador-ciudad');
    const selectOrden = document.getElementById('filtro-orden');

    const padreOriginal = contenedor.parentNode;
    const siguienteHermanoOriginal = contenedor.nextSibling;

    let modoCompactoActivo = false;

    function transformarBuscador(inputActivo) {
        if (modoCompactoActivo) return;

        // Guardamos la posición del cursor antes de mover el DOM
        const cursorStart = inputActivo.selectionStart;


        if (carrusel) {
            carrusel.classList.add('d-none');
            carrusel.classList.remove('d-lg-block');
        }
        if (titulo) titulo.classList.add('d-none');

        contenedor.classList.remove('col-lg-6');
        contenedor.classList.add('col-12', 'buscador-fijo', 'sticky-top', 'bg-white');

        // 2. Ajuste Responsive: col-md-3 para que los 4 campos quepan en una fila
        campos.forEach(div => {
            div.classList.replace('col-12', 'col-md-3');
            const label = div.querySelector('label');
            if (label) label.classList.add('d-none');
        });

        // 3. Mover el buscador arriba de los artículos
        seccionArticulos.parentNode.insertBefore(contenedor, seccionArticulos);


        setTimeout(() => {
            inputActivo.focus();
            if (cursorStart !== null) inputActivo.setSelectionRange(cursorStart, cursorStart);
        }, 10);

        modoCompactoActivo = true;
    }

    function restaurarOriginal() {
        if (!modoCompactoActivo) return;

        padreOriginal.insertBefore(contenedor, siguienteHermanoOriginal);

        contenedor.classList.remove('col-12', 'buscador-fijo', 'sticky-top', 'bg-white');
        contenedor.classList.add('col-lg-6');

        // Restauramos clases del carrusel
        if (carrusel) {
            carrusel.classList.remove('d-none');
            carrusel.classList.add('d-lg-block');
        }
        if (titulo) titulo.classList.remove('d-none');

        campos.forEach(div => {
            div.classList.replace('col-md-3', 'col-12');
            const label = div.querySelector('label');
            if (label) label.classList.remove('d-none');
        });

        modoCompactoActivo = false;
    }

    // Eventos para todos los inputs de búsqueda
    [inputGeneral, inputCambio, inputCiudad].forEach(input => {
        if (input) {
            input.addEventListener('input', function () {
                // Comprobamos si alguno de los tres campos tiene texto
                const tieneTexto = (inputGeneral.value.trim().length > 0) ||
                    (inputCambio.value.trim().length > 0) ||
                    (inputCiudad.value.trim().length > 0);

                if (tieneTexto) {
                    transformarBuscador(this);
                } else {
                    restaurarOriginal();
                }
            });
        }
    });
});


const $botonPerfil = document.getElementById('botonPerfil');

$botonPerfil.addEventListener('click', mostrarPerfil());

function mostrarPerfil() {

    const datos = document.getElementById('idPerfil');
    fetch('../php/crearPerfilUsuario.php')
        .then(response => response.json())
        .then(datosObtenidos =>
            datosObtenidos.forEach(element => {


                const datosPerfil = `
<div class="container-fluid">
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block">
            <img src="${element.avatar}" 
                 alt="Foto de perfil" 
                 class="rounded-circle shadow border border-3 border-white" 
                 style="width: 120px; height: 120px; object-fit: cover; background-color: #f8f9fa;">
        </div>
        <h3 class="mt-3 fw-bold text-primary">${element.nombre} ${element.apellido1} ${element.apellido2}</h3>
        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill" style="background-color: #e7f1ff;">Usuario Registrado</span>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 border rounded bg-light shadow-sm h-100">
                <label class="text-muted d-block small fw-bold text-uppercase mb-1">
                    <i class="fa fa-envelope-o me-2 text-primary"></i>Correo Electrónico
                </label>
                <span class="text-dark fw-medium">${element.email}</span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded bg-light shadow-sm h-100">
                <label class="text-muted d-block small fw-bold text-uppercase mb-1">
                    <i class="fa fa-map-marker me-2 text-danger"></i>Ciudad
                </label>
                <span class="text-dark fw-medium">${element.ciudad}</span>
            </div>
        </div>

        <div class="col-12">
            <div class="p-3 border rounded bg-light shadow-sm">
                <label class="text-muted d-block small fw-bold text-uppercase mb-1">
                    <i class="fa fa-calendar-check-o me-2 text-success"></i>Fecha de registro
                </label>
                <span class="text-dark fw-medium">
                    ${new Date(element.fecha_registro).toLocaleDateString('es-ES', {
                    day: '2-digit', month: 'long', year: 'numeric'
                })}
                </span>
            </div>
        </div>
    </div>
</div>`;

                datos.innerHTML = datosPerfil;
            }));
}

function aplicarFiltroYcategoria(event) {
    // 1. Evitar que la página recargue al darle al botón submit
    if (event) {
        event.preventDefault();
    }

    // 2. Obtener los valores del select y del input
    const categoria = document.getElementById('searchCategory').value;
    const texto = document.getElementById('campoFiltro').value.toLowerCase().trim();

    // 3. Filtrar usando el array principal que tienes (todosLosDatos)
    const resultadosFiltrados = todosLosDatos.filter(articulo => {
        
        // Condición 1: Comprobar la categoría
        // Pasa si seleccionó "todas" o si la categoría del artículo es exactamente igual a la seleccionada
        const coincideCategoria = (categoria === "todas" || articulo.categoria === categoria);

        // Condición 2: Comprobar el texto
        // Pasa si el nombre o la descripción contienen el texto escrito en el input
        const coincideTexto = articulo.nombre.toLowerCase().includes(texto) || 
                              articulo.descripcion.toLowerCase().includes(texto);

        // Un artículo solo se muestra si cumple AMBAS condiciones
        return coincideCategoria && coincideTexto;
    });

    // 4. Mostrar los resultados
    const contenedor = document.getElementById('articulos'); // Ajusta esto a tu contenedor si se llama diferente
    
    if (contenedor) {
        contenedor.innerHTML = ""; // Limpiamos la pantalla

        if (resultadosFiltrados.length > 0) {
            // Utilizamos tu función original que pinta los artículos
            mostrarDatos(resultadosFiltrados, contenedor);
        } else {
            // Mensaje elegante si no hay coincidencias
            contenedor.innerHTML = `
                <div class="col-12 text-center my-5 py-5 w-100">
                    <i class="fa fa-search fa-3x text-muted mb-3 opacity-25"></i>
                    <h4 class="text-secondary fw-bold">No hemos encontrado nada</h4>
                    <p class="text-muted">Prueba a buscar con otras palabras o cambia la categoría.</p>
                </div>
            `;
        }
    }
}