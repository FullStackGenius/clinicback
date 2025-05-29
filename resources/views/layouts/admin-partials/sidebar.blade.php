<aside class="main-sidebar sidebar-bg-dark sidebar-color-primary shadow">
    <div class="brand-container">
        <a href="javascript:;" class="brand-link">
            <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-80 shadow">
            <span class="brand-text fw-light">Admin Pannel</span>
        </a>
        <a class="pushmenu mx-1" data-lte-toggle="sidebar-mini" href="javascript:;" role="button"><i
                class="fas fa-angle-double-left"></i></a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <!-- Sidebar Menu -->
            <ul class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} ">

                        <i class='fa fa-tachometer-alt'></i>
                        <p>
                            Dashboard

                        </p>
                    </a>

                </li>
                <li class="nav-item {{ request()->routeIs('skill*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)" class="nav-link {{ request()->routeIs('skill*') ? 'active' : '' }} ">

                        <i class="fa fa-lightbulb"></i>
                        <p>
                            Skills
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('skill.index') }}"
                                class="nav-link {{ request()->routeIs('skill.index') || request()->routeIs('skill.edit') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>All Skill</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('skill.create') }}"
                                class="nav-link {{ request()->routeIs('skill.create') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>Add skill</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item  {{ request()->routeIs('category*') ? 'menu-open' : '' }}  ">
                    <a href="javascript:void(0)"
                        class="nav-link  {{ request()->routeIs('category*') ? 'active' : '' }}">

                        <i class="fa fa-layer-group"></i>
                        <p>
                            Category
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('category.index') }}"
                                class="nav-link {{ request()->routeIs('category.index') || request()->routeIs('category.edit') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>All Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category.create') }}"
                                class="nav-link {{ request()->routeIs('category.create') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>Add Category</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item  {{ request()->routeIs('subcategory*') ? 'menu-open' : '' }}  ">
                    <a href="javascript:void(0)"
                        class="nav-link  {{ request()->routeIs('subcategory*') ? 'active' : '' }}  ">

                        <i class='fa fa-tags'></i>
                        <p>
                            Sub Category
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('subcategory.index') }}"
                                class="nav-link {{ request()->routeIs('subcategory.index') || request()->routeIs('subcategory.edit') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>All Sub Category</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subcategory.create') }}"
                                class="nav-link {{ request()->routeIs('subcategory.create') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>Add Sub Category</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li
                    class="nav-item {{ request()->routeIs('your-experience*') || request()->routeIs('your-goal*') || request()->routeIs('how-to-like-work*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('your-experience*') || request()->routeIs('your-goal*') || request()->routeIs('how-to-like-work*') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-question-circle"></i>
                        <p>
                            Few Quick Questions
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('your-experience.index') }}"
                                class="nav-link {{ request()->routeIs('your-experience.index') || request()->routeIs('your-experience*') ? 'active' : '' }} ">

                                <i class="nav-icon fa fa-trophy"></i>
                                <p>Your Experience</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('your-goal.index') }}"
                                class="nav-link {{ request()->routeIs('your-goal.index') || request()->routeIs('your-goal*') ? 'active' : '' }} ">

                                <i class="nav-icon fa fa-bullseye"></i>
                                <p> Your Goal</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('how-to-like-work.index') }}"
                                class="nav-link {{ request()->routeIs('how-to-like-work.index') || request()->routeIs('how-to-like-work*') ? 'active' : '' }} ">

                                <i class="nav-icon fa fa-handshake"></i>
                                <p> How To Like Work</p>
                            </a>
                        </li>
                    </ul>
                 
                </li>
                <li
                    class="nav-item {{ request()->routeIs('freelancer*') || request()->routeIs('client*') ? 'menu-open' : '' }}  ">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('freelancer*') || request()->routeIs('client*') ? 'active' : '' }}  ">
                        <i class='fas fa-user-alt'></i>
                        <p>
                            Users
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('freelancer.index') }}"
                                class="nav-link {{ request()->routeIs('freelancer.index') || request()->routeIs('freelancer*')? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>Freelancer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('client.index') }}"
                                class="nav-link {{ request()->routeIs('client.index') || request()->routeIs('client*') ? 'active' : '' }} ">

                                <i class='fas fa-arrow-right'></i>
                                <p>Client</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}"
                        class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }} ">

                        <i class='fas fa-cogs'></i>
                        <p>
                            Settings

                        </p>
                    </a>
                </li>
                <li
                    class="nav-item {{ request()->routeIs('testimonials*') || request()->routeIs('account-section') || request()->routeIs('flexible-section') || request()->routeIs('learn-how-to-hire') || request()->routeIs('contract-section*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('testimonials*') || request()->routeIs('account-section') || request()->routeIs('flexible-section') || request()->routeIs('learn-how-to-hire') || request()->routeIs('contract-section*') ? 'active' : '' }}">

                        <i class='fas fa-edit'></i>
                        <p>
                            Manage Homepage
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item {{ request()->routeIs('testimonials*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">

                                <i class='fa fa-quote-left'></i>
                                <p>
                                    Testimonials
                                    <i class="end fas fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('testimonials.index') }}"
                                        class="nav-link {{ request()->routeIs('testimonials.index') || request()->routeIs('testimonials.edit') ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>All Testimonials</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('testimonials.create') }}"
                                        class="nav-link {{ request()->routeIs('testimonials.create') ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>Add Testimonials</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>



                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('account-section') }}"
                                class="nav-link {{ request()->routeIs('account-section') ? 'active' : '' }}">

                                <i class='fa fa-calculator'></i>
                                <p>
                                    Accounting Section

                                </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('flexible-section') }}"
                                class="nav-link {{ request()->routeIs('flexible-section') ? 'active' : '' }}">

                                <i class='fa fa-arrows-alt'></i>
                                <p>
                                    Flexible Section

                                </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('learn-how-to-hire') }}"
                                class="nav-link {{ request()->routeIs('learn-how-to-hire') ? 'active' : '' }}">

                                <i class='fa fa-chalkboard-teacher'></i>
                                <p>
                                    Learn How To hire

                                </p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('contract-section') }}"
                                class="nav-link {{ request()->routeIs('contract-section*') ? 'active' : '' }}">

                                <i class='fa fa-file-contract'></i>
                                <p>
                                    Contract Section

                                </p>
                            </a>
                        </li>
                    </ul>

                </li>

                <li class="nav-item">
                    <a href="{{ route('jobs.index') }}"
                        class="nav-link {{ request()->routeIs('jobs*') ? 'active' : '' }} ">

                        <i class='fas fa-briefcase'></i>
                        <p>
                            Jobs by Client

                        </p>
                    </a>
                </li>


                <li
                    class="nav-item {{ request()->routeIs('language*') || request()->routeIs('country*') ? 'menu-open' : '' }}">
                    <a href="javascript:void(0)"
                        class="nav-link {{ request()->routeIs('language*') || request()->routeIs('country*') ? 'active' : '' }}">

                        <i class='fas fa-edit'></i>
                        <p>
                            Manage Data
                            <i class="end fas fa-angle-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item {{ request()->routeIs('language*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">

                                <i class='fa fa-globe'></i>
                                <p>
                                    Language
                                    <i class="end fas fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('language.index') }}"
                                        class="nav-link {{ request()->routeIs('language.index') || request()->routeIs('language.edit')  ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>All language</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('language.create') }}"
                                        class="nav-link {{ request()->routeIs('language.create') ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>Add language</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nav nav-treeview">
                        <li class="nav-item {{ request()->routeIs('country*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">

                                <i class='fa fa-map'></i>
                                <p>
                                    Country
                                    <i class="end fas fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('country.index') }}"
                                        class="nav-link {{ request()->routeIs('country.index') || request()->routeIs('country.edit') ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>All Countries</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('country.create') }}"
                                        class="nav-link {{ request()->routeIs('country.create') ? 'active' : '' }}">

                                        <i class='fas fa-arrow-right'></i>
                                        <p>Add Country</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li
                class="nav-item {{ request()->routeIs('resources*') || request()->routeIs('resource-category*') ? 'menu-open' : '' }}">
                <a href="javascript:void(0)"
                    class="nav-link {{ request()->routeIs('resources*') || request()->routeIs('resource-category*') ? 'active' : '' }}">

                    <i class='fas fa-edit'></i>
                    <p>
                       Resources
                        <i class="end fas fa-angle-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item {{ request()->routeIs('resource-category*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">

                            <i class='fa fa-globe'></i>
                            <p>
                                Category
                                <i class="end fas fa-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('resource-category.index') }}"
                                    class="nav-link {{ request()->routeIs('resource-category.index') || request()->routeIs('resource-category.edit')  ? 'active' : '' }}">

                                    <i class='fas fa-arrow-right'></i>
                                    <p>All Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('resource-category.create') }}"
                                    class="nav-link {{ request()->routeIs('resource-category.create') ? 'active' : '' }}">

                                    <i class='fas fa-arrow-right'></i>
                                    <p>Add Category</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav nav-treeview">
                    <li class="nav-item {{ request()->routeIs('resources*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">

                            <i class='fa fa-map'></i>
                            <p>
                                Resource Data
                                <i class="end fas fa-angle-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('resources.index') }}"
                                    class="nav-link {{ request()->routeIs('resources.index') || request()->routeIs('resources.edit') ? 'active' : '' }}">

                                    <i class='fas fa-arrow-right'></i>
                                    <p>All Resource Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('resources.create') }}"
                                    class="nav-link {{ request()->routeIs('resources.create') ? 'active' : '' }}">

                                    <i class='fas fa-arrow-right'></i>
                                    <p>Add Resource Data</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                        class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <p>Profile</p>
                    </a>
                </li>
                

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                          this.closest('form').submit();">
                            <i class="fa fa-sign-out-alt"></i>
                            <p>{{ __('Sign Out') }}</p>
                        </a>
                    </form>
                </li>
                
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
