<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <!-- begin::Head -->
  <head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
      if (window.WebFont && typeof window.WebFont.load === 'function') WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>

    <!--end::Web font -->

    <!--begin::Global Theme Styles -->
    <link href="/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />

    <link href="/assets/demo/demo2/base/style.bundle.css" rel="stylesheet" type="text/css" />

    <link href="/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

    <link href="/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

    <link href="/css/hierarchy-view.css" rel="stylesheet" type="text/css" />


    <!--end::Page Vendors Styles -->
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="/css/novaseed-ai-mascot.css?v=20260930-17" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/js/novaseed-ai-mascot.js?v=20260930-18" defer></script>
    <style type="text/css">
        .ui-tooltip :hover:after{
          background: #333;
          background: rgba(0,0,0,.9);
          border-radius: 5px;
          bottom: 26px;
          color: rgb(255, 77, 85);
          content: attr(title);
          right: 20%;
          padding: 5px 15px;
          position: absolute;
          z-index: 98;
          width: 220px;}
        .ui-tooltip :hover:before{
            border: solid;
            border-color: #333 transparent;
            border-width: 6px 6px 0 6px;
            bottom: 20px;
            content: "";
            right: 50%;
            position: absolute;
            z-index: 99;
        }
        .ui-tooltip  {
          padding: 5px 20px;
          border-radius: 20px;
          margin: 10px;
          text-align: center;
          font: bold 14px ;
          font-stretch: condensed;
          text-decoration: none;
          box-shadow: 0 0 10px black;
          max-width: 160px;
          z-index: 999999999999;
        }
        .ui-helper-hidden-accessible{
            display: none;
        }
    </style>
  </head>

  <!-- end::Head -->

  <!-- begin::Body -->
  <body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default">

    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">

      @include('elements.header')

      <!-- end::Header -->

      <!-- begin::Body -->
      <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop  m-container m-container--responsive m-container--xxl m-page__container m-body">
        <div class="m-grid__item m-grid__item--fluid m-wrapper">

          <!-- END: Subheader -->
          <div class="m-content">

             @if(Session::has('success'))
                <div class="alert alert-block alert-success">
                    <?= nl2br(Session::get('success')) ?>
                </div>
            @endif
            @if(Session::has('alert'))
                <div class="alert alert-block alert-danger">
                    <?= nl2br(Session::get('alert')) ?>
                </div>
            @endif

            

            @yield('content')

            

          </div>
        </div>

        <!--</div>-->
      </div>

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


    <!-- begin::Scroll Top -->
    <div id="m_scroll_top" class="m-scroll-top">
      <i class="la la-arrow-up"></i>
    </div>

    <!-- end::Scroll Top -->

    
    <!--begin::Global Theme Bundle -->
    <script src="/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="/assets/demo/demo2/base/scripts.bundle.js" type="text/javascript"></script>

    <!--end::Global Theme Bundle -->


    <!--begin::Page Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.rut@1.1.2/jquery.rut.js"></script>
    <script>
      // Compatibilidad con el API legacy usado por los formularios NovaSeed.
      if (window.jQuery && jQuery.fn.rut && !jQuery.fn.Rut) {
        jQuery.fn.Rut = jQuery.fn.rut;
        jQuery.Rut = { validar: jQuery.validateRut };
      }
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    
    <!--begin::Datatables -->
    <script src="/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <!--end::Datatables -->

    <!--begin::Page Scripts -->
    <script src="/assets/demo/default/custom/crud/datatables/basic/basic.js" type="text/javascript"></script>
    <!--end::Page Scripts -->

    <!--begin::sweetalert2 -->
    <script src="/assets/demo/default/custom/components/base/sweetalert2.js" type="text/javascript"></script>
    <!--end::sweetalert2 -->
    



    <script>
        global = {}
        function start_loading(id) {
            global.loading = id;
            global.texto = $("#"+id).html();
            $("#"+id).html("<i class='fa fa-spinner fa-spin'></i> Cargando");
            $("#"+id).attr('disabled', true)
        }

        function end_loading(){
            $("#"+global.loading).html(global.texto);
            $("#"+global.loading).attr('disabled', false)

        }
        $(document).ready(function(){

            $('[data-toggle="tooltip"]').tooltip();

            $.validator.addMethod("rut", function(value, element) {
              return this.optional(element) || $.Rut.validar(value);
            }, "Este campo debe ser un rut valido.");

            $(".rut").Rut({
               format_on: 'keyup'
            })

            showColors = function(){
                $("#modal-colors").modal('show')
            }

        })

        ;(function($){
            

        }(jQuery));

        // Disable search and ordering by default
        $.extend( $.fn.dataTable.defaults, {
            "language": {
                "url": "/js/datatables.spanish.json"
            }
        } );


        //$(".datatables_p").DataTable()

        $(".btn-delete").click(function(){
            var element = $(this)
            swal(
                {
                    title:"¿Está seguro que quiere eliminar?",
                    text:"Si elimina el registro no podrá volver atrás.",
                    type:"warning",
                    showCancelButton:!0,
                    confirmButtonText:"Si, eliminar",
                    cancelButtonText:"No, cancelar",
                    reverseButtons:!0
                }
            ).then(function(e){
                if(e.value){
                   $(element).parent().submit()
                }else{
                    return false;
                }
            })
            return false;
        })


        

    </script>

    <!--end::Page Scripts -->
    @include('components.novaseed-ai-chat-drawer')
    @include('components.novaseed-ai-launcher')
  </body>

  <!-- end::Body -->
</html>
