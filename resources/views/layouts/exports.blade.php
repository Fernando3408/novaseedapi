<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <!-- begin::Head -->
  <head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    
  </head>

  <!-- end::Head -->

  <!-- begin::Body -->
  <body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default">
        @yield('content')

      <!-- end::Body -->

      <!-- begin::Footer -->
      <footer class="m-grid__item m-footer ">
        <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
          <div class="m-footer__wrapper">
            <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
              <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                <span class="m-footer__copyright">
                  2018 &copy; {{ config('app.name', 'Laravel') }} desarrollado por <a target="_blank" href="https://weblabs.cl" class="m-link">Weblabs Informática SpA.</a>.
                </span>
              </div>
              <!--
              <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
                <ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
                  <li class="m-nav__item">
                    <a href="#" class="m-nav__link">
                      <span class="m-nav__link-text">About</span>
                    </a>
                  </li>
                </ul>
              </div>
            -->
            </div>
          </div>
        </div>
      </footer>

      <!-- end::Footer -->
    </div>

    <!-- end:: Page -->


    <!--end::Page Scripts -->
  </body>

  <!-- end::Body -->
</html>