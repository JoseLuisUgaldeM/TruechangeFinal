<?php



require_once "../src/clases/Usuario.php";

require_once "../src/clases/Articulos.php";

require_once "../src/php/crearFicheroJson.php";

session_start();


$database = new Database();

$db = $database->getConnection();

$usuario = new Usuario($database);

creaYactualiza($usuario);


$todos = $usuario->listarUsuarios();


header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <link rel="stylesheet" href="../src/css/estilo.css">
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

<body>

    <div class="loader">
        <div class="spinner">
        </div>
        <p>Cargando...</p>
    </div>

    <?php


    ?>
    <header class="header">

        <div class="px-2 py-1 bg-opacity-30 bg-info bg-gradient text-white contenedor">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-around">
                    <img src="../src/imagenes/icono_proyecto.png" alt="icono de la aplicacion" width="100" height="100">


                    <div class="botones">
                        <button id="botonIniciarSesion" type="button" class="btn btn-light text-dark me-2" data-bs-toggle="modal"
                            data-bs-target="#Modal">Inicio sesión</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Registrarme</button>
                    </div>


                    <!-- Modal registro-->


                   <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"> <div class="modal-content border-0 shadow-lg rounded-4">
            
            <div class="modal-header bg-primary text-white rounded-top-4 p-4" style="background: linear-gradient(45deg, #0d6efd, #003d92);">
                <div class="text-center w-100">
                    <i class="fa fa-user-plus fa-3x mb-2"></i>
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">Únete a TrueChange</h5>
                    <p class="small mb-0 opacity-75">Crea tu cuenta y empieza a intercambiar hoy mismo</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form class="row g-3" action="../src/php/registro.php" method="post">
                    
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-bold text-muted small">NOMBRE</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="nombre" name="nombre" placeholder="Tu nombre" pattern="^[a-zA-ZáéíóúÁÉÍÓÚ ]+$" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="primerApellido" class="form-label fw-bold text-muted small">PRIMER APELLIDO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-user-o"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="primerApellido" name="primerApellido" placeholder="Primer apellido" pattern="^[a-zA-ZáéíóúÁÉÍÓÚ ]+$" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="segundoApellido" class="form-label fw-bold text-muted small">SEGUNDO APELLIDO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-user-o"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="segundoApellido" name="segundoApellido" placeholder="Segundo apellido" pattern="^[a-zA-ZáéíóúÁÉÍÓÚ ]+$" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="usuarioNombre" class="form-label fw-bold text-muted small">NOMBRE DE USUARIO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-at"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="usuarioNombre" name="usuarioNombre" placeholder="Usuario123" pattern="^[a-zA-Z0-9]+$" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold text-muted small">CORREO ELECTRÓNICO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-envelope"></i></span>
                            <input type="email" class="form-control border-start-0 bg-light" id="email" name="email" placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="pass" class="form-label fw-bold text-muted small">CONTRASEÑA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control border-start-0 bg-light" id="pass" name="pass" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="ciudad" class="form-label fw-bold text-muted small">CIUDAD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa fa-map-marker"></i></span>
                            <input type="text" class="form-control border-start-0 bg-light" id="ciudad" name="ciudad" placeholder="Tu ciudad actual" required>
                        </div>
                    </div>

                    <div class="col-12 my-3">
                        <div class="form-check">
                            <input class="form-check-input border-primary" type="checkbox" value="" id="invalidCheck2" required>
                            <label class="form-check-label small text-muted" for="invalidCheck2">
                                Acepto los <a href="#" class="text-primary fw-bold text-decoration-none">términos de uso</a> y la <a href="#" class="text-primary fw-bold text-decoration-none">política de privacidad</a>.
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-3 btn-blue-grad" name="enviar" type="submit">
                            <i class="fa fa-check-circle me-2"></i> FINALIZAR REGISTRO
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



</div>
</div>
</div>


</header>


<!-- Barra de navegación-->


<div class="barraNavegacion mx-auto ">
    
    <nav class="barraNavegacion mx-auto sticky-top bg-white shadow-sm">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
            aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-item nav-underline">
               <li class="nav-item">
    <select class="form-select me-2 shadow-sm custom-nav-select" id="nav-categoria">
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
</li>
                
                
                <li class="nav-item ">
                    <a class="nav-link" href="#">Electronica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Informática</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hogar</a>
                </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Coches</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Motos</a>
                        </li>
                    </ul>
                </div>
                <form class="d-flex col-lg-5 col-md-8 col-sm-9" role="search">
                    <input id="campoFiltro" class="form-control me-2 text-primary" type="search" placeholder="Ej: Iphone" aria-label="Search">
                    <button onclick="aplicarFiltro(event)" class="btn btn-outline-primary" type="submit" id="btn-buscar">Buscar</button>
                    
                </form>
            </div>
        </nav>
        
    </div>
</div>
<!-- Hasta aquí la barra de navegación-->


<!-- Mostramos el carrusel de fotos-->


<div class="container">
    
    <div class="d-flex bg-body-tertiary container d-flex row text-center my-5">
        <div id="carouselExampleInterval" class="carousel slide d-flex flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12" data-bs-ride="carousel">
            <div class="carousel-inner text-center">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="../src/imagenes/manos.jpg" class="d-block w-100 img-fluid " alt="Foto de ROMAN ODINTSOV: https://www.pexels.com/es-es/foto/manos-sujetando-cartulina-carton-12725405/">
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="../src/imagenes/electronica.jpg" class="d-block w-100 img-fluid" alt="Foto de ATC Comm Photo: https://www.pexels.com/es-es/foto/primer-plano-de-la-camara-sobre-fondo-negro-306763/">
                </div>
                <div class="carousel-item">
                    <img src="../src/imagenes/moto.jpg" class="d-block w-100 img-fluid" alt="Foto de Pragyan Bezbaruah: https://www.pexels.com/es-es/foto/motocicleta-en-medio-de-la-carretera-1715193/">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary col-lg-6 col-sm-12">
            
            <span class="fs-4 text-center">TRUECHANGE</span>
            <hr>
            <p>TrueChange es una aplicación pensada para dar una segunda vida a los objetos que ya no usas, conectando personas que quieren intercambiar artículos de forma sencilla, visual y segura. Desde tecnología y hogar hasta deporte o moda, la plataforma permite descubrir productos, contactar con otros usuarios y gestionar cambios de manera cómoda, fomentando un consumo más inteligente, económico y sostenible.</p>
            
            <hr>
        </div>
    </div>
</div>
<!-- Mostramos una introduccion acerca de la aplicacion -->


    <!-- Mostramos los articulos-->
    
    <div class="container my-5 contenedor1" id="articulos">
        <h1 id="titulo1">Artículos</h1>
        <div class="row" id="resultados">
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
                        <p><a href="#" class="text-white text-decoration-none" onclick="mostrarTodos()">Todos los artículos</a></p>
                        <p><a href="#" class="text-white text-decoration-none" onclick="mostrarFavoritos()">Mis Favoritos</a></p>
                        <p><a href="#" class="text-white text-decoration-none" onclick="mostrarMisProductos()">Mis Publicaciones</a></p>
                    </div>
                    
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Ayuda</h5>
                        <p><a href="funcionamiento.html" class="text-white text-decoration-none">Cómo funciona</a></p>
                        <p><a href="normas.html" class="text-white text-decoration-none">Reglas y normas</a></p>
                        <p><a href="#" class="text-white text-decoration-none">Contacto</a></p>
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
                            <small class="text-muted">Desarrollado con <i class="fa fa-heart text-danger"></i> para un mundo sostenible.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </footer>
        
        <script>
        window.onload = function() {
            const loader = document.querySelector('.loader');
            loader.style.transition = 'opacity 0.5s ease'; // Transición para el desvanecimiento
            loader.style.opacity = '0'; // Hace que el loader se desvanezca
            
            // Opcional: Eliminar el loader del DOM después de la transición
            setTimeout(() => {
                loader.remove();
            }, 500);
        };
        const usuarioLogueadoId = <?php echo isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 'null'; ?>;
        </script>

<!-- Modal inicio de sesión-->

<div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content border-0 shadow-lg rounded-4"> 
            
            <div class="modal-header bg-primary text-white rounded-top-4 p-4" style="background: linear-gradient(45deg, #0d6efd, #003d92);">
                <div class="text-center w-100">
                    <i class="fa fa-refresh fa-3x mb-2"></i> <h5 class="modal-title fw-bold" id="exampleModalLabel">Bienvenido a TrueChange</h5>
                    <p class="small mb-0 opacity-75">Tu plataforma de intercambio seguro</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form method="post" action="../src/php/login.php" class="row g-3">
                    
                    <div class="col-md-12">
                        <label for="usuario" class="form-label fw-bold text-muted small">USUARIO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" name="usuario" class="form-control border-start-0 bg-light" id="usuario" placeholder="Nombre de usuario" pattern="^[a-zA-Z\s]+$" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="contrasena" class="form-label fw-bold text-muted small">CONTRASEÑA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input type="password" name="pass" class="form-control border-start-0 bg-light" id="contrasena" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <a href="#" class="text-decoration-none small fw-medium text-primary">¿Problemas para entrar?</a>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <button class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3 btn-blue-grad" type="submit" name="iniciarSesion">
                            <i class="fa fa-sign-in me-2"></i> Acceder ahora
                        </button>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <p class="small mb-0 text-muted">¿Eres nuevo aquí? <a href="#" data-bs-toggle="modal" 
                        data-bs-target="#exampleModal"class="fw-bold text-primary text-decoration-none">Crea una cuenta</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        
    </div>
</div>
</div>
<script src="../src/scripts/script.js"></script>
</body>

</html>