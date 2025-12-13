<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Petology</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/front/assets/css/bootstrap.css')}}" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Dosis:400,500|Poppins:400,700&display=swap" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="{{asset('assets/front/assets/css/style.css')}}" rel="stylesheet" />
  <!-- responsive style -->
  <link href="{{asset('assets/front/assets/css/responsive.css')}}" rel="stylesheet" />
</head>


    <!-- header section strats -->
 @yield('header')

    <!-- end header section -->
@yield('contenido')
    <!-- slider section -->


  <!-- end info_section -->

  <!-- footer section -->
  <section class="container-fluid footer_section">
    <p>
      &copy; 2025 Todos los derechos reservados
      <a href="https://html.design/"></a>
    </p>
  </section>
  <!-- footer section -->

  <script type="text/javascript" src="{{asset('assets/front/assets/js/jquery-3.4.1.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/front/assets/js/bootstrap.js')}}"></script>



</body>
</body>

</html>