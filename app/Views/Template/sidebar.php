<aside class="app-sidebar bg-body-secondary" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() . 'MainMenu'; ?>" class="brand-link">
            <img
                src="<?= base_url() . 'img/favicon.png'; ?>"
                alt="Schlemmer Indonesia"
                class="brand-image opacity-75" />
            <span class="brand-text fw-light">Schlemmer Indonesia</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-header">DASHBOARD</li>
                <li class="nav-item">
                    <a href="<?= base_url() . 'dashboard' ?>" class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-header">TRANSACTION</li>
                <li class="nav-item">
                    <a href="<?= base_url() . 'document' ?>" class="nav-link" onclick="loading();">
                        <i class="nav-icon bi bi-folder2-open"></i>
                        <p>
                            List of Project
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-vector-pen"></i>
                        <p>
                            Approval
                        </p>
                    </a>
                </li>
                <li class="nav-header">MASTER DATA</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <p>
                            Common Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('customer_category') ?> " class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Customer Category
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('customer') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Customers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('satuan') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of UoM
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('routes') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Routes
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('material_category') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    Material Category List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('workshop') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Workshop
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('material') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    List of Material
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>
                            APQP Setup
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('apqp_level') ?>" class="nav-link" onclick="loading()">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    APQP Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bezier2 nav-icon"></i>
                        <p>
                            Project Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'project'  ?>" class="nav-link">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    Project Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-data"></i>
                        <p>
                            Document Flow
                        </p>
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </a>
                    <ul class="nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'document-flow' ?>" class="nav-link">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    Setup Document Flow
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">APP SETUP</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-gear"></i>
                        <p>
                            User Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('users') ?>" class="nav-link" onclick="loading()">
                                <i class="nav-icon bi bi-arrow-right-circle"></i>
                                <p>
                                    User List
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-building"></i>
                        <p>
                            Master Data Seeder
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url() . 'seeder' ?>" class="nav-link" onlick="loading()">
                                <i class="bi bi-arrow-right-circle"></i>
                                <p>
                                    Master Data Seeder
                                </p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <li class="nav-item">
                    <a href="<?= base_url() . 'logout' ?>" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-left"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>