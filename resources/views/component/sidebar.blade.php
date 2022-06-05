<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html"
            target="_blank">
            <img src="soft-ui/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Inventory</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#collapseTransaction" role="button"
                    aria-expanded="false" aria-controls="collapseTransaction">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                    <span class="nav-link-text ms-1">Transaction</span>
                </a>
                <div class="collapse" id="collapseTransaction">
                    <ul class="navbar-nav card shadow-light ">
                        <li class="nav-item">
                            <a class="nav-link" href="/transaction">
                                <span class="nav-link-text">List of Stock</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/order">
                                <span class="nav-link-text">Order</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/itemin">
                                <span class="nav-link-text">Item In</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/itemout">
                                <span class="nav-link-text">Item Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @if (auth()->user()->job_title_id == 0 || auth()->user()->job_title_id == 1)
                <li class="nav-item">
                    <a class="nav-link" href="/item">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <span class="nav-link-text ms-1">Item</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/category">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-th"></i>
                        </div>
                        <span class="nav-link-text ms-1">Category</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/unit">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <span class="nav-link-text ms-1">Unit</span>
                    </a>
                </li>
                @if (auth()->user()->job_title_id == 0)
                    <li class="nav-item">
                        <a class="nav-link" href="/job-title">
                            <div
                                class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>
                            <span class="nav-link-text ms-1">Job Title</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user">
                            <div
                                class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <span class="nav-link-text ms-1">User / Employee</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="/supplier">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <span class="nav-link-text ms-1">Supplier</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/warehouse">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-warehouse"></i>
                        </div>
                        <span class="nav-link-text ms-1">Warehouse</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/customer">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <span class="nav-link-text ms-1">Customer</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
