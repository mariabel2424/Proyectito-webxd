@extends('layouts.template')
@section('header')

    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container fixed-top">
          <a class="navbar-brand" href="index.html">
            <img src="{{ asset('assets/front/assets/images/logo.png') }}" alt="">
            <span>
              Cursos Vacionales 
            </span>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
             <li class="nav-item active">
              <a class="nav-link" href="#home">inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
             <a class="nav-link" href="#service">Servicios</a>
                     </li>
            <li class="nav-item">
             <a class="nav-link" href="#galery">Galería</a>
                </li>
              <li class="nav-item">
               <a class="nav-link" href="#contact">Contáctanos</a>
             </li>
            </ul>

  <!-- aquí irá la parte derecha con los botones -->
  <div class="navbar-right-actions d-flex align-items-center">
    <a href="{{ url('/register') }}" class="nav-auth-btn nav-auth-btn-outline mr-2">Registrarse</a>
    <a href="{{ url ('/login') }}" class="nav-auth-btn">Iniciar sesión</a>
  </div>
</div>

        </nav>
      </div>
    </header>
@endsection
@section('contenido')
    <section class=" slider_section position-relative " id = "home">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-4 offset-md-2">
                  <div class="slider_detail-box">
                    <h1>
                      Curso de 
                      <span>
                        futbol
                      </span>
                    </h1>
                    <p>
                      Domina el campo, perfecciona tu técnica y vive la pasión del deporte rey. 
                      En nuestro curso, mejorarás tus pases, regates y estrategia de juego mientras 
                      te diviertes y haces nuevos amigos. ¡Prepárate para marcar la diferencia en la cancha!
                    </p>
                    <div class="btn-box">
                      <a href="{{ url('/register') }}" class="btn-1">
                        Inscribirse
                      </a>
                      <a href="#contact" class="btn-2" >
                        Contactanos
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="slider_img-box">
                    <img src="{{asset('assets/front/assets/images/futbol.png')}}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-4 offset-md-2">
                  <div class="slider_detail-box">
                    <h1>
                      Curso de
                      <span>
                        Vóley
                      </span>
                    </h1>
                    <p>
                      Eleva tu juego y aprende a volar en la red. 
                      Este curso es perfecto para desarrollar reflejos rápidos, 
                      potenciar tus remates y dominar los saques. Trabaja en equipo y 
                      experimenta la emoción de cada punto ganado.
                    </p>
                    <div class="btn-box">
                      <a href="{{ url('/register') }}" class="btn-1">
                        Inscribirse
                      </a>
                      <a href="#contact" class="btn-2">
                        Contactanos
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="slider_img-box">
                    <img src="{{asset('assets/front/assets/images/voley.png')}}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-4 offset-md-2">
                  <div class="slider_detail-box">
                    <h1>
                      Curso de 
                      <span>
                      Básquet
                      </span>
                    </h1>
                    <p>
                      Mejora tus dribles, afina tu puntería y conviértete en el líder de la cancha. 
                      Entrénate para entender la táctica del juego, perfeccionar tus tiros libres y 
                      jugar con intensidad. ¡Es hora de encestar tus metas!
                    </p>
                    <div class="btn-box">
                      <a href="{{ url('/register') }}" class="btn-1">
                        Inscribirse
                      </a>
                      <a href="#contact" class="btn-2">
                        Contactanos
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="slider_img-box">
                    <img src="{{asset('assets/front/assets/images/basquet.png')}}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-4 offset-md-2">
                  <div class="slider_detail-box">
                    <h1>
                      Curso de 
                      <span>
                      Boxeo
                      </span>
                    </h1>
                    <p>
                      Canaliza tu energía, fortalece tu disciplina y domina el arte de la defensa. 
                      Aprende técnicas de jab, cross y movimiento de pies. Es la mejor forma de mejorar
                       tu condición física, confianza y liberar el estrés.
                    </p>
                    <div class="btn-box">
                      <a href="{{ url('/register') }}" class="btn-1">
                        Inscribirse
                      </a>
                      <a href="#contact" class="btn-2">
                        Contactanos
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="slider_img-box">
                    <img src="{{asset('assets/front/assets/images/boxeo.png')}}" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- end slider section -->
  </div>

  <!-- service section -->
  <section class="service_section layout_padding" id ="service">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6 offset-md-2">
          <h2 class="custom_heading">
            Nuestros <span>Servicios</span>
          </h2>
          <div class="container layout_padding2">
            <div class="row">
              <div class="col-md-4">
                <div class="img_box">
                  <img src="{{asset('assets/front/assets/images/s-1.png')}}" alt="">
                </div>
                <div class="detail_box">
                  <h6>
                    Coaching de Alto Nivel
                  </h6>
                  <p>
                    Entrenadores especializados con experiencia en formación juvenil. 
                    Aseguramos una metodología de enseñanza enfocada en la técnica correcta, 
                    disciplina y el desarrollo integral del deportista.
                  </p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="img_box">
                  <img src="{{asset('assets/front/assets/images/s-2.png')}}" alt="">
                </div>
                <div class="detail_box">
                  <h6>
                    Instalaciones Seguras y Equipadas
                  </h6>
                  <p>
                    Entrena en un ambiente profesional y vigilado. 
                    Contamos con canchas, rings y espacios diseñados para maximizar el 
                    aprendizaje y garantizar la seguridad en cada sesión.
                  </p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="img_box">
                  <img src="{{asset('assets/front/assets/images/s-3.png')}}" alt="">
                </div>
                <div class="detail_box">
                  <h6>
                    Desarrollo Social y Disciplina
                  </h6>
                  <p>
                    Más que deporte, es formación. Fomentamos el trabajo en equipo, el respeto 
                    y la autodisciplina, habilidades esenciales que llevarán a casa y 
                    aplicarán en la vida diaria.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div>
          </div>
        </div>
        <div class="col-md-4">
          <img src="{{asset('assets/front/assets/images/Tool.png')}}" alt="" class="w-100">
        </div>
      </div>
    </div>
  </section>

  <!-- end service section -->



  <!-- gallery section -->
  <section class="gallery-section layout_padding" id = "galery">
    <div class="container">
      <h2>
        Galeria de cursos
      </h2>
    </div>
    <div class="container ">

      <div class="img_box box-3">
        <img src="{{asset('assets/front/assets/images/g-3.png')}}" alt="">
      </div>
      <div class="img_box box-4">
        <img src="{{asset('assets/front/assets/images/g-4.png')}}" alt="">
      </div>
      <div class="img_box box-5">
        <img src="{{asset('assets/front/assets/images/g-5.png')}}" alt="">
      </div>
    </div>
  </section>



  <!-- end gallery section -->

  <!-- buy section -->

  <section class="buy_section layout_padding">
    <div class="container">
      <h2>
        Puedes Registrate En Nuestros Cursos Vacacionales
      </h2>
      <p>
        ¡No dejes pasar el verano sin aprender algo nuevo! Inscríbete hoy y asegura tu cupo.
      </p>
      <div class="d-flex justify-content-center">
        <a href="/register">
          Registrarte
        </a>
      </div>
    </div>
  </section>

  <!-- end buy section -->


  <!-- info section -->
  <section class="info_section layout_padding2" id = "contact">
    <div class="container">
      <div class="info_items">
        <a href="">
          <div class="item ">
            <div class="img-box box-1">
              <img src="" alt="">
            </div>
            <div class="detail-box">
              <p>
                Montecristi, Manabí, Ecuador
              </p>
            </div>
          </div>
        </a>
        <a href="">
          <div class="item ">
            <div class="img-box box-2">
              <img src="" alt="">
            </div>
            <div class="detail-box">
              <p>
                +593 981373472
              </p>
            </div>
          </div>
        </a>
        <a href="">
          <div class="item ">
            <div class="img-box box-3">
              <img src="" alt="">
            </div>
            <div class="detail-box">
              <p>
                cursosvacacionalesmontecristi@gmail.com
              </p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
@endsection