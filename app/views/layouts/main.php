<!DOCTYPE html>
<html lang="es">
<head>
    <title><?php echo $title ?? 'Zay Shop eCommerce'; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $meta_description ?? 'Tienda en línea con los mejores productos'; ?>">

    <link rel="apple-touch-icon" href="<?php echo View::asset('img/apple-icon.png'); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo View::asset('img/favicon.ico'); ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo View::asset('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo View::asset('css/templatemo.css'); ?>">
    <link rel="stylesheet" href="<?php echo View::asset('css/custom.css'); ?>">

    <!-- Fuentes -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="<?php echo View::asset('css/fontawesome.min.css'); ?>">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo View::csrf(); ?>">
    
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo View::asset($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <!-- Top Nav -->
    <?php include 'navbar.php'; ?>
    
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Modal de búsqueda -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo View::url('search'); ?>" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Buscar productos..." value="<?php echo View::escape($search_query ?? ''); ?>">
                    <button class="input-group-text bg-success text-light" type="submit">
                        <i class="fa fa-fw fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contenido principal -->
    <main>
        <?php 
        // Mostrar mensajes flash
        if (Session::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo Session::flash('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo Session::flash('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (Session::hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo Session::flash('warning'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="<?php echo View::asset('js/jquery-1.11.0.min.js'); ?>"></script>
    <script src="<?php echo View::asset('js/jquery-migrate-1.2.1.min.js'); ?>"></script>
    <script src="<?php echo View::asset('js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo View::asset('js/templatemo.js'); ?>"></script>
    <script src="<?php echo View::asset('js/custom.js'); ?>"></script>

    <!-- Script para CSRF en AJAX -->
    <script>
        // Configurar token CSRF para peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Variables globales para JavaScript
        window.APP_URL = '<?php echo View::url(); ?>';
        window.ASSET_URL = '<?php echo View::asset(''); ?>';
    </script>

    <?php if (isset($additional_js)): ?>
        <?php foreach ($additional_js as $js): ?>
            <script src="<?php echo View::asset($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>
</body>
</html>