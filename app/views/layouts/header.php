<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light shadow">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand text-success logo h1 align-self-center" href="<?php echo View::url(); ?>">
            Zay
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
            <div class="flex-fill">
                <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo View::url(); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo View::url('about'); ?>">Acerca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo View::url('products'); ?>">Tienda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo View::url('contact'); ?>">Contacto</a>
                    </li>
                    
                    <?php if (Session::isAuthenticated()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Mi Cuenta
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo View::url('profile'); ?>">Perfil</a></li>
                            <li><a class="dropdown-item" href="<?php echo View::url('profile/orders'); ?>">Mis Pedidos</a></li>
                            <li><a class="dropdown-item" href="<?php echo View::url('wishlist'); ?>">Lista de Deseos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="<?php echo View::url('logout'); ?>" method="post" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo View::csrf(); ?>">
                                    <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="navbar align-self-center d-flex">
                <!-- Búsqueda móvil -->
                <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                    <form action="<?php echo View::url('search'); ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Buscar..." value="<?php echo View::escape($search_query ?? ''); ?>">
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Búsqueda desktop -->
                <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                    <i class="fa fa-fw fa-search text-dark mr-2"></i>
                </a>
                
                <!-- Carrito -->
                <a class="nav-icon position-relative text-decoration-none" href="<?php echo View::url('cart'); ?>" id="cart-icon">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                    <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark" id="cart-count">
                        <?php echo Session::get('cart_count', 0); ?>
                    </span>
                </a>
                
                <!-- Usuario -->
                <?php if (Session::isAuthenticated()): ?>
                    <a class="nav-icon position-relative text-decoration-none" href="<?php echo View::url('profile'); ?>">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                <?php else: ?>
                    <a class="nav-icon position-relative text-decoration-none" href="<?php echo View::url('login'); ?>" title="Iniciar Sesión">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<!-- Close Header -->