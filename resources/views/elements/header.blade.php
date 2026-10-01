
<input type="hidden" id="currentController" value="<?= @explode('/', request()->route()->uri)[0];?>">
<input type="hidden" id="currentAction" value="<?= @explode('/', request()->route()->uri)[1];?>">


<!-- begin::Header -->
<header id="m_header" class="m-grid__item m-header " m-minimize="minimize" m-minimize-offset="200" m-minimize-mobile-offset="200">
<div class="m-header__top">
  <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
	<div class="m-stack m-stack--ver m-stack--desktop">

	  <!-- begin::Brand -->
	  <div class="m-stack__item m-brand">
		<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
		  <div class="m-stack__item m-stack__item--middle m-brand__logo">
			<a href="/home" class="m-brand__logo-wrapper">
			  <img alt="" src="/img/logo.png" style="max-width:70px;max-height: 70px" />
			</a>
		  </div>
		  <div class="m-stack__item m-stack__item--middle m-brand__tools">

			<!-- begin::Responsive Header Menu Toggler-->
			<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
			  <span></span>
			</a>

			<!-- end::Responsive Header Menu Toggler-->

			<!-- begin::Topbar Toggler-->
			<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
			  <i class="flaticon-more"></i>
			</a>

			<!--end::Topbar Toggler-->
		  </div>
		</div>
	  </div>

	  <!-- end::Brand -->

	  <!-- begin::Topbar -->
	  <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
		<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
		  <div class="m-stack__item m-topbar__nav-wrapper">
			<ul class="m-topbar__nav m-nav m-nav--inline">
				<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link ">
				  <span class="m-topbar__username" style="color:#a4b5c3">
				  	@if( Auth::user()->hasRole("admin"))
						Administrador: <b>{{ Auth::user()->name }}</b>		
				  	@endif
				  </span>
				</a>
			  </li>
			  @if( Auth::user()->hasRole("gerencial") OR Auth::user()->hasRole("user") )
			  <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link ">
				  <span class="m-topbar__username" style="color:#4b5156">/</span>
				</a>
			  </li>
			  <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link ">
				  <span class="m-topbar__username">
					{{ Auth::user()->name }}
				  </span>
				</a>
			  </li>
			  @endif
			  <!--<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link " >
				  <span class="m-topbar__username" style="color:#4b5156">/</span>
				</a>
			  </li>
			  <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link ">
				  <span class="m-topbar__username">Cambiar contraseña</span>
				</a>
			  </li>-->
			  <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img"
			   m-dropdown-toggle="click">
				<a href="#" class="m-nav__link ">
				  <span class="m-topbar__username" style="color:#4b5156">/</span>
				</a>
			  </li>
			  <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img">
				<a class="m-nav__link"  href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <span class="m-topbar__username">Cerrar Sesión</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			  </li>
			</ul>
		  </div>
		</div>
	  </div>

	  <!-- end::Topbar -->
	</div>
  </div>
</div>
<div class="m-header__bottom">
  <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
	<div class="m-stack m-stack--ver m-stack--desktop">

	  <!-- begin::Horizontal Menu -->
	  <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
		<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
		<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light ">
		  <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
			<li data-controller="home" class="m-menu__item  " aria-haspopup="true"><a href="/home" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Home</span></a></li>
			@if( Auth::check() && (Auth::user()->hasRole("admin") OR Auth::user()->hasRole("gerencial") OR Auth::user()->hasRole("user")) )
			<li data-controller="novaseed-ia" class="m-menu__item" aria-haspopup="true">
			  <a href="{{ config('services.novaseed_ia.panel_url') }}" class="m-menu__link" target="_blank" rel="noopener" title="Abrir Genetista Estadístico NovaSeed IA">
				<span class="m-menu__item-here"></span>
				<i class="m-menu__link-icon flaticon-light"></i>
				<span class="m-menu__link-text">Genetista IA</span>
			  </a>
			</li>
			@endif
			@if( Auth::user()->hasRole("admin") )
			<li data-controller="varities" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Variedades</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/varities/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Variedad
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/varities" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado de Variedades
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/papelera" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado de Variedades en Papelera
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="crossings" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Cruces</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/crossings/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Cruce
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/crossings" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado de Cruces
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="divider"></li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/greenhouse/" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-location"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Invernadero
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="divider"></li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/greenhouse/show_mobile/14" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-location"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Invernadero Tablet
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="mapouts" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Mapeos</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/mapouts/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Mapeo
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/mapouts" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado de Mapeos
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="blocks" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Ensayos</span>	
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/blocks/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Ensayo
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/blocks" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado de Ensayos
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/blocks/trash" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Ensayos Eliminados
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="features" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Características</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/features/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Característica
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/features" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado Características
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/relative_yield" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Listado Standards por Mercado
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="users" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Resultados</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/results" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Resultados Ensayos
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/results/preview_report" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Informes
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/results/crossings" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Resultados Cruces
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
<li class="m-menu__item " aria-haspopup="true">
<a href="/results/fries" class="m-menu__link ">
<i class="m-menu__link-icon flaticon-list"></i>
<span class="m-menu__link-title">
<span class="m-menu__link-wrap">
				  				<span class="m-menu__link-text">
				  					Resultados frituras
				  				</span> 
				  			</span>
</span>
</a>
</li>
<li class="m-menu__item " aria-haspopup="true">
<a href="/results/compare-results" class="m-menu__link ">
<i class="m-menu__link-icon flaticon-list"></i>
<span class="m-menu__link-title">
<span class="m-menu__link-wrap">
<span class="m-menu__link-text"> Comparación Resultados </span>
</span>
</span>
</a>
</li>
<li class="m-menu__item " aria-haspopup="true">
<a href="/results/stadistics" class="m-menu__link ">
<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					EVA
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="users" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Trazabilidad Productiva</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/farmers" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Agricultores
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/companies" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Empresas
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/lands" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Campos
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/traceability" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Registro Trazabilidad
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/outtraceability" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-list"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Registro Trazabilidad Exterior
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			<li data-controller="users" class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" aria-haspopup="true">
			  <a href="javascript:;" class="m-menu__link m-menu__toggle" title="Non functional dummy link">
				<span class="m-menu__item-here"></span>
				<span class="m-menu__link-text">Usuarios</span>
				<i class="m-menu__hor-arrow la la-angle-down"></i>
				<i class="m-menu__ver-arrow la la-angle-right"></i>
			  </a>
			  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
				<span class="m-menu__arrow m-menu__arrow--adjust"></span>
				<ul class="m-menu__subnav">
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/users/create" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-plus"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Crear Usuario
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/users" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Modificar Usuarios
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				  <li class="m-menu__item " aria-haspopup="true">
				  	<a href="/users/logins" class="m-menu__link ">
				  		<i class="m-menu__link-icon flaticon-edit"></i>
				  		<span class="m-menu__link-title"> 
				  			<span class="m-menu__link-wrap"> 
				  				<span class="m-menu__link-text">
				  					Sesiones
				  				</span> 
				  			</span>
				  		</span>
				  	</a>
				  </li>
				</ul>
			  </div>
			</li>
			@endif
			@if( Auth::user()->hasRole("gerencial") OR Auth::user()->hasRole("client") )
			<li data-controller="reporte" class="m-menu__item  " aria-haspopup="true"><a href="/reporte" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Reporte</span></a></li>
			<li data-controller="detalle" class="m-menu__item  " aria-haspopup="true"><a href="/detalle" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Detalle</span></a></li>
			<li data-controller="excel" class="m-menu__item  " aria-haspopup="true"><a href="/excel" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Exportar</span></a></li>
				@if( Auth::user()->hasRole("asd"))
					<li data-controller="alertas" class="m-menu__item  " aria-haspopup="true"><a href="/alerts" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Alertas</span></a></li>
				@endif
			@endif
		  </ul>
		</div>
	  </div>

	  <!-- end::Horizontal Menu -->

	  <!--begin::Search-->
	  <div class="m-stack__item m-stack__item--middle m-dropdown m-dropdown--arrow m-dropdown--large m-dropdown--mobile-full-width m-dropdown--align-right m-dropdown--skin-light m-header-search m-header-search--expandable m-header-search--skin-" id=""
	   m-quicksearch-mode="default">
	
		@if( Auth::user()->hasRole("gerencial") OR Auth::user()->hasRole("client") )
		<!--begin::Search Form -->
		<form class="m-header-search__form" id="form-entre-fechas" style="display: none;">
		  	<div class="m-header-search__wrapper ">
			<span class="m-header-search__input-wrapper">
			  <input autocomplete="off" type="text" name="ini" class="m-header-search__input" value="" placeholder="Fecha Inicio" id="fecha_inicio_home" style="border-right: 1px solid #CCC;">
			</span>
			<span class="m-header-search__input-wrapper">
			  <input autocomplete="off" type="text" name="fin" class="m-header-search__input" value="" placeholder="Fecha Termino" id="fecha_termino_home">
			</span>
			<span class="m-header-search__input-wrapper">
			  <button class="btn btn-info btn-search"><i class="fa fa-search"></i></button>
			</span>

			<span class="m-header-search__input-wrapper">
			  <a class="btn btn-warning btn-print" style="margin-left: -1px"><i class="fa fa-print"></i></a>
			</span>
			
		  </div>
		</form>

		<!--begin::Search Form -->
		<form class="m-header-search__form" id="form-reporte" style="display: none;">
			<span class="m-header-search__input-wrapper">
			  <a class="btn btn-info " id="btn-volver-reporte">Volver a buscador de reporte</a>
			</span>
			<span class="m-header-search__input-wrapper">
			  <a class="btn btn-info " id="btn-imprimir-reporte">Imprimir</a>
			</span>
		</form>

		<!--end::Search Form -->

		<!--begin::Search Results -->
		@endif

		<!--end::Search Results -->
	  </div>

	  <!--end::Search-->
	</div>
  </div>
</div>
</header>

<script>
    $(document).ready(function(){
        //menu activo seleccionado
        var controller = $("#currentController").val()
        var action = $("#currentAction").val()

        var t;t=mUtil.isRTL()

        if(controller == "") controller = 'home'

        $(".m-menu__item[data-controller="+controller+"]").addClass("m-menu__item--active")

        $("#fecha_inicio_home").datepicker({todayHighlight:true,language: 'es',format:'dd-mm-yyyy',setDate: '01-12-2018'})
        $("#fecha_termino_home").datepicker({endDate: new Date(),todayHighlight:true,language: 'es',format:'dd-mm-yyyy'})

        $("#fecha_inicio_home_").datepicker({todayHighlight:true,language: 'es',format:'dd-mm-yyyy',setDate: '01-12-2018'})
        $("#fecha_termino_home_").datepicker({endDate: new Date(),todayHighlight:true,language: 'es',format:'dd-mm-yyyy'})

        $("#form-entre-fechas .btn-search i").click(function(){
        	$("#form-entre-fechas .btn-search").trigger("click")
        })

        $("#form-entre-fechas .btn-search").click(function(){
        	$("#form-entre-fechas .btn-search").html('<i class="fa fa-spinner fa-spin fa-fw"></i>')
        })

        $("#form-entre-fechas .btn-print").click(function(){
        	
        	window.open('/homepdf?'+$("#form-entre-fechas").serialize(),'_blank');

        })


        
    })
</script>
