<nav class="navbar fixed-top navbar-expand-lg bg-black">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse justify-content-between navbar-collapse align-items-center" id="navbarSupportedContent">
            <div class="justify-content-start d-flex align-items-center flex-nowrap">
                <?php $app->renderPartial('breadcrumbs/breadcrumbs'); ?>
            </div>
            <div class="justify-content-end d-flex">
                <form class="d-flex flex-nowrap" role="search">

                    <input class="form-control text-nowrap me-2 bg-search-bg text-secondary search-input" type="search" placeholder="Zoek relaties..."
                           aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">Zoek</button>

                </form>
                <a href="#" class="btn btn-outline-secondary text-nowrap ms-4">Geavanceerd zoeken</a>
                <div class="vr text-secondary-200 ms-4"></div>

                <ul class="mb-2 gear-icon-padding me-4 mb-lg-0">
                    <li class="nav-item gap-2 d-flex flex-row">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-gear-fill"></i></button>
                        <div class="position-relative">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mijn instellingen</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-left me-2"></i>Uitloggen</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
