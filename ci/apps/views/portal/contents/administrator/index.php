            <div class="content mt-3">
                <div class="card-deck m-2 mb-4">
                    <div class="card text-white bg-flat-color-5">
                        <div class="card-body pb-0">
                            <div class="dropdown float-right">
                                <button class="btn bg-transparent dropdown-toggle theme-toggle text-light" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-menu-content">
                                        <a class="dropdown-item" href="<?=base_url()?>administrator/user">Manage Users</a>
                                        <a class="dropdown-item" href="#">Add User</a>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-0">
                                <span class="count"><?=$dashboard['total']?></span>
                            </h4>
                            <p class="text-light">Registered Users</p>
                            <div class="chart-wrapper px-0" style="height:70px;" height="70"></div>
                        </div>

                    </div>
                    <div class="card text-white bg-flat-color-4">
                        <div class="card-body pb-0">
                            <div class="dropdown float-right">
                                <button class="btn bg-transparent dropdown-toggle theme-toggle text-light" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-menu-content">
                                        <a class="dropdown-item" href="<?=base_url()?>administrator/role">Manage Roles</a>
                                        <a class="dropdown-item" href="#">Add Role</a>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-0">
                                <span class="count"><?=$dashboard['total']?></span>
                            </h4>
                            <p class="text-light">Roles</p>
                            <div class="chart-wrapper px-0" style="height:70px;" height="70"></div>
                        </div>

                    </div>
                    <div class="card text-white bg-flat-color-3">
                        <div class="card-body pb-0">
                            <div class="dropdown float-right">
                                <button class="btn bg-transparent dropdown-toggle theme-toggle text-light" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-menu-content">
                                        <a class="dropdown-item" href="<?=base_url()?>administrator/page">Manage Pages</a>
                                        <a class="dropdown-item" href="#">Add Page</a>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-0">
                                <span class="count"><?=$dashboard['total']?></span>
                            </h4>
                            <p class="text-light">Pages</p>
                            <div class="chart-wrapper px-0" style="height:70px;" height="70"></div>
                        </div>

                    </div>
                    <div class="card text-white bg-flat-color-2">
                        <div class="card-body pb-0">
                            <div class="dropdown float-right">
                                <button class="btn bg-transparent dropdown-toggle theme-toggle text-light" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="dropdown-menu-content">
                                        <a class="dropdown-item" href="<?=base_url()?>administrator/page">Manage Sites</a>
                                        <a class="dropdown-item" href="#">Add Site</a>
                                    </div>
                                </div>
                            </div>
                            <h4 class="mb-0">
                                <span class="count"><?=$dashboard['total']?></span>
                            </h4>
                            <p class="text-light">Sites</p>
                            <div class="chart-wrapper px-0" style="height:70px;" height="70"></div>
                        </div>

                    </div>
                </div>
            </div>