<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
//$pegawai=$this->session->userdata('pegawai');
//$data=$this->session->userdata('user');
//if(!$pegawai){
//print_r(unserialize($this->session->userdata('user_pegawai')));
  $user_pegawai=unserialize($this->session->userdata('user_pegawai'))['nipbaru'][0];
  //print_r($user_pegawai);
  //echo $user_pegawai['direktori_foto'];
//}else{
  //$user['username']=$data['nipbaru'][0]['nipbaru'];
//}

?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Akun Pribadi Pegawai</title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo base_url('/dashboard/vendor/fontawesome-free/css/all.min.css');?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url('/dashboard/css/sb-admin-2.min.css')?>" rel="stylesheet">

  <!--<link href="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">-->
  <script src="<?php echo base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js'); ?>"></script>
  <script src="<?php echo base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js'); ?>"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo site_url('Frontoffice/frontoffice_admin'); ?>">
        <div class="sidebar-brand-icon">
          <img src="<?php echo base_url('/assets/images/logo_sulsel.png');?>" alt="" width="50px">
        </div>
        <div class="sidebar-brand-text mx-3"><font size="1.5pt">Akun Pegawai</font></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard <?php echo ucwords($user_pegawai['nama']); ?></span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Akun Pribadi</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Ruang Virtual:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas" >Riwayat Unggah Surat</a>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas_balasan" >Surat dibalas</a>
            <a class="collapse-item" style="cursor:pointer;" id="surat_berkas_terusan" >Surat dikembalikan</a>
            <a class="collapse-item" href="">Agenda Kerja</a>
            <a class="collapse-item" href="<?php echo site_url('Akuntamupegawai/index_unggah'); ?>"><button class="btn btn-info">Front Office</button></a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Meja Kerja Virtual</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kelola:</h6>
            <a class="collapse-item" style="cursor:pointer;" id="profil_saya">Profil Saya</a>
            <a class="collapse-item" style="cursor:pointer;" id="kelola_profil">Kelola Profil</a>
            <a class="collapse-item" style="cursor:pointer;" id="ubah_password">Ubah Password</a>
            <a class="collapse-item" href="<?php echo site_url('Akuntamupegawai/index_unggah') ?>"><button class="btn btn-info btn-xs">Front Office</button></a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Addons
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Login Screens:</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Other Pages:</h6>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alerts Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-success">
                      <i class="fas fa-donate text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 7, 2019</div>
                    $290.29 has been deposited into your account!
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-warning">
                      <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 2, 2019</div>
                    Spending Alert: We've noticed unusually high spending for your account.
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                    <div class="small text-gray-500">Jae Chun · 1d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                    <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div>
                    <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                    <div class="small text-gray-500">Chicken the Dog · 2w</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo strtoupper($user_pegawai['nama']);?></span>
                <img class="img-profile rounded-circle" src="<?php echo base_url('/login/p1.png'); ?>" alt="">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
          </div>

          <!-- Content Row -->
          <div class="row">

            
          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#surat_berkas").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/tampilkan_tabel_surat_terupload_di_akun_pegawai/".$user_pegawai['nipbaru']);?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#surat_berkas_terusan").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/tampilkan_tabel_surat_terusan_di_akun_pegawai/".$user_pegawai['nipbaru']);?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

           <!-- Script untuk pemanggilan ajax -->
           <script>      
          $(document).ready(function(){
            $("#surat_berkas_balasan").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/tampilkan_tabel_surat_dibalas_di_akun_pegawai/".$user_pegawai['nipbaru']);?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script> 

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#profil_saya").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/tampilkan_profil_pegawai/".$user_pegawai['nipbaru']);?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script>

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#kelola_profil").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/gerbang/rincian_penampil_kelola_profil_pegawai");?>',{ data: "<?php echo $user_pegawai['username']; ?>"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script>

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
            $("#ubah_password").click(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/ubah_password_pegawai/".$user_pegawai['nipbaru']);?>',{ data: "<?php echo $user_pegawai['username']; ?>"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            });
            
          </script>

          <!-- Script untuk pemanggilan ajax -->
          <script>      
          $(document).ready(function(){
              var loading = $("#pra_tabel");
              var tampilkan = $("#penampil_tabel");
              tampilkan.hide();
              loading.fadeIn(); 
              $.post('<?php echo site_url("/Akuntamupegawai/tampilkan_profil_pegawai/".$user_pegawai['nipbaru']);?>',{ data:"okbro"},
              function(data,status){
                loading.fadeOut();
                tampilkan.html(data);
                tampilkan.fadeIn(2000);
              });
            });
            
          </script> 

          <!--Skrip untuk menampilkan modal saat window onload-->
          <script type="text/javascript">
              var ok=2;
              $(window).on('load',function(){
                  <?php (isset($data_post_enkrip_hex)|| isset($pesan_kirim_surat) || isset($pesan_kirim_berkas))?$ok=2:$ok=3;  ?>
                  let antara=<?php echo $ok ?>;
                  $('#myModal').modal('show');
                  let loading = $("#pra");
                  let tampilkan = $("#penampil");
                  tampilkan.hide();
                  loading.fadeIn(); 
                  if(antara==2){
                    loading.fadeOut();
                    tampilkan.fadeIn(2000);
                  }
              });
          </script>

          <!-- Content Row -->
          <div class="row">

            <!-- Content Column -->
            <div class="col-xl-3 col-md-6 mb-4" id="okbro" style='overflow:auto;' >
            <center>
            <div id='pra_tabel' style='width:40%;display:none;' align='center' >
            <i class="fa-3x fas fa-spinner fa-pulse" style="color:#97BEE4"></i>
            <!--
            <div class="progress" style="margin-top:50px; height:20px">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:100%">
              mohon tunggu...
              </div>
            </div>
            -->
            </div>
          </center>
            <div id=penampil_tabel align="center" style='width:100%;overflow:auto;'></div>
            
            </div>

            
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; e-Sinra <?php echo $this->config->item('nama_opd');?> Provinsi Sulawesi Selatan</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" <?php echo $this->config->item('style_modal_admin');?>>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Apakah anda hendak keluar?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Klik tombol Logout di bawah ini untuk keluar session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
          <a class="btn btn-primary" href="<?php echo base_url('index.php/login/logintamupegawai/logout_pegawai') ?>">Logout</a>
        </div>
      </div>
    </div>
  </div>

<?php
  if(isset($data_post_enkrip_hex) || isset($pesan_kirim_surat) || isset($pesan_kirim_berkas)) {
			//alert('Selamat:\nSurat dan Berkas pendukung sukses diunggah');
			echo "
      <!-- Modal -->
      <div class=\"modal fade\" id=\"myModal\" role=\"dialog\" style=\"z-index:100000;\">
        <div class=\"modal-dialog modal-lg\">
        
        <!-- Modal content-->
        <div class=\"modal-content\" ".$this->config->item('style_modal_admin').">
          <div class=\"modal-header\">
          <center>Rincian total file surat dan berkas yang hendak dikirim</center>
          <!--<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>-->
          </div>
          <div class=\"modal-body\">
          <center>
          <div id='pra' style='width:65%;' align='center' >
          <i class='fa-3x fas fa-spinner fa-pulse' ".$this->config->item('style_progres_bulat_admin')."></i>
          <!--
          <div class=\"progress\" style=\"margin-top:50px; height:20px\">
            <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
            mohon tunggu...
            </div>
          </div>
          -->
          </center>
          </div>
          <div id=penampil align=\"center\" style='width:100%;'>
          ";
            
          //if($pesan_kirim_surat!==FALSE){echo "<br>INI OKSURAT: ".$pesan_kirim_surat;}
          //if($pesan_kirim_berkas!==FALSE){echo "<br>INI OKBERKAS: ".$pesan_kirim_berkas;}
          if(!(isset($pesan_kirim_surat) || isset($pesan_kirim_berkas))) {
            echo "<br>INI UKURAN POST: ".strlen($data_post_enkrip_hex)." bytes<br>";
            $ok=trim(ini_get('post_max_size'),'M');
            $ok=$ok*1024*1024;
            echo "BATAS MAKSIMUM ADALAH: ".$ok." bytes";
            if(strlen($data_post_enkrip_hex)>$ok) {alert('file anda melampaui batas upload\nbatas ukuran kirim file terkirim adalah 40M\nanda dapat menyampaikan ke admin server \nuntuk merubah nilai post_max_size pada PHP.ini');} else{
              echo "
              <form name=\"myform\" action=\"".site_url('Frontoffice/coba_kirim')."\" method=\"POST\">
                <input type=\"hidden\" name=\"data_post_enkrip_hex\" value=\"".$data_post_enkrip_hex."\">
                <button id=\"Link\" class=\"btn btn-primary\" onclick=\"document.myform.submit()\" >Kirim</button>
              </form>


              ";
            }
          } else {
            if(isset($pesan_kirim_surat)) {
              echo('Info Surat: Surat anda sukses terkirim<br>');
            }else{
              echo('Info Surat: Surat anda gagal terkirim atau tidak ada surat yang diunggah sebelumnya');}
            if(isset($pesan_kirim_berkas)) {
              echo('Info Berkas: Berkas anda sukses terkirim');
            }else{
              echo('Info Berkas: Berkas anda gagal terkirim atau tidak ada berkas yang diunggah sebelumnya');}
          }
          echo "
          </div>
          <div class=\"modal-footer\">
          <button type=\"button\" class=\"btn btn-primary\" data-dismiss=\"modal\">Close</button>
          </div>
        </div>
        
        </div>
      </div>
      ";
		} else {
			//alert('Maaf Surat dan Berkas Anda Gagal di unggah \natau Anda Belum Unggah Surat dan Berkas');
		}
  ?>
  <script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  </script>
  
  <!-- Bootstrap core JavaScript-->
  <!--TAMBAHAN:-->
  <script src="<?php echo base_url('/dashboard/vendor/jquery/jquery.min.js');?>"></script>
  <script src="<?php echo base_url('/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js');?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url('/dashboard/vendor/jquery-easing/jquery.easing.min.js');?>"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url('/dashboard/js/sb-admin-2.min.js');?>"></script>

  <!-- Page level plugins -->
  <script src="<?php echo base_url('/dashboard/vendor/chart.js/Chart.min.js');?>"></script>

  <!-- Page level custom scripts -->
  <script src="<?php echo base_url('/dashboard/js/demo/chart-area-demo.js');?>"></script>
  <script src="<?php echo base_url('/dashboard/js/demo/chart-pie-demo.js');?>"></script>
  
</body>

</html>
