<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>e-Sinra <?php echo $this->config->item('nama_opd'); ?> Provinsi Sulawesi Selatan</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

	<script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    
  <!-- Favicons-->
  <link href="<?php echo base_url('/public/restaurantly/assets/img/favicon.png');?>" rel="icon">
  <link href="<?php echo base_url('/public/restaurantly/assets/img/apple-touch-icon.png');?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/icofont/icofont.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/boxicons/css/boxicons.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/animate.css/animate.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/owl.carousel/assets/owl.carousel.min.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/venobox/venobox.css');?>" rel="stylesheet">
  <link href="<?php echo base_url('/public/restaurantly/assets/vendor/aos/aos.css');?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo base_url('/public/restaurantly/assets/css/style.css');?>" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Restaurantly - v1.1.0
  * Template URL: https://bootstrapmade.com/restaurantly-restaurant-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex">
      <div class="contact-info mr-auto">
        <i class="icofont-phone"></i> <?php echo $this->config->item('nomor_telepon'); ?>
        <i class="icofont-envelope"></i><a href="mailto:<?php echo $this->config->item('email'); ?>"><?php echo $this->config->item('email'); ?></a>
        <span class="d-none d-lg-inline-block"><i class="icofont-clock-time icofont-rotate-180"></i> Jam kantor: 08:00 AM - 15:00 PM</span>
      </div>
      <div class="languages">
        <ul>
          <li>Id</li>
          <li><a href="#">En</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <h1 class="logo mr-auto"><a href="#"><img style="margin-right:10px;" src="<?php echo base_url('/public/restaurantly/assets/img/logo_sulsel.png');?>" alt="" class="img-fluid"><?php echo $this->config->item('nama_opd'); ?> Prov. Sulawesi Selatan</a></h1>
      
      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li><a href="<?php echo $this->config->item('nama_landingpage'); ?>">Home</a></li>
          <li class="active"><a href="#">Beranda</a></li>
          <!--<li><a href="#about">Tentang <?php echo $this->config->item('nama_opd'); ?></a></li>-->
          <li><a href="#contact">Kontak</a></li>
          <li class="book-a-table text-center"><a href="<?php echo $this->config->item('link_frontoffice'); ?>" target='_blank'>Front Office <?php echo $this->config->item('nama_opd'); ?></a></li>
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">
    <div class="container position-relative text-center text-lg-left" data-aos="zoom-in" data-aos-delay="100">
      <div class="row">
        <div class="col-lg-8">
          <h1>Welcome to <span>e-Sinra <?php echo $this->config->item('nama_opd'); ?></span></h1>
          <h2><?php echo $this->config->item('nama_bidang'); ?></h2>

          <div class="btns">
            <a href="#about" class="btn-menu animated fadeInUp scrollto">Tentang Kami</a>
            <a href="#" class="btn-book animated fadeInUp scrollto" data-toggle="modal" data-target="#loginModal2">Masuk admin <?php echo $this->config->item('nama_bidang_pendek'); ?></a>
          </div>
        </div>
        <style>#tombol_youtube{opacity:0.3;} #tombol_youtube:hover{opacity:1;}</style>
        <div id="tombol_youtube" class="col-lg-4 d-flex align-items-center justify-content-center" data-aos="zoom-in" data-aos-delay="200">
          <a href="<?php echo $this->config->item('youtube_opd'); ?>" class="venobox play-btn" data-vbtype="video" data-autoplay="true"></a>
        </div>

      </div>
    </div>
  </section><!-- End Hero -->

  <!-- The Modal -->
  <div class="modal fade" id="loginModal2">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content" <?php echo $this->config->item('style_modal_content'); ?>>
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h6 class="modal-title">
          <img src="<?php echo base_url('/public/restaurantly/assets/img/LogoSulselH.png');?>" class="logo_sulsel" style="float:left;margin-right:20px;" />
                  <br><br>e-Sinra <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel</h6>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form action="<?php echo site_url('login/login/process') ?>" method="post" accept-charset="utf-8">
            <div class="form-group">
              <!--<label for="username">Username:</label>-->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" style='color:black;'><i class="icofont-user-suited"></i></span>
                </div>
                <input type="text" class="form-control" id="username" placeholder="Masukkan username" name="username" required>
              </div>
            </div>
            <div class="form-group">
              <!--<label for="password">Password:</label>-->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" style='color:black;'><i class="icofont-ui-password"></i></span>
                </div>
                  <input type="password" class="form-control" id="password" placeholder="Masukkan password" name="password" required>
              </div>
            </div>
            <div class="custom-control custom-switch" style="margin-bottom:20px;">
              <input type="checkbox" class="custom-control-input" id="switch1">
              <label class="custom-control-label" for="switch1">Ingat saya</label>
            </div>
            <button type="submit" class="btn btn-secondary" style="width:100%">Submit</button>
          </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#loginModal">Lupa Password?</button><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
        
      </div>
    </div>
  </div>

  <!-- Modal Media Sosial-->
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" <?php echo $this->config->item('style_modal_content'); ?>>
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                  <img src="<?php echo base_url('/public/restaurantly/assets/img/LogoSulselH.png');?>" class="logo_sulsel" style="float:left;margin-right:20px;" />
                  e-Sinra <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">Silahkan menghubungi super admin <?php echo $this->config->item('nama_opd'); ?></div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>


  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="100">
            <div class="about-img">
              <img width="100%" src="<?php echo base_url('/public/restaurantly/assets/img/kaban.jpg');?>" alt="">
            </div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
            <h3>Tentang Kami</h3>
            <?php echo $this->config->item('isi_about_opd'); ?>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    <!-- ======= Aturan Layanan Section ======= -->
    <section id="aturanlayanan" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="100">
            <div class="about-img">
              <img width="100%" src="<?php echo base_url('/public/restaurantly/assets/img/kaban.jpg');?>" alt="">
            </div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
            <h3>Aturan Layanan</h3>
            <?php echo $this->config->item('aturan_layanan'); ?>
          </div>
        </div>

      </div>
    </section><!-- End Aturan Layanan Section -->

    <!-- ======= Kebijakan Section ======= -->
    <!--
    <section id="kebijakan" class="about">
      <div class="container" data-aos="fade-up">

        <div class="row">
          <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in" data-aos-delay="100">
            <div class="about-img">
              <img width="100%" src="<?php echo base_url('/public/restaurantly/assets/img/kaban.jpg');?>" alt="">
            </div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
            <h3>Kebijakan</h3>
            <?php echo $this->config->item('kebijakan'); ?>
          </div>
        </div>

      </div>
    </section>
    -->
    <!-- End Aturan Layanan Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Kontak</h2>
          <p>Hubungi Kami</p>
        </div>
      </div>
      <div class="container" data-aos="fade-up">

        <div class="row mt-5">

          <div class="col-lg-4">
            <div class="info">
              <div class="address">
                <i class="icofont-google-map"></i>
                <h4>Lokasi:</h4>
                <p><?php echo $this->config->item('alamat_opd'); ?></p>
              </div>

              <div class="open-hours">
                <i class="icofont-clock-time icofont-rotate-90"></i>
                <h4>Jam Kantor:</h4>
                <p>
                  Senin-Jumat:<br>
                  08:00 AM - 15.00 PM
                </p>
              </div>

              <div class="email">
                <i class="icofont-envelope"></i>
                <h4>Email:</h4>
                <p><?php echo $this->config->item('email'); ?></p>
              </div>

              <div class="phone">
                <i class="icofont-phone"></i>
                <h4>Telepon:</h4>
                <p><?php echo $this->config->item('nomor_telepon'); ?></p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0">

            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="form-row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Nama" data-rule="minlen:4" data-msg="Paling sedikit 4 karakter" />
                  <div class="validate"></div>
                </div>
                <div class="col-md-6 form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Email" data-rule="email" data-msg="Silahkan memasukkan alamat email yang valid" />
                  <div class="validate"></div>
                </div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Tujuan" data-rule="minlen:4" data-msg="Silahkan memasukkan tujuan minimal 4 karakter" />
                <div class="validate"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="8" data-rule="required" data-msg="Silahkan menulis sesuatu untuk kami" placeholder="Pesan"></textarea>
                <div class="validate"></div>
              </div>
              <div class="mb-3">
                <div class="loading">Sedang memuat...</div>
                <div class="error-message"></div>
                <div class="sent-message">Pesan anda telah terkirim, Terima kasih!</div>
              </div>
              <div class="text-center"><button type="submit">Kirim Pesan</button></div>
            </form>

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3><?php echo $this->config->item('nama_opd'); ?> Prov. Sulawesi Selatan</h3>
              <p>
                <?php echo $this->config->item('alamat_opd'); ?><br>
                <strong>Telepon:</strong> <?php echo $this->config->item('nomor_telepon'); ?><br>
                <strong>Email:</strong> <?php echo $this->config->item('email'); ?><br>
              </p>
              <div class="social-links mt-3">
                <!--Twitter-->
                <?php if($this->config->item('twitter_opd')!==''){ ?>
                <a href="<?php echo $this->config->item('twitter_opd'); ?>" class="twitter" target='_blank'><i class="bx bxl-twitter"></i></a>
                <?php } else { ?>
                <a href="#" class="twitter" data-toggle="modal" data-target="#pesanModal"><i class="bx bxl-twitter"></i></a>
                <?php } ?>

                <!--Facebook-->
                <?php if($this->config->item('fb_opd')!==''){ ?>
                <a href="<?php echo $this->config->item('fb_opd'); ?>" class="facebook" target='_blank'><i class="bx bxl-facebook"></i></a>
                <?php } else { ?>
                <a href="#" class="facebook" data-toggle="modal" data-target="#pesanModal"><i class="bx bxl-facebook"></i></a>
                <?php } ?>

                <!--Instagram-->
                <?php if($this->config->item('instagram_opd')!==''){ ?>
                <a href="<?php echo $this->config->item('instagram_opd'); ?>" class="instagram" target='_blank'><i class="bx bxl-instagram"></i></a>
                <?php } else { ?>
                <a href="#" class="instagram" data-toggle="modal" data-target="#pesanModal"><i class="bx bxl-instagram"></i></a>
                <?php } ?>

                <!--Skaype-->
                <?php if($this->config->item('skype_opd')!==''){ ?>
                <a href="<?php echo $this->config->item('skype_opd'); ?>" class="google-plus" target='_blank'><i class="bx bxl-skype"></i></a>
                <?php } else { ?>
                <a href="#" class="google-plus" data-toggle="modal" data-target="#pesanModal"><i class="bx bxl-skype"></i></a>
                <?php } ?>

                <!--Linkedin-->
                <?php if($this->config->item('linkedin_opd')!==''){ ?>
                <a href="<?php echo $this->config->item('linkedin_opd'); ?>" class="linkedin" target='_blank'><i class="bx bxl-linkedin"></i></a>
                <?php } else { ?>
                <a href="#" class="linkedin" data-toggle="modal" data-target="#pesanModal"><i class="bx bxl-linkedin"></i></a>
                <?php } ?>

              </div>
            </div>
          </div>
          <!-- Modal Media Sosial-->
          <div class="modal fade" id="pesanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" <?php echo $this->config->item('style_modal_content'); ?>>
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                  <img src="<?php echo base_url('/public/restaurantly/assets/img/LogoSulselH.png');?>" class="logo_sulsel" style="float:left;margin-right:20px;" />
                  e-Sinra <?php echo $this->config->item('nama_opd'); ?> Prov. Sulsel</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">Maaf akun kami di media sosial ini belum resmi tersedia</div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Menu utama</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $this->config->item('nama_landingpage'); ?>" target='_blank'>Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $this->config->item('link_frontoffice'); ?>" target='_blank'>Front Office<?php echo $this->config->item('nama_opd'); ?></a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $this->config->item('link_sekretariat'); ?>" target='_blank'>Sekretariat <?php echo $this->config->item('nama_opd'); ?></a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#about">Tentang kami</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $this->config->item('situs_induk'); ?>" target='_blank'>Situs <?php echo $this->config->item('nama_opd'); ?></a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#aturanlayanan">Aturan Layanan</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Bidang <?php echo $this->config->item('nama_opd'); ?></h4>
            <ul>
              <?php foreach($this->config->item('bidang_opd') as $key=>$bidang){?>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $this->config->item('base_domain'); ?>/<?php echo $this->config->item('nama_opd'); ?>-<?php echo $key; ?>" target='_blank'><?php echo $bidang; ?></a></li>
              <?php } ?>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Berita kami</h4>
            <p>Silahkan mendaftarkan email anda untuk layanan berita dari kami</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span><?php echo $this->config->item('nama_opd'); ?> Prov. Sulawesi Selatan</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/restaurantly-restaurant-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->


  <?php 
    $error = $this->session->userdata('form_error');
    if ($error)
    {   
      echo "<script> alert('Harap mengisi kolom username atau password') </script>";
    }

    $error_isian = $this->session->userdata('login_salah');
    if ($error_isian)
    {
      echo "<script> alert('Username atau password anda salah') </script>";
    }

    $error_login = $this->session->userdata('percobaan_login');
    if ($error_login)
    {
      echo "<script> alert('Maaf Sesi anda kadaluarsa. Silahkan login lagi') </script>";
    }

    $ok_keluar = $this->session->userdata('keluar', 'keluar');
    if ($ok_keluar)
    {
      echo "<script> alert('Anda telah keluar') </script>";
    }

    $this->session->unset_userdata('form_error');
    $this->session->unset_userdata('login_salah');
    $this->session->unset_userdata('percobaan_login');
    $this->session->unset_userdata('keluar');
  ?>
  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/jquery/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/jquery.easing/jquery.easing.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/php-email-form/validate.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/owl.carousel/owl.carousel.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/isotope-layout/isotope.pkgd.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/venobox/venobox.min.js');?>"></script>
  <script src="<?php echo base_url('/public/restaurantly/assets/vendor/aos/aos.js');?>"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo base_url('/public/restaurantly/assets/js/main.js');?>"></script>

</body>

</html>