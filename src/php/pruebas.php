<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pruebas TrueChange</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            background: #f4f7fb;
            font-family: Arial, sans-serif;
        }

        .hero {
            background: linear-gradient(45deg, #0d6efd, #003d92);
            color: white;
            padding: 45px 0;
            text-align: center;
        }

        .card-pruebas {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .badge-ok {
            background: #e8f8ef;
            color: #198754;
            padding: 8px 14px;
            border-radius: 50px;
            font-weight: bold;
        }

        iframe {
            width: 100%;
            min-height: 420px;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            background: white;
        }
    </style>
</head>

<body>

    <section class="hero">
        <div class="container">
            <i class="fa fa-flask fa-3x mb-3"></i>
            <h1 class="fw-bold">Panel de pruebas TrueChange</h1>
            <p class="mb-0">Carga de usuarios, artículos y batería de comprobaciones</p>
        </div>
    </section>

    <main class="container my-5">

        <?php
        session_start();
         if (isset($_GET['borrado']) && $_GET['borrado'] === 'ok') { ?>
            <div class="alert alert-success">
                Datos de prueba borrados correctamente.
            </div>
        <?php } ?>

        <?php if (isset($_GET['borrado']) && $_GET['borrado'] === 'error') { ?>
            <div class="alert alert-danger">
                Error al borrar los datos de prueba.
          
        <?php } ?>

         <?php if (isset($_GET['datos']) && $_GET['datos'] === 'ok') { ?>
            <div class="alert alert-success">
                Datos de prueba cargados correctamente.
            </div>
        <?php } ?>

        <?php if (isset($_GET['datos']) && $_GET['datos'] === 'error') { ?>
            <div class="alert alert-danger">
                Error al cargar los datos de prueba.
            </div>
        <?php } ?>
        <div class="card card-pruebas mb-4">
            <div class="card-body p-4">
                <h3 class="fw-bold text-primary mb-3">
                    <i class="fa fa-database me-2"></i>Carga de datos de prueba
                </h3>

                <form action="cargar_datos_test.php" method="post" class="mt-4">
                    <button type="submit" name="cargar" class="btn btn-primary  fw-bold rounded-pill px-4">
                        Cargar datos de prueba
                    </button>
                </form>


                <form action="borrar_pruebas_test.php" method="post" class="mt-4"
                    onsubmit="return confirm('¿Seguro que quieres borrar todos los datos de prueba?');">
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">
                        <i class="fa fa-trash me-2"></i>Borrar datos de prueba
                    </button>
                </form>
            </div>
        </div>

        <div class="card card-pruebas">
            <div class="card-body p-4">
                <h3 class="fw-bold text-primary mb-4">
                    <i class="fa fa-list-check me-2"></i>Batería de preguntas
                </h3>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Pregunta</th>
                                <th>Prueba</th>
                                <th>Resultado esperado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>¿Se pueden crear usuarios?</td>
                                <td>Ejecutar la carga de pruebas</td>
                                <td>Se crean varios usuarios vendedores</td>
                            </tr>
                            <tr>
                                <td>¿Se pueden crear artículos?</td>
                                <td>Comprobar los artículos insertados</td>
                                <td>Cada vendedor tiene varios artículos</td>
                            </tr>
                            <tr>
                                <td>¿Se muestran los artículos en la página principal?</td>
                                <td>Entrar a TrueChange</td>
                                <td>Aparecen las tarjetas de artículos</td>
                            </tr>
                            <tr>
                                <td>¿Funciona el buscador?</td>
                                <td>Buscar un artículo por nombre</td>
                                <td>Solo aparecen coincidencias</td>
                            </tr>
                            <tr>
                                <td>¿Funciona el filtro por ciudad?</td>
                                <td>Buscar por ciudad</td>
                                <td>Aparecen artículos de esa ciudad</td>
                            </tr>
                            <tr>
                                <td>¿Funciona el filtro “qué ofreces”?</td>
                                <td>Buscar por el campo cambio</td>
                                <td>Aparecen artículos compatibles</td>
                            </tr>
                            <tr>
                                <td>¿Se puede iniciar sesión?</td>
                                <td>Entrar con un usuario de prueba</td>
                                <td>Accede correctamente a la sesión</td>
                            </tr>
                            <tr>
                                <td>¿Se ven mis artículos?</td>
                                <td>Entrar como vendedor</td>
                                <td>Aparecen sus propios artículos</td>
                            </tr>
                            <tr>
                                <td>¿Se puede cambiar un artículo a reservado?</td>
                                <td>Pulsar reservado</td>
                                <td>El estado cambia correctamente</td>
                            </tr>
                            <tr>
                                <td>¿Se puede cerrar un intercambio?</td>
                                <td>Marcar artículo como cambiado</td>
                                <td>Pide seleccionar comprador</td>
                            </tr>
                            <tr>
                                <td>¿Se pueden guardar reseñas?</td>
                                <td>Finalizar intercambio</td>
                                <td>Aparece el modal de valoración</td>
                            </tr>
                            <tr>
                                <td>¿Se actualiza el JSON?</td>
                                <td>Actualizar datos y recargar la app</td>
                                <td>Los artículos aparecen actualizados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <a href="sesionIniciada.php" class="btn btn-primary fw-bold rounded-pill px-4 mt-3">
                    <i class="fa fa-arrow-right me-2"></i>Ir a TrueChange
                </a>
            </div>
        </div>

    </main>

</body>

</html>