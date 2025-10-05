<!-- Start Footer -->
<footer class="bg-dark" id="tempaltemo_footer">
    <div class="container">
        <div class="row">

            <div class="col-md-4 pt-5">
                <h2 class="h2 text-success border-bottom pb-3 border-light logo">Zay Shop</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li>
                        <i class="fas fa-map-marker-alt fa-fw"></i>
                        123 Consectetur at ligula 10660
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <a class="text-decoration-none" href="tel:010-020-0340">010-020-0340</a>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <a class="text-decoration-none" href="mailto:info@zayshop.com">info@zayshop.com</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4 pt-5">
                <h2 class="h2 text-light border-bottom pb-3 border-light">Productos</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li><a class="text-decoration-none" href="<?php echo View::url('products'); ?>">Todos los Productos</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('products?category=men'); ?>">Hombres</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('products?category=women'); ?>">Mujeres</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('products?category=shoes'); ?>">Zapatos</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('products?category=accessories'); ?>">Accesorios</a></li>
                </ul>
            </div>

            <div class="col-md-4 pt-5">
                <h2 class="h2 text-light border-bottom pb-3 border-light">Información</h2>
                <ul class="list-unstyled text-light footer-link-list">
                    <li><a class="text-decoration-none" href="<?php echo View::url(); ?>">Inicio</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('about'); ?>">Acerca de Nosotros</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('contact'); ?>">Contacto</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('terms'); ?>">Términos y Condiciones</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('privacy'); ?>">Política de Privacidad</a></li>
                    <li><a class="text-decoration-none" href="<?php echo View::url('faq'); ?>">Preguntas Frecuentes</a></li>
                </ul>
            </div>

        </div>

        <div class="row text-light mb-4">
            <div class="col-12 mb-3">
                <div class="w-100 my-3 border-top border-light"></div>
            </div>
            <div class="col-auto me-auto">
                <ul class="list-inline text-left footer-icons">
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="http://facebook.com/">
                            <i class="fab fa-facebook-f fa-lg fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="https://www.instagram.com/">
                            <i class="fab fa-instagram fa-lg fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="https://twitter.com/">
                            <i class="fab fa-twitter fa-lg fa-fw"></i>
                        </a>
                    </li>
                    <li class="list-inline-item border border-light rounded-circle text-center">
                        <a class="text-light text-decoration-none" target="_blank" href="https://www.linkedin.com/">
                            <i class="fab fa-linkedin fa-lg fa-fw"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-auto">
                <label class="sr-only" for="subscribeEmail">Email</label>
                <form action="<?php echo View::url('newsletter/subscribe'); ?>" method="post" class="d-flex">
                    <input type="hidden" name="csrf_token" value="<?php echo View::csrf(); ?>">
                    <div class="input-group mb-2">
                        <input type="email" class="form-control bg-dark border-light" id="subscribeEmail" name="email" placeholder="Email" required>
                        <div class="input-group-text btn-success text-light">
                            <button type="submit" class="btn btn-success btn-sm">Suscribirse</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="w-100 bg-black py-3">
        <div class="container">
            <div class="row pt-2">
                <div class="col-12">
                    <p class="text-left text-light">
                        Copyright &copy; <?php echo date('Y'); ?> Zay Shop 
                        | Desarrollado con ❤️ para nuestros clientes
                    </p>
                </div>
            </div>
        </div>
    </div>

</footer>
<!-- End Footer -->