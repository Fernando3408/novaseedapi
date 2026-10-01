
<!DOCTYPE html>
<html lang="en" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="Latest updates and statistic charts"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
          // El CDN puede bloquearse o no estar disponible; el login no debe romperse por tipografías.
          if (window.WebFont && typeof window.WebFont.load === 'function') {
            window.WebFont.load({
              google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
              active: function() { sessionStorage.fonts = true; }
            });
          }
        </script>
        <!--end::Web font -->

        <!--begin::Global Theme Styles -->
                    <link href="/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
                    <link href="/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
                <!--end::Global Theme Styles -->

        
        
        
        <link rel="shortcut icon" href="/favicon.ico" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <link rel="stylesheet" href="/css/style.css">
    </head>
    <!-- end::Head -->

    
    <!-- begin::Body -->
    <body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >

        
        
        <!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    
            
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url(./assets/app/media/img//bg/bg-3.jpg);">
    <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
        <div class="m-login__container">
            @if(Session::has('success'))
                <div class="alert alert-block alert-success" style="margin-bottom: 20px;margin-top: -20px">
                    {{ nl2br(Session::get('success')) }}
                </div>
            @endif
            @if(Session::has('alert'))
                <div class="alert alert-block alert-danger" style="margin-bottom: 20px;margin-top: -20px">
                    {{ nl2br(Session::get('alert')) }}
                </div>
            @endif
            <div class="m-login__logo">
                <a href="#">
                <img src="/img/logo.png" style="width: 100%;max-width: 200px;">     
                </a>
            </div>
            <div class="m-login__signin">
                
                @yield('content')
                
                
            </div>
        </div>  
    </div>
</div>              
        

</div>
<!-- end:: Page -->


<!--begin::Global Theme Bundle -->
    <script src="/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<!--end::Global Theme Bundle -->


    <!--begin::Page Scripts -->
            <script src="/assets/snippets/custom/pages/user/login.js" type="text/javascript"></script>
        <!--end::Page Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.rut@1.1.2/jquery.rut.js"></script>

    <script>
        $(document).ready(function(){


            if (window.jQuery && jQuery.fn.rut && !jQuery.fn.Rut) {
              jQuery.fn.Rut = jQuery.fn.rut;
              jQuery.Rut = { validar: jQuery.validateRut };
            }

            $.validator.addMethod("rut", function(value, element) {
              return this.optional(element) || $.Rut.validar(value);
            }, "Este campo debe ser un rut valido.");

            $(".rut").Rut({
               format_on: 'keyup'
            })

        })
    </script>

    </body>
<!-- end::Body -->
</html>
