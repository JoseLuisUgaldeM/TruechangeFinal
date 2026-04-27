<?php

require_once "../clases/Usuario.php";

require_once "../clases/Articulos.php";


$database = new Database();

$db = $database->getConnection();

require_once "crearFicheroJson.php";

session_start();

if ($_SESSION['inicioSesion'] == true) {

    $usuario = new Usuario($database);

    $id_usuario =  $_SESSION['id_usuario'];

    // Hacemos una consulta a la base da datos para obtener el nombre 

    $datosUsuario = $usuario->obtenerUsuarioPorId($id_usuario);

    $usuarioNombre = $datosUsuario['usuarioNombre'];

    creaYactualiza($usuario);


    $_SESSION['usuarioNombre'] = $usuarioNombre;


    $_SESSION['avatar'] =  $datosUsuario['avatar'];


    $todos = $usuario->listarUsuarios();

    header('Content-Type: text/html; charset=utf-8');
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <link rel="icon" href="../imagenes/icono_proyecto.png" type="image/x-icon">
    <link rel="shortcut icon" href="../imagenes/icono_proyecto.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
            integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../css/estilo.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
        <title>TrueChange</title>
    </head>

    <body>






        <header class="header">

            <div class="px-2 py-1 bg-opacity-30 bg-info bg-gradient text-white contenedor">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <img src="../imagenes/icono_proyecto.png" alt="icono de la aplicacion" width="100" height="100">

                        <div class="header-center-action">
                            <button type="button"
                                class="btn-truechange-main"
                                data-bs-toggle="modal"
                                data-bs-target="#subirModal">
                                <div class="btn-content">
                                    <i class="fa fa-refresh me-2 animate-spin-slow"></i>
                                    <span class="text-uppercase fw-bold">Nuevo Intercambio</span>
                                </div>
                                <div class="flare"></div>
                            </button>
                        </div>

                        <div class="dropdown"> <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src=<?php echo ($_SESSION['avatar']) ?> alt="Foto de perfil " width="60" height="60" class="rounded-circle me-2">
                                <strong> <?php print($_SESSION['usuarioNombre']) ?></strong> </a>


                            <ul class="dropdown-menu text-small shadow" style="z-index: 2000;">
                                <li><a class="btn dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#avatarModal">Cambiar foto de perfil</a></li>
                                <li><a class="btn dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#subirModal">Subir producto</a></li>
                                <li><a class="btn dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#perfilUsuario" id="botonPerfil">Mi perfil</a></li>
                                <li>
                                    <a class="dropdown-item" data-id="<?php echo $id_usuario ?>" href="javascript:void(0)" id="btn-valoraciones">
                                        Mis valoraciones
                                    </a>
                                </li>

                                <hr class="dropdown-divider">
                                </li>
                                <li class="px-3"> <a class="dropdown-item text-center text-danger fw-bold rounded-2 border border-danger-subtle mt-1"
                                        href="javascript:void(0)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#logoutModal">
                                        <i class="fa fa-power-off me-1"></i> Cerrar Sesión
                                    </a>
                                </li>

                            </ul>

                        </div>

                        <!--Modal para mostrar el perfil del usuario-->

                        <div class="modal fade" id="perfilUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Perfil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="idPerfil">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de cambio de avatar -->

                        <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Foto de perfil</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="cambiarAvatar.php" method="post" enctype="multipart/form-data">

                                            <div class="col-md-12">
                                                <label for="fileToUpload" class="form-label">Selecciona una imagen para subir:</label>
                                                <input type="file" class="form-control" id="fileToUpload" name="fileToUpload">
                                            </div>

                                            <div class="modal-footer">
                                                <input type="submit" class="btn btn-primary" value="Subir Imagen" name="submit">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal subir producto-->

                        <div class="modal fade" id="subirModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg">

                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel">
                                            <i class="bi bi-plus-circle-fill me-2 text-primary"></i>Nuevo Anuncio
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-4">
                                        <form id="formSubir" class="row g-3" action="subirArticulo.php" method="post" enctype="multipart/form-data">

                                            <div class="col-md-8">
                                                <label for="titulo" class="form-label fw-semibold text-secondary">Título del artículo</label>
                                                <input type="text" class="form-control form-control-lg shadow-sm" id="titulo" name="titulo" placeholder="Ej: Bicicleta de montaña casi nueva" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold text-secondary">Categoría</label>
                                                <select class="form-select form-select-lg shadow-sm" name="categoria" required>
                                                    <option selected disabled value="">Elegir...</option>
                                                    <option value="Coches">Coches</option>
                                                    <option value="Motos">Motos</option>
                                                    <option value="Motor y accesorios">Motor y accesorios</option>
                                                    <option value="Moda y accesorios">Moda y accesorios</option>
                                                    <option value="inmobiliaria">Inmobiliaria</option>
                                                    <option value="Tecnología y electrónica">Tecnología y electrónica</option>
                                                    <option value="Deporte y ocio">Deporte y ocio</option>
                                                    <option value="Bicicletas">Bicicletas</option>
                                                    <option value="Hogar y jardín">Hogar y jardín</option>
                                                    <option value="Electrodomésticos">Electrodomésticos</option>
                                                    <option value="Cine libros y música">Cine libros y música</option>
                                                    <option value="Niños y bebés">Niños y bebés</option>
                                                    <option value="Coleccionismo">Coleccionismo</option>
                                                    <option value="Construcción y reformas">Construcción y reformas</option>
                                                    <option value="Industria agricultura">Industria y agricultura</option>
                                                    <option value="Empleo">Empleo</option>
                                                    <option value="Servicios">Servicios</option>
                                                    <option value="Otros">Otros...</option>
                                                </select>
                                            </div>

                                            <div class="col-12 my-3">
                                                <label class="form-label fw-semibold text-secondary d-block">Estado del artículo</label>
                                                <div class="btn-group w-100 shadow-sm" role="group" aria-label="Estado del artículo">
                                                    <input type="radio" class="btn-check" name="estado" id="nuevo" value="nuevo">
                                                    <label class="btn btn-outline-primary" for="nuevo">Nuevo</label>

                                                    <input type="radio" class="btn-check" name="estado" id="como-nuevo" value="como nuevo">
                                                    <label class="btn btn-outline-primary" for="como-nuevo">Semi-nuevo</label>

                                                    <input type="radio" class="btn-check" name="estado" id="usado" value="usado" checked>
                                                    <label class="btn btn-outline-primary" for="usado">Usado</label>

                                                    <input type="radio" class="btn-check" name="estado" id="deteriorado" value="deteriorado">
                                                    <label class="btn btn-outline-primary" for="deteriorado">Deteriorado</label>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label for="descripcion" class="form-label fw-semibold text-secondary">Descripción</label>
                                                <textarea id="descripcion" class="form-control shadow-sm" name="descripcion" rows="3" placeholder="Cuéntanos más sobre el producto..."></textarea>
                                            </div>

                                            <div class="col-12">
                                                <label for="cambio" class="form-label fw-semibold text-secondary">¿Qué buscas a cambio?</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white shadow-sm"><i class="bi bi-arrow-left-right text-primary"></i></span>
                                                    <input type="text" class="form-control shadow-sm" id="cambio" name="cambio" placeholder="Ej: Una tablet o material de oficina">
                                                </div>
                                            </div>

                                            <div class="col-12 mt-3">
                                                <label for="foto" class="form-label fw-semibold text-secondary">Imagen del producto</label>
                                                <div class="upload-container border border-dashed rounded-3 p-4 text-center bg-light shadow-sm">
                                                    <i class="bi bi-cloud-arrow-up fs-2 text-primary"></i>
                                                    <input type="file" name="foto" id="foto" class="form-control mt-2" required>
                                                    <small class="text-muted d-block mt-1">Formatos sugeridos: JPG, PNG (Máx. 5MB)</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer border-0 mt-4 px-0 pb-0">
                                                <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" name="publicar">Publicar Anuncio</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


        </header>

        <!-- Barra de navegación-->
        <div class="barraNavegacion mx-auto  bg-white">

            <nav class="navbar navbar-expand-lg ">
                <div class="collapse navbar-collapse" id="navbarSearch">
                    <form onsubmit="aplicarFiltroYcategoria(event)" class="d-flex w-100 mx-auto bg-white rounded-pill shadow-sm border border-primary-subtle p-1 buscador-pro" style="max-width: 800px;">
                        <div class="input-group align-items-center">

                            <select class="form-select border-0 bg-transparent fw-medium text-secondary" id="searchCategory" style="max-width: 200px; cursor: pointer; box-shadow: none;">
                                <option value="todas" selected>Todas las categorías</option>
                                <option value="Coches">Coches</option>
                                <option value="Motos">Motos</option>
                                <option value="Motor y accesorios">Motor y accesorios</option>
                                <option value="Moda y accesorios">Moda y accesorios</option>
                                <option value="inmobiliaria">Inmobiliaria</option>
                                <option value="Tecnología y electrónica">Tecnología y electrónica</option>
                                <option value="Deporte y ocio">Deporte y ocio</option>
                                <option value="Bicicletas">Bicicletas</option>
                                <option value="Hogar y jardín">Hogar y jardín</option>
                                <option value="Electrodomésticos">Electrodomésticos</option>
                                <option value="Cine libros y música">Cine libros y música</option>
                                <option value="Niños y bebés">Niños y bebés</option>
                                <option value="Coleccionismo">Coleccionismo</option>
                                <option value="Construcción y reformas">Construcción y reformas</option>
                                <option value="Industria agricultura">Industria y agricultura</option>
                                <option value="Empleo">Empleo</option>
                                <option value="Servicios">Servicios</option>
                                <option value="Otros">Otros...</option>
                            </select>

                            <div class="vr bg-secondary opacity-25" style="width: 2px; height: 25px; margin: 0 10px;"></div>

                            <input id="campoFiltro" class="form-control border-0 bg-transparent text-dark" type="search" placeholder="¿Qué buscas? Ej: iPhone" aria-label="Search" style="box-shadow: none;">

                            <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" type="submit" style="transition: all 0.2s;">
                                <i class="fa fa-search me-1"></i> <span class="d-none d-sm-inline">Buscar</span>
                            </button>
                            <button class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm b-2" type="reset" style="transition: all 0.2s;">
                                <i class="fa fa-trash me-1"></i> <span class="d-none d-sm-inline" onclick="location.reload()">Limpiar</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                
                
                <div class="chat-nav-wrapper ms-3 me-3">
    <a class="chat-nav-button" href="../../public/Chat/listado_chats.php" title="Mensajes">
        <i class="fa fa-envelope-o chat-nav-button-icon"></i>
        <span class="chat-nav-button-text">Mensajes</span>
        <span id="notif-badge" class="chat-nav-button-badge">0</span>
    </a>
</div>
                
                
            </div>
        </div>
        </nav>

        </div>
        </div>

        <!-- Hasta aquí la barra de navegación-->

        <div class="d-flex bg-body-tertiary ">
            <div id="contenedor-busqueda" class="p-3 bg-body-tertiary col-lg-6 col-12 transition-all shadow-sm">
                <h3 id="titulo-busqueda" class="h5 mb-3">Búsqueda avanzada</h3>

                <div id="form-layout" class="row g-2">
                    <div class="col-12 campo-busqueda">
                        <label class="form-label small fw-bold">¿Qué buscas?</label>
                        <input type="text" id="buscador-general" class="form-control form-control-sm" placeholder="Ej: Bicicleta...">
                    </div>
                    <div class="col-12 campo-busqueda">
                        <label class="form-label small fw-bold">¿Qué ofreces?</label>
                        <input type="text" id="buscador-cambio" class="form-control form-control-sm" placeholder="Ej: Guitarra...">
                    </div>
                    <div class="col-12 campo-busqueda">
                        <label class="form-label small fw-bold">¿Lugar donde buscar?</label>
                        <input type="text" id="buscador-ciudad" class="form-control form-control-sm" placeholder="Ej: Logroño...">
                    </div>
                    <div class="col-12 campo-busqueda">
                        <label class="form-label small fw-bold">Orden:</label>
                        <select id="filtro-orden" class="form-select form-select-sm">
                            <option value="reciente">Más recientes</option>
                            <option value="nombre_asc">A - Z</option>
                        </select>
                    </div>
                </div>


                <hr>
            </div>


            <div id="carouselExampleInterval" class="carousel slide text-center flex-shrink-0 p-3 bg-body-tertiary col-lg-6 d-none d-lg-block" data-bs-ride="carousel">
                <div class="carousel-inner text-center">
                    <div class="carousel-item active" data-bs-interval="10000">
                        <img src="../imagenes/manos.jpg" class="d-block w-100 img-fluid" alt="Intercambio">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="../imagenes/electronica.jpg" class="d-block w-100 img-fluid" alt="Electrónica">
                    </div>
                    <div class="carousel-item">
                        <img src="../imagenes/moto.jpg" class="d-block w-100 img-fluid" alt="Motor">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>

        </div>
        <!--  Añadimos las cards con los articulos guardados-->

        <div class="container my-5 contenedor1 row" id="articulos">
            <h1 id="titulo1">Artículos</h1>
            <div class="row" id="resultados">
            </div>
        </div>

        <div class="container my-5">
            <h2>Mis artículos</h2>
            <div id="contenedor-mis-productos" class="row">
            </div>
        </div>



        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-primary text-black">
                    <strong class="me-auto">Nuevo Mensaje</strong>
                    <small>Ahora mismo</small>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ¡Tienes un nuevo mensaje sobre un artículo!
                    <br>
                    <a href="chat.php" class="btn btn-sm btn-outline-primary mt-2">Ir al chat</a>
                </div>
            </div>
        </div>



        <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">

                    <div class="modal-header bg-light border-0 py-3">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                            <i class="fa fa-pencil-square-o text-primary me-2"></i> Editar Artículo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <form id="formEditarProducto">
                            <input type="hidden" id="edit_id">

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Título del anuncio</label>
                                <input type="text" class="form-control form-control-lg shadow-sm border-2" id="edit_titulo" placeholder="Ej. Cámara Reflex Nikon" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary small text-uppercase">Descripción detallada</label>
                                <textarea class="form-control shadow-sm border-2" id="edit_descripcion" rows="4" placeholder="Describe el estado actual..." required></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small text-uppercase">Categoría</label>
                                    <select class="form-select shadow-sm border-2" id="edit_categoria">
                                        <option value="Coches">Coches</option>
                                        <option value="Motos">Motos</option>
                                        <option value="Motor y accesorios">Motor y accesorios</option>
                                        <option value="Moda y accesorios">Moda y accesorios</option>
                                        <option value="inmobiliaria">Inmobiliaria</option>
                                        <option value="Tecnología y electrónica">Tecnología y electrónica</option>
                                        <option value="Deporte y ocio">Deporte y ocio</option>
                                        <option value="Bicicletas">Bicicletas</option>
                                        <option value="Hogar y jardín">Hogar y jardín</option>
                                        <option value="Electrodomésticos">Electrodomésticos</option>
                                        <option value="Cine libros y música">Cine libros y música</option>
                                        <option value="Niños y bebés">Niños y bebés</option>
                                        <option value="Coleccionismo">Coleccionismo</option>
                                        <option value="Construcción y reformas">Construcción y reformas</option>
                                        <option value="Industria agricultura">Industria y agricultura</option>
                                        <option value="Empleo">Empleo</option>
                                        <option value="Servicios">Servicios</option>
                                        <option value="Otros">Otros...</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-secondary small text-uppercase">Estado actual</label>
                                    <select class="form-select shadow-sm border-2" id="edit_estado">
                                        <option value="nuevo">Nuevo</option>
                                        <option value="como nuevo">Como nuevo</option>
                                        <option value="usado">Usado</option>
                                        <option value="deteriorado">Deteriorado</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-link text-muted text-decoration-none fw-semibold" data-bs-dismiss="modal">Descartar</button>
                        <button type="button" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow" onclick="guardarCambios()">
                            Actualizar Artículo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSeleccionarComprador" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">¡Enhorabuena por el intercambio!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Para cerrar el trato, indícanos quién fue el afortunado:</p>

                        <div class="mb-3">
                            <label for="select-comprador" class="form-label fw-bold">He hecho trato con:</label>
                            <select class="form-select" id="select-comprador">
                                <option value="" selected disabled>Cargando usuarios...</option>
                            </select>
                        </div>
                        <p class="small text-muted"><i class="fa fa-info-circle"></i> Esto servirá para que podáis intercambiar reseñas.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" onclick="confirmarVenta()">Confirmar trato</button>
                    </div>
                </div>
            </div>
        </div>


        <footer class="pie bg-dark text-white pt-5 pb-4 mt-5">
            <div class="container text-center text-md-start">
                <div class="row text-center text-md-start">

                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-primary">TrueChange</h5>
                        <p>La plataforma líder para el intercambio de artículos. Fomentando la economía circular y el consumo responsable.</p>
                    </div>

                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Explorar</h5>
                        <p><a href="#" class="text-white text-decoration-none">Todos los artículos</a></p>
                        <p><a href="#" class="text-white text-decoration-none">Mis Favoritos</a></p>
                        <p><a href="#" class="text-white text-decoration-none">Mis Publicaciones</a></p>
                    </div>

                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Ayuda</h5>
                        <p><a href="../../public/funcionamiento.html" class="text-white text-decoration-none">Cómo funciona</a></p>
                        <p><a href="../../public/normas.html" class="text-white text-decoration-none">Reglas y normas</a></p>
                        <p><a href="../../public/contacto.html" class="text-white text-decoration-none">Contacto</a></p>
                        <p><a href="../../public/test.html" class="text-white text-decoration-none">Developers</a></p>
                    </div>

                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Síguenos</h5>
                        <div class="d-flex justify-content-center justify-content-md-start">
                            <a href="#" class="text-white me-4"><i class="fa fa-facebook fa-lg"></i></a>
                            <a href="#" class="text-white me-4"><i class="fa fa-twitter fa-lg"></i></a>
                            <a href="#" class="text-white me-4"><i class="fa fa-instagram fa-lg"></i></a>
                            <a href="#" class="text-white"><i class="fa fa-linkedin fa-lg"></i></a>
                        </div>
                    </div>
                </div>

                <hr class="mb-4 mt-5">

                <div class="row align-items-center">
                    <div class="col-md-7 col-lg-8">
                        <p>© 2025 Copyright: <strong class="text-primary">TrueChange.com</strong> - Dale una segunda vida a tus cosas.</p>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <div class="text-center text-md-end">
                            <small>Desarrollado con <i class="fa fa-heart text-danger"></i> para un mundo sostenible.</small>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="modal fade" id="modalReseña" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">¡Valora el intercambio!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="fw-bold">¿Cuántas estrellas le das?</p>

                        <div class="estrellas-rating mb-3" style="font-size: 2.5rem; color: #ffc107; cursor: pointer;">
                            <i class="fa fa-star-o" data-value="1"></i>
                            <i class="fa fa-star-o" data-value="2"></i>
                            <i class="fa fa-star-o" data-value="3"></i>
                            <i class="fa fa-star-o" data-value="4"></i>
                            <i class="fa fa-star-o" data-value="5"></i>
                        </div>

                        <input type="hidden" id="puntuacion-valor" value="0">

                        <div class="form-group">
                            <textarea id="comentario-reseña" class="form-control" rows="3" placeholder="Escribe tu opinión aquí..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary w-50" onclick="enviarResena()">Enviar Opinión</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalVerOpiniones" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title"><i class="fa fa-comments-o"></i> Opiniones sobre el usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="lista-opiniones" class="list-group list-group-flush">
                            <p class="text-center text-muted">Cargando opiniones...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Pasamos el ID del usuario de la sesión PHP a una variable Global de JS
            const usuarioLogueadoId = "<?php echo $_SESSION['id_usuario']; ?>";
        </script>
        <script src="../scripts/script.js"></script>

        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fa fa-sign-out fa-4x text-primary opacity-25"></i>
                        </div>
                        <h5 class="fw-bold text-dark">¿Cerrar sesión?</h5>
                        <p class="text-muted small">Estás a punto de salir de TrueChange. ¡Esperamos verte pronto!</p>

                        <div class="d-grid gap-2 d-md-block mt-4">
                            <button type="button" class="btn btn-light px-4 fw-medium border" data-bs-dismiss="modal">Cancelar</button>
                            <a href="logout.php" class="btn btn-primary px-4 fw-bold btn-blue-grad">Sí, salir</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>


    </html>

<?php
} else {

    echo "<script>
            alert('Regístrese para acceder.');
            window.location.href = 'index.php';
          </script>";
    exit();
}
?>