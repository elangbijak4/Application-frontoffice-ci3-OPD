<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 //===============KHUSUS UNTUK OFFICE==================================
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
 
 use PhpOffice\PhpWord\PhpWord;
 use PhpOffice\PhpWord\Writer\Word2007;

 //===============END KHUSUS UNTUK OFFICE==============================

class Akuntamupegawai extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model("model_frommyframework");
		$this->load->helper('alert');
		$this->load->library('form_validation');
		$this->load->library('enkripsi');
		$this->load->library('viewfrommyframework');

	}
	
	public function index()
	{
		//redirect( site_url('login/login') );
		//$data1["key_suratmasuk"]=$this->penarik_key_controller('surat_masuk');
		//print_r($data1["key_suratmasuk"]);
		//$this->load->view('front_office',$data1);
		$this->load->view('loginpage_tamupegawai');
	}

	public function index_pegawai(){
		$this->load->view('loginpage_pegawai');
	}

	public function index_dashboard(){
		$this->load->view("admin_frontoffice/dashboard_tamupegawai");
	}

	public function index_dashboard_pegawai($data=NULL,$pesan=NULL){
		if($data!==NULL){
			$data=$this->enkripsi->dekapsulasiData($data);
			if(isset($data['direktori_foto'][0])) $data['nipbaru'][0]=$data['direktori_foto'][0];
			$this->session->set_userdata('user_pegawai',serialize($data));
			//print_r($this->session->userdata('user_pegawai'));
		}
		if($this->session->userdata('toggle')==TRUE){
			if($pesan!==NULL) {alert($pesan);$this->session->set_userdata('toggle',FALSE);}
		}
		$this->load->view("admin_frontoffice/dashboard_pegawai");
	}

	
	//==============FUNGSI-FUNGSI UNTUK MENAMPILKAN AGENDA====================================================
	public function baca_agenda($table='tbagenda_kerja',$mulai_rekord=0,$jumlah_rekord=20,$order='desc'){

		echo "
			<h5>Agenda Hari Ini</h5>           
			<table class='table table-hover table-striped'>
			<thead>
				<tr>
				<th>id</th>
				<th>Acara</th>
				<th>Tempat</th>
				<th>Tanggal</th>
				<th>Urgensi</th>
				<th>Rincian</th>
				</tr>
			</thead>
			<tbody>";
			$fields=$this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			$query=$this->model_frommyframework->query_dengan_limit($table,$mulai_rekord,$jumlah_rekord,$fields[0],$order);
			foreach ($query->result() as $row)
			{
					echo "
					<tr>
					<td>".$row->idagenda_kerja."</td>
					<td>".$row->acara_kegiatan."</td>
					<td>".$row->tempat."</td>
					<td>".$row->tanggal."</td>
					<td>".$row->urgensi."</td>
					<td><button class=\"d-sm-inline-block btn btn-lg btn-success shadow-sm kotak\" id=\"rincian_agenda$row->idagenda_kerja\"><i class='fas fa-eye fa-sm text-white-100'></i> Rincian</button></td>
					</tr>
					<tr id='tr$row->idagenda_kerja'>
					<td><i class='fas fa-eye fa-sm text-white-100'></i></td>
					<td colspan=4>
					Rincian:<br>
					Sampai Tanggal: $row->sampai_tanggal<br>
					Lama Kegiatan: $row->lama_kegiatan<br>
					Status Kegiatan: $row->status_kegiatan<br>
					Urgensi Acara: $row->urgensi<br>
					Dasar Surat: $row->dasar_surat<br>
					Admin: $row->admin
					</td>
					<td><button class=\"d-sm-inline-block btn btn-lg btn-warning shadow-sm kotak\" id=\"tutup_rincian$row->idagenda_kerja\">Tutup</button></td>
					</tr>
					

					<style>
						#tr$row->idagenda_kerja{
							display:none;
						}
					</style>
					<script>
					$(document).ready(function(){
						$(\"#rincian_agenda$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeIn();
						});
						$(\"#tutup_rincian$row->idagenda_kerja\").click(function(){
							$('#tr$row->idagenda_kerja').fadeOut();
						});
						});
					</script>";
			}
			echo "
			</tbody>
			</table>
		";
	}

	//==============END FUNGSI-FUNGSI AGENDA==================================================================

	//==============FUNGSI-FUNGSI UNTUK BACA COUNTER SURAT MASUK==============================================
	public function notifikasi_surat_total(){
		echo "
				<!-- Nav Item - Messages -->
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_masuk1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#2C9FAF\"></i>
							<div class=\"status-indicator bg-success\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat masuk
							<span id=\"counter_surat_masuk_masuk1\" class=\"badge badge-danger badge-counter\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_kecil1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_masuk1\");
							var tampilan_kecil = $(\"#surat_masuk_kecil\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_masuk/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_masuk1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_terusan1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#17A673\"></i>
							<div class=\"status-indicator\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat terusan
							<span id=\"counter_surat_masuk_terusan1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_terusan1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_terusan1\");
							var tampilan_kecil = $(\"#surat_masuk_terusan1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_terusan/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_terusan1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_terusan')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_balasan1\">
						  <div class=\"dropdown-list-image mr-3\">
						  <i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#F4B619\"></i>
							<div class=\"status-indicator bg-warning\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox surat balasan
							<span id=\"counter_surat_masuk_balasan1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_balasan1\"></span></div>
						  </div>
						</a>
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_balasan1\");
							var tampilan_kecil = $(\"#surat_masuk_balasan1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_balasan/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_balasan1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_balasan')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
						<!--
						<a class=\"dropdown-item d-flex align-items-center\" style=\"cursor:pointer;\" id=\"notif_surat_arsip1\">
						  <div class=\"dropdown-list-image mr-3\">
							<i class=\"fas fa-envelope fa-fw\" style=\"font-size:30px;color:#2653D4\"></i>
							<div class=\"status-indicator bg-info\"></div>
						  </div>
						  <div class=\"\">
							<div class=\"text-truncate\">Inbox arsip surat
							<span id=\"counter_surat_masuk_arsip1\" class=\"badge badge-danger badge-counter\" style=\"margin-top:-15px;\"></span></div>
							<div class=\"small text-gray-500\">Jumlah surat belum terbaca <span id=\"surat_masuk_arsip1\"></span></div>
						  </div>
						</a>
						-->
						<script>      
						$(document).ready(function(){
							var tampilkan = $(\"#counter_surat_masuk_arsip1\");
							var tampilan_kecil = $(\"#surat_masuk_arsip1\");
							$.post('".site_url('/Frontoffice/baca_counter_surat_arsip/echo')."',{ data:\"okbro\"},
							function(data,status){
							  tampilkan.html(data);
							  if(data>0)tampilan_kecil.html(data);else tampilan_kecil.html('0');
							});
						  });
						</script> 
		
						<script>      
						$(document).ready(function(){
						  $(\"#notif_surat_arsip1\").click(function(){
							var loading = $(\"#pra_tabel\");
							var tampilkan = $(\"#penampil_tabel\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url('/Frontoffice/tampilkan_tabel_surat_keluar')."',{ data:\"okbro\"},
							function(data,status){
							  loading.fadeOut();
							  tampilkan.html(data);
							  tampilkan.fadeIn(2000);
							});
						  });
						  });
						</script> 
		
		";
	}

	public function baca_counter_surat_controller($counter_table='tbcounter_notifikasi',$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter'){
		return $this->model_frommyframework->pembaca_nilai_kolom_tertentu($counter_table,$kolom_rujukan,$kolom_target)[0];
	}

	public function baca_counter_surat_total($mode='fungsi'){
		$counter_surat_total=array();
		$counter_table='tbcounter_notifikasi';
		$kolom_target='nilai_counter';

		for($i=1;$i<5;$i++){
			$counter_surat_total[$i]=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>$i),$kolom_target);
		}
		if($mode=='fungsi'){
			return array_sum($counter_surat_total);
		}else {
			if(array_sum($counter_surat_total)==0)NULL;else echo array_sum($counter_surat_total);
		}
	}

	public function baca_counter_surat_masuk($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>1),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}

	public function baca_counter_surat_arsip($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>2),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}
	
	public function baca_counter_surat_terusan($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>3),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}

	public function baca_counter_surat_balasan($mode='fungsi'){
		$counter_table='tbcounter_notifikasi';
		if($mode=='fungsi'){
			return $this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
		}else {
			$ok=$this->baca_counter_surat_controller($counter_table,$kolom_rujukan=array('nama_kolom'=>'idcounter_notifikasi','nilai'=>4),$kolom_target='nilai_counter');
			if($ok==0)NULL;else echo $ok;
		}
	}
	//==============END FUNGSI-FUNGSI COUNTER SURAT MASUK=====================================================

	//==============FUNGSI UNTUK MENGEKSPORT KE WORD,PDF,HTML DARI TINYMCE====================================
	public function coba_word(){
		set_error_handler("myErrorHandler");
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		//$section->addText('Hello World !');

		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $_POST['mytextarea']);
		
		$filename = $_POST['nama_file'];
		
		header('Content-Type: application/msword');
		//header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="'. $filename .'.docx"'); 
		header('Cache-Control: max-age=0');

		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		myErrorHandler($errno, $errstr, $errfile, $errline);
	}

	public function coba_word2(){
		$phpWord = new PhpWord();
		$section = $phpWord->addSection();
		$section->addText($_POST['mytextarea']);
		
		
		$writer = new Word2007($phpWord);
		
		$filename = $_POST['nama_file'];
		
		header('Content-Type: application/msword');
        header('Content-Disposition: attachment;filename="'. $filename .'.docx"'); 
		header('Cache-Control: max-age=0');
				
		$writer->save('php://output');
		
	}

	public function compiler_untuk_bbc_to_html($string){
		set_error_handler("myErrorHandler");
		//Ubah bbcode menjadi html tag
		$string=preg_replace('#<!DOCTYPE html>#','', $string);

		if(preg_grep("#~#i",array($string))==array()){
			$string2=preg_replace('#\n#','~', $string);
			$ok=explode('~',$string2);
		}else if(preg_grep("#`#i",array($string))==array()){
			$string2=preg_replace('#\n#','`', $string);
			$ok=explode('`',$string2);
		}else if(preg_grep("#|#i",array($string))==array()){
			$string2=preg_replace('#\n#','|', $string);
			$ok=explode('|',$string2);
		}else if(preg_grep("#~#i",array($string))==array()){
			$string2=preg_replace('#\n#','~', $string);
			$ok=explode('~',$string2);
		}else{
			alert("Maaf untuk sementara file anda tidak bisa di compile, anda dapat menyimpan file ini untuk dianalisa admin dan untuk perbaikan kode, terima kasih.");
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
			exit();
		}
		$okbru=array();
		foreach($ok as $key=>$isi){
			//Tahap pemberian pasangan tag untuk <p>, karena bbc tidak memberi pasangan
			(preg_grep("#<p#i",array($isi))!==array())?$okbru[$key]=$isi."</p>":$okbru[$key]=$isi;

			//Tahap menghilangkan semua <br/> yang menyalahi aturan html di dalm bbc
			do{
				(preg_grep("#<br /><html>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /><head>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></head>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /><body>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></body>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
				(preg_grep("#<br /></html>#i",array($isi))!==array())?$okbru[$key]=preg_replace('#<br />#','', $okbru[$key]):NULL;
			}while(preg_grep("#<br /><html>#i",array($okbru[$key]))!==array());
		}
		$okberikut=implode('',$okbru);

		//Tahap mengganti semua tag bbc yaitu [] diganti menjadi <>
		$okberikut=preg_replace('#\[#','<', $okberikut);
		$okberikut=preg_replace('#\]#','>', $okberikut);

		//Perbaiki tag <img> agar sesuai standar, karena phpword tidak menerima bentuk tag bbc untuk img
		if(preg_grep("#<img>#i",array($okberikut))!==array()){
			$okberikut=explode('<img>',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</img>#i",array($isi))!==array()){
					if(preg_grep("#../../#i",array($isi))!==array()){
						//$okberikut[$key]=preg_replace('#../../#','', $isi);
						$isi=trim($isi,'.');
						$isi=trim($isi,'/');
						$isi=trim($isi,'.');
						$okberikut[$key]=preg_replace('#</img>#','"></img>', $isi);
						$okberikut[$key]='<img src=".'.$okberikut[$key];
					}else{
						$okberikut[$key]=preg_replace('#</img>#','"></img>', $isi);
						$okberikut[$key]='<img src="'.$okberikut[$key];
					}
				}
			}
			$okberikut=implode('',$okberikut);
		}

		//Perbaiki tag <color></color> karena phpword tidak mengenali, ubah menjadi <span style="color:....></span>
		if(preg_grep("#<color=#i",array($okberikut))!==array()){
			$okberikut=explode('<color=',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</color>#i",array($isi))!==array()){
					$isi=explode('>',$isi);
					$isi[0]=$isi[0].'"';
					$isi=implode('>',$isi);
					$okberikut[$key]=preg_replace('#</color>#','</span>', $isi);
					$okberikut[$key]='<span style="color:'.$okberikut[$key];
				}
			}
			$okberikut=implode('',$okberikut);
		}

		//Sekarang bagaimana menangkap border="1"?
		//pecah dulu di <table, lalu pecah di ">", lalu ambil array[0] dan tangkap border="1", setelah tangkap, trim border=" dan akhir "
		//lalu baca berapa nilainya, lalu bikin border-width:1 sesuai nilainya, lalu tambahkan ke array[0] untuk pecahan <table.
		if(preg_grep("#<table#i",array($okberikut))!==array()){
			$string_width='';
			$string_border='';
			$string_style='';
			$okberikut=explode('<table',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</table>#i",array($isi))!==array()){
					$isi_baca=array();
					$isi_baca=explode('>',$isi);

					//cek dulu apakah border ada?
					if(preg_grep("#border=#i",array($isi_baca[0]))!==array()){ 
						//jika ada, baca nilainya
						$nilai=0;
						$isi_sub=array();
						$isi_sub2=array();
						$isi_sub=explode('border="',$isi_baca[0]);
						$isi_sub2=explode('"',$isi_sub[1]);
						$nilai=$isi_sub2[0];
						$string_border='border-width:'.$nilai.'px;';
						
						//hilangkan border:
						$isi=preg_replace('#border="[0-9]*"#','', $isi);
					}

					//cek apakah width ada?
					if(preg_grep("#width=#i",array($isi_baca[0]))!==array()){ 
						//jika ada, baca nilainya
						$nilai=0;
						$isi_sub=array();
						$isi_sub2=array();
						$isi_sub=explode('width="',$isi_baca[0]);
						$isi_sub2=explode('"',$isi_sub[1]);
						$nilai=$isi_sub2[0];
						$string_width='width:'.$nilai.'px;';

						//hilangkan width:
						$isi=preg_replace('#width="[0-9]*"#','', $isi);
					}

					$isi='<table'.$isi;
					$string_style='style=" '.$string_border.' '.$string_width.' ';
					if(preg_grep("#style=#i",array($isi))!==array()){
						//tambahkan $string_style:
						$isi=preg_replace('#<table style="#',$string_style, $isi);
					}
					$okberikut[$key]='<table '.$isi;
				}

			}
			$okberikut=implode('',$okberikut);
		}

		//Sekarang bagaimana menerjemahkan kode bbc untuk link url menjadi tag <a></a>? disini tag [] sudah diganti di atas
		if(preg_grep("#<url=#i",array($okberikut))!==array()){
			$okberikut=preg_replace('#</url>#','</a>', $okberikut);
			$okberikut=preg_replace('#<url=#','<a href="', $okberikut);
			$okberikut=explode('<a href="',$okberikut);
			foreach($okberikut as $key=>$isi){
				if(preg_grep("#</a>#i",array($isi))!==array()){
					$isi=explode('>',$isi);
					$isi[0]=$isi[0].'"';
					$isi=implode('>',$isi);
					$okberikut[$key]='<a href="'.$isi;

				}
			}
			$okberikut=implode('',$okberikut);
		}

		return $okberikut;
	}

	public function export2word_tinymce(){
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$section = $phpWord->addSection();
		
		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $this->compiler_untuk_bbc_to_html($_POST['mytextarea']));
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="'.$_POST['nama_file'].'.docx"');
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$objWriter->save('php://output');
		
	}

	public function export2pdf_tinymce(){
		//alert("Masih dalam rencana konstruksi");
		$file_html=$this->compiler_untuk_bbc_to_html($_POST['mytextarea']);
		export_html_ke_pdf($file_html,$output_dest='D',$_POST['nama_file'],$nama_satker=''.$this->config->item('nama_opd').' Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban '.$this->config->item('nama_opd').'',$lebar_page=270,$tinggi_page=330,$orientasi='');
	}

	public function export2excel_tinymce(){
		alert("Masih dalam rencana konstruksi");
	}

	public function export2html_tinymce(){
		//alert("OK MASUK export2html_tinymce");
		$file_html=$this->compiler_untuk_bbc_to_html($_POST['mytextarea']);
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file'])?$file=$_POST['nama_file'].".html":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file_simpan'])&&$_POST['direktori_file_simpan']!==''?$direktori="./".$_POST['direktori_file_simpan']."/":$_POST['direktori_file_simpan']='';
		//$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']);
		$_POST['direktori_file_simpan']!==''?$okbro=file_put_contents($direktori.$file, $file_html):$okbro=file_put_contents("./file_tersimpan_html/".$file, $file_html);
		if($okbro){
			//alert("direktori: ".$direktori);
			isset($direktori)?$direktori_trim=trim(trim($direktori,'.'),'/'):NULL;
			isset($direktori)?alert('data tersimpan di folder: '.base_url($direktori_trim)):alert('data tersimpan di folder '.base_url("file_tersimpan_html/"));
			//myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}else{
			alert('Data gagal tersimpan, periksa kembali direktori yang anda masukkan, apakah memang ada?');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function export2pp_tinymce(){
		alert("Masih dalam rencana konstruksi");
	}

	public function tes_preg_grep(){
		//$ok=preg_grep("#border#i",array('<table style="height: 36px;" border="1" width="69" cellspacing="0" cellpadding="0">'));
		//print_r($ok);
		$isi='<table style="height: 36px;" border="3000" width="69" cellspacing="0" cellpadding="0">';
		$isi=preg_replace('#border="[0-9]*"#','', $isi);
		echo $isi;
	} 

	public function tes_preg_replace(){
		$string='<!DOCTYPE html><html><head></head><body>jkj kdskdjskdj sdskdm</body></html>';
		echo "INI BRO POTONGNYA? ".preg_replace('#<!DOCTYPE html>#i', '', $string);
	}

	public function tes_preg_replace2(){
		$string='<!DOCTYPE html>%<html>%<head>%</head>%<body>%[i]kssssssssssssssssssssss[/i] kjda [b]skjdkld[/b] [u]kjdskad[/u]%%[color=#FF0000]kjljsk skjdlkjd lskdjldjld[/color]%% %%<table style="height: 73px; border-color: #ad2323;" border="1" width="213">%<tbody>%<tr>%<td style="width: 98.5px;">kjkss</td>%<td style="width: 98.5px;">sffdf</td>%</tr>%<tr>%<td style="width: 98.5px;">dfdf</td>%<td style="width: 98.5px;">dffdf</td>%</tr>%</tbody>%</table>%</body>%</html>';
		$string1=preg_replace('#<!DOCTYPE html>#i','', $string);
		$string2=preg_replace('#\[#','<', $string1);
		$string3=preg_replace('#\]#','>', $string2);
		$string3=preg_replace('#%#','', $string3);
		echo "INI BRO POTONGNYA? ".$string3;
	}

	public function tes_explode_untuk_p(){
		$ok="<!DOCTYPE html>
		<html>
		<head>
		</head>
		<body>
		<p align='justify'>kjskdsd [b]ksdjklasd[/b] [u]kldjlaskd[/u] [i]aslkdl[/i]
		
		<p align='center'>dlasdk ldjlkad aldalskd alsdjsakld
		
		<p align='right'>dalds ldlasd djlaskd
		
		<table style='height: 79px;' border='1' width='217' cellspacing='0' cellpadding='0'>
		<tbody>
		<tr>
		<td style='width: 100.5px;'>skjks</td>
		<td style='width: 100.5px;'>ss</td>
		</tr>
		<tr>
		<td style='width: 100.5px;'>ssxs</td>
		<td style='width: 100.5px;'>ssx</td>
		</tr>
		</tbody>
		</table>
		</body>
		</html>
		";
		print_r(explode('\n',$ok));
	}

	//==============END FUNGSI EXPORT KE WORD===========================================

	//===============FUNGSI UNTUK PERCOBAAN EDITOR=======================================
	public function iframe_editor(){
		echo "<iframe name='iframe_editor' src=\"".site_url('Frontoffice/buat_surat_baru_tinymce')."\" width='100%' height='600px' frameborder='0'></iframe>";
	}
	public function buat_surat_baru_tinymce(){
		echo "
		<link href=\"".base_url('/dashboard/vendor/fontawesome-free/css/all.min.css')."\" rel=\"stylesheet\" type=\"text/css\">
  		<link href=\"https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i\" rel=\"stylesheet\">
		<link href=\"".base_url('/dashboard/css/sb-admin-2.min.css')."\" rel=\"stylesheet\">
		<script src=\"".base_url('/public/vendor3.4.1/jquery/3.4.1/jquery.min.js')."\"></script>
		<script src=\"".base_url('/public/vendor3.4.1/bootstrap/3.4.1/js/bootstrap.min.js')."\"></script>
		<!-- Bootstrap core JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery/jquery.min.js')."\"></script>
		<script src=\"".base_url('/dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js')."\"></script>
		<!-- Core plugin JavaScript-->
		<script src=\"".base_url('/dashboard/vendor/jquery-easing/jquery.easing.min.js')."\"></script>
		<!-- Custom scripts for all pages-->
		<script src=\"".base_url('/dashboard/js/sb-admin-2.min.js')."\"></script>
		<!-- Page level plugins -->
		<script src=\"".base_url('/dashboard/vendor/chart.js/Chart.min.js')."\"></script>
		<!-- Page level custom scripts -->
		<script src=\"".base_url('/dashboard/js/demo/chart-area-demo.js')."\"></script>
		<script src=\"".base_url('/dashboard/js/demo/chart-pie-demo.js')."\"></script>
		";
		echo "
		<script src=\"".base_url('/public/tinymce/js/tinymce/tinymce.min.js')."\"></script>
		<script src=\"".base_url('/public/tinymce/js/tinymce/jquery.tinymce.min.js')."\"></script>
		";
		echo "
			<script type='text/javascript'>
			/* 
				tinymce.init({
					selector: '#mytextarea',
					plugins: 'table',
					menubar: 'table', 
					toolbar: \"insertdatetime table bold italic\"
				  });
				  */
				
				/*tinymce.init({ selector:'#mytextarea',plugins: 'table', theme: 'modern'});*/

				tinymce.init({
					selector: \"#mytextarea\",  // change this value according to your HTML
					base_url: '/public/tinymce/js/tinymce',
					plugins : 'insertdatetime table visualblocks advlist autolink link image lists charmap print preview anchor autoresize autosave bbcode code codesample colorpicker contextmenu directionality emoticons example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste save searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount ',
					menubar: \"favs file edit view format insert tools table help\",
					//contextmenu: \"link image imagetools table spellchecker\",
					draggable_modal: true,
					mobile: {
						plugins: [ 'autosave', 'lists', 'autolink' ],
						toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
					  },
					toolbar1: 'undo redo | fontsizes formats insertfile styleselect fontselect fontsizeselect| bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent ',
					toolbar2: \"visualblocks insertdatetime table advlist autolink link image lists charmap print preview anchor autoresize bbcode code codesample forecolor backcolor contextmenu directionality emoticons\",
					toolbar3: \"example fullpage fullscreen hr imagetools importcss layer legacyoutput media nonbreaking noneditable pagebreak paste searchreplace spellchecker tabfocus template textcolor textpattern toc visualchars wordcount\",
					menu: {
						file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
						edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
						view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
						insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
						format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
						tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
						table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
						help: { title: 'Help', items: 'help' },
						favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | spellchecker | emoticons'}
					  }

				});
				
				
				  
			</script>
		";
		//target=\"target_buat_surat_baru\" 
		echo "
			<div >
			<form target=\"target_buat_surat_baru\"  method='post' action=\"".site_url('Frontoffice/terima_hasil_ketikan_surat')."\">
			<textarea id='mytextarea' name='mytextarea' style=\"width:100%; height:60%\"></textarea>";
		
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_nama_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content' ".$this->config->item('style_modal_admin').">
					<div class='modal-header'>
					<h7 class='modal-title'>".$this->config->item('nama_opd')." Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_simpan' style='width:65%;' align='center' >
					<label for=nama_file>Nama file simpan:</label>
					<input type=text id='nama_file' class=\"form-control\" name='nama_file' placeholder='nama file...'>
					<input type=text id='direktori_file_simpan' class=\"form-control\" name='direktori_file_simpan' placeholder='masukkan direktori file (opsional)...'>
					<button type='submit' name='simpan' class=\"btn btn-sm btn-success shadow-sm\" id=\"simpan_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
					<button type='submit' id=\"export2word\" name='export2word' formaction=\"".site_url('Frontoffice/export2word_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Word</button>
					<button type='submit' id=\"export2pdf\" name='export2pdf' formaction=\"".site_url('Frontoffice/export2pdf_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PDF</button>
					<button type='submit' id=\"export2excel\" name='export2excel' formaction=\"".site_url('Frontoffice/export2excel_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke Excel</button>
					<button type='submit' id=\"export2html\" name='export2html' formaction=\"".site_url('Frontoffice/export2html_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Simpan ke HTML</button>
					<button type='submit' id=\"export2pp\" name='export2pp' formaction=\"".site_url('Frontoffice/export2pp_tinymce')."\" class=\"btn btn-sm btn-success shadow-sm\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-file-export fa-sm text-white-100\"></i> Export ke PowerPoint</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			</form>
			</div>
		";

		echo "
			<div >
			<form target=\"target_buat_surat_baru\" method='post' action=\"".site_url('Frontoffice/buka_surat')."\">";
			echo "
			<!-- Modal Simpan dan Buka File -->
			<div class='modal fade' id='modal_buka_file' role='dialog''>
				<div class='modal-dialog'>
				<!-- Modal content-->
				<div class='modal-content' ".$this->config->item('style_modal_admin').">
					<div class='modal-header'>
					<h7 class='modal-title'>".$this->config->item('nama_opd')." Provinsi Sulawesi Selatan</h7>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_buka_file' style='width:65%;' align='center' >
					<label for=nama_file_buka>Nama file buka:</label>
					<input type=text id='nama_file_buka' class=\"form-control\" name='nama_file_buka' placeholder='nama file...'>
					<input type=text id='direktori_file' class=\"form-control\" name='direktori_file' placeholder='masukkan direktori file (opsional)...'>
					<button type='button' name='buka_file' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"buka_file\" style=\"width:100%;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
					</div>
					</center>
					</div>
					<div class='modal-footer'>
					<!--<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>-->
					</div>
				</div>
				</div>
			</div>
		";

		echo "
			<div>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='simpan_file1' class=\"d-sm-inline-block btn btn-sm btn-primary shadow-sm\" id=\"simpan_file1\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-save fa-sm text-white-100\"></i> Simpan</button>
				<button type=button data-toggle=\"modal\" data-target=\"#modal_buka_file\" name='buka_file1' class=\"d-sm-inline-block btn btn-sm btn-warning shadow-sm\" id=\"buka_file1\"  style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-folder-open fa-sm text-white-100\"></i> Buka</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopdf' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopdf\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-pdf fa-sm text-white-100\"></i> Export PDF</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttohtml' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttohtml\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-html fa-sm text-white-100\"></i> Simpan HTML</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoword' class=\"d-sm-inline-block btn btn-sm btn-info shadow-sm\" id=\"exporttoword\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-word fa-sm text-white-100\"></i> Export Word</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttoexcel' class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"exporttoexcel\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-excel fa-sm text-white-100\"></i> Export Excel</button>
				<button type='button' data-toggle=\"modal\" data-target=\"#modal_nama_file\" name='exporttopp' class=\"d-sm-inline-block btn btn-sm btn-danger shadow-sm\" id=\"exporttopp\" style=\"float:right;margin-left:3px;margin-top:10px;\"><i class=\"fas fa-file-powerpoint fa-sm text-white-100\"></i> Ex PowerPoint</button>
				<!--<input style=\"float:right\" type=text class='form-control' name='nama_file'><label for=nama_file style=\"float:right;\">Masukkan nama file: </label>-->
			</div>
		";

		echo "
			<style>
				#simpan_file{
					display:none;
				}
				#export2word{
					display:none;
				}
				#export2pdf{
					display:none;
				}
				#export2excel{
					display:none;
				}
				#export2html{
					display:none;
				}
				#export2pp{
					display:none;
				}
				#direktori_file_simpan{
					display:block;
				}
			</style>
			<script>
			$(document).ready(function(){
                $(\"#simpan_file1\").click(function(){
					$('#simpan_file').show();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttoword\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').show();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttopdf\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').show();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
				$(\"#exporttoexcel\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').show();
					$('#export2html').hide();
					$('#export2pp').hide();
					$('#direktori_file_simpan').hide();
				});
                $(\"#exporttohtml\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').show();
					$('#export2pp').hide();
					$('#direktori_file_simpan').show();
				});
				$(\"#exporttopp\").click(function(){
					$('#simpan_file').hide();
					$('#export2word').hide();
					$('#export2pdf').hide();
					$('#export2excel').hide();
					$('#export2html').hide();
					$('#export2pp').show();
					$('#direktori_file_simpan').hide();
				});
				
				});
			</script>
		";
		echo "<iframe name='target_buat_surat_baru' width='0' height='0' frameborder='0'></iframe>";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#buka_file\").click(function(){
				  var tampilkan = $(\"#mytextarea\");
				  var nama_file = $(\"#nama_file_buka\").val();
				  var direktori_file = $(\"#direktori_file\").val();
                  $.post('".site_url("/Frontoffice/buka_surat")."',{ nama_file_buka:nama_file, direktori_file:direktori_file},
                  function(data,status){
					tinymce.activeEditor.setContent(data);

                  });
                });
				});
			</script>
        ";
		
	}

	public function terima_hasil_ketikan_surat(){
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file'])?$file=$_POST['nama_file'].".bbc":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file_simpan'])&&$_POST['direktori_file_simpan']!==''?$direktori="./".$_POST['direktori_file_simpan']."/":$_POST['direktori_file_simpan']='';
		//$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']);
		$_POST['direktori_file_simpan']!==''?$okbro=file_put_contents($direktori.$file, $_POST['mytextarea']):$okbro=file_put_contents("./file_tersimpan/".$file, $_POST['mytextarea']);
		if($okbro){
			//alert("direktori: ".$direktori);
			isset($direktori)?$direktori_trim=trim(trim($direktori,'.'),'/'):NULL;
			isset($direktori)?alert('data tersimpan di folder: '.base_url($direktori_trim)):alert('data tersimpan di folder '.base_url("file_tersimpan/"));
			//myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}else{
			alert('Data gagal tersimpan, periksa kembali direktori yang anda masukkan, apakah memang ada?');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function buka_surat(){
		set_error_handler("myErrorHandler");
		isset($_POST['nama_file_buka'])?$file=$_POST['nama_file_buka'].".bbc":alert('Maaf masukkan dulu nama file');
		isset($_POST['direktori_file'])&&$_POST['direktori_file']!==''?$direktori="./".$_POST['direktori_file']."/":$_POST['direktori_file']='';
		
		//rencanakan disini untuk menyimpna handler error:
		$_POST['direktori_file']!==''?$okbro=file_get_contents($direktori.$file):$okbro=file_get_contents("./file_tersimpan/".$file);
		if($okbro){
			echo $okbro;
			//alert("Sumber file: ".base_url().$direktori);
		}else{
			echo('Data gagal diambil, mungkin namanya salah, coba jangan tambahkan ekstensi file yaitu .html, atau direktori salah<br><br>');
			myErrorHandler($errno=NULL, $errstr=NULL, $errfile=NULL, $errline=NULL);
		}
	}

	public function penerima_surat_yang_dibuat($data){
		$file=$_POST['file'];
		$okbro=file_put_contents("./file_tersimpan/".$file,$_POST['mytextarea']);
		if($okbro){
			echo('data tersimpan');}else{
		echo('data gagal tersimpan');}

	}

	public function buat_surat_baru_summernote(){
		echo "
		<link href=\"https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\" rel=\"stylesheet\">
		<script src=\"https://code.jquery.com/jquery-3.4.1.min.js\"></script>
		<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
		<link href=\"https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css\" rel=\"stylesheet\">
		<script src=\"https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js\"></script>
		<!--
		<style src=\"".base_url('/public/summernote/summernote.min.css')."\"></style>
		<script src=\"".base_url('/public/summernote/summernote.min.js')."\"></script>
		-->
		";
		echo "
			<script>
			$(document).ready(function() {
				$('#summernote').summernote();
			});
			</script>
		";
		echo "
			<div >
			<h1>TinyMCE Quick Start Guide</h1>
			<form method='post'>
			<textarea id='summernote' style='width:100%; height:800px;'>Hello, World!</textarea>
			</form>
			</div>
		";
	}
	//===============END FUNGSI PERCOBAAN EDITOR=========================================

	//===============FUNGSI UNTUK PERCOBAAN EXCEL========================================
	public function tes_huruf($batas='z'){
		$i='A';
		$rentang=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40);
		foreach($rentang as $ok){
			echo "<br>$i";
			$i++;
		}
	}

	public function tes_preg($input){
		if((preg_grep("#[a-z]#i",array($input)))==array()) echo "bukan huruf";
	}

	public function tes_tambahkan_setiap_tabel_deng_id(){
		$tables = $this->db->list_tables();
		foreach ($tables as $table)
		{
			if((preg_grep("#tbl_#i",array($table)))!==array()) {
				echo "<br>ALTER TABLE `$table` ADD `id_$table` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id_$table`);";
			}
		}
	}

	public function tes_query(){
		$query = $this->db->query($this->sanitasi_controller('SELECT * FROM identpeg limit 0,10'));
		//foreach ($query->list_fields() as $field){
		//		echo "<br>".$field;
		//}

		foreach ($query->result() as $row){
			echo "<br>".$row->nipbaru;
		}
	}

	public function tes_sanitasi_danger(){
		sanitasi_kata_berbahaya($query);
	}

	public function tes_modulo($a,$n){
		echo $a%$n;
	}

	public function export2excel($nama_file_laporan=NULL,$table,$jumlah_rekord,$mulai,$order='asc',$input_query='',$kolom_cetak=array()){
		$spreadsheet = new Spreadsheet;
		$sheet = $spreadsheet->getActiveSheet();

		if($input_query!==''){
			//alert("INI HASI DARI DALAM ".$input_query);
			
			$tes=sanitasi_kata_berbahaya($input_query);
			
			if($tes){
				alert("Maaf query tidak boleh memuat kata yang otoritasnya selain SELECT");
				exit();
			}
			
			$query_ok = $this->db->query($input_query);
			$fields=array();
			$k=0;
			foreach ($query_ok->list_fields() as $field)
			{
				$fields[$k]=$field;
				$k++;
			}
			//alert(implode(' ',$fields));
			//$ok=implode('_',$fields);
			//alert("INI ok: ".$fields[0]);
			
			
			$sheet->setCellValue('A1',"Hasil Query \"".$input_query."\"");
			$i='A';
			foreach ($fields as $field){
				$sheet->setCellValue($i.'3',$field);
				$i++;
			}
			
			$j=4;
			//$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
			$query = $query_ok;//$this->db->get($table, $jumlah_rekord, $mulai);
			foreach ($query->result() as $row){
				$i='A';
				foreach($fields as $field){
					if((preg_grep("#[a-z]#i",array($row->$field)))==array()&&$row->$field!==''){
						$sheet->setCellValue($i.$j,"'".strval($row->$field)."'");
					}else{
						$sheet->setCellValue($i.$j,strval($row->$field));
					}
					$i++;
				}
				$j++;
			}
			
			$nama_file_laporan==NULL?$filename = 'laporan_query_'.'bankdata'.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			
		}else {
			$fields=array();
			$i=0;
			if($kolom_cetak!==array()){
				foreach($kolom_cetak as $value){
					$fields[$i]=$value;
					$i++;
				}
				$this->db->select($fields);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);

			}else{
				$fields = $this->db->list_fields($table);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);
			}
			//$fields = $this->db->list_fields($table);
			$sheet->setCellValue('A1','Tabel '.ucwords($table));
			$i='A';
			foreach ($fields as $field){
				$sheet->setCellValue($i.'3',$field);
				$i++;
			}
	
			$j=4;
			//$query = $this->db->get($table, $jumlah_rekord, $mulai);
			foreach ($query->result() as $row){
				$i='A';
				foreach($fields as $field){
					if((preg_grep("#[a-z]#i",array($row->$field)))==array()&&$row->$field!==''){
						$sheet->setCellValue($i.$j,"'".strval($row->$field)."'");
					}else{
						$sheet->setCellValue($i.$j,strval($row->$field));
					}
					$i++;
				}
				$j++;
			}
			$nama_file_laporan==NULL?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;

		}


		
		$writer = new Xlsx($spreadsheet);
		
		header('Content-Type: application/vnd.ms-ecxel');
		header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		
	}

	public function export2pdf($nama_file_laporan='',$table,$jumlah_rekord,$mulai,$order='asc',$input_query='',$kolom_cetak=array(),$orientasi='P',$tinggi_hal=800,$lebar_hal=210){
		if($input_query==''){
			$fields=array();
			$i=0;
			if($kolom_cetak!==array()){
				foreach($kolom_cetak as $value){
					$fields[$i]=$value;
					$i++;
				}
				$this->db->select($fields);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);

			}else{
				$fields = $this->db->list_fields($table);
				$order=='desc'?$this->db->order_by($fields[0], 'DESC'):NULL;
				$query = $this->db->get($table, $jumlah_rekord, $mulai);
			}
			

			$i=0;
			foreach ($query->result() as $row){
				$j=0;
				foreach($fields as $field){
					$data[$i][$j]=$row->$field;
					$j++;
				}
				$i++;
			}

			//penentuan panjang tiap-tiap sel:
			$panjang_tiap_sel=array();
			$i=0;
			foreach($fields as $k=>$field){
				//Semua perhitungan disini di dasarkan pada perbandingan untuk 15 karakter ukuran 12 = kira-kira 40 point jarak di pdf.
				strlen($field)>15&&strlen($field)<40?$panjang_tiap_sel[$i]=ceil(strlen($field)*40/15)+ceil(40/15):$panjang_tiap_sel[$i]=40;

				//SETTINGAN INI KHUSUS, TIDAK GENERAL, HANYA BERLAKU UNTUK STRUKTUR DATA BKD PEMPROV SULSEL YANG BERLAKU SEKARANG:
				$field=='NIP'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='KGolRu'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='STMT'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NSTTPP'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='KPej'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NtBAKN'?$panjang_tiap_sel[$i]=22:NULL;
				$field=='gldepan'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kgoldar'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrt'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrw'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='suku'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kskawin'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='kduduk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjpeg'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kstatus'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kagama'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjkel'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='altelp'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='alkoprop'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkokab'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='alkokec'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkodes'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kpos'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='kaparpol'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='npap'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='glblk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='tlahir'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='npap_g'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='nkarpeg'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='naskes'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='ntaspen'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='nkaris_su'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='aljalan'?$panjang_tiap_sel[$i]=30:NULL;

				$i++;
			}

			$panjang_tiap_sel[0]=ceil(7*40/15)+5;
			$fields[0]='id';

			$tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			//$okbro=array();

			//pikirkan bagaimana agar mengikuti ukuran lebar kolom, jangan berpatokan 40
			foreach($data as $k=>$row){
				$max=1;
				$kandidat=0;
				foreach($row as $s=>$isi){
					//obselet:
					//strlen($isi)>15?$kandidat=ceil(strlen($isi)/15):NULL;//kenapa 15? karena untuk panjang sel 40 = kira-kira minimal 15 karakter
					
					//filosofi hitungan ini:
					//satu satuan tinggi sel diambil nilai 6 point.
					//berapa satuan tinggi rekord? = nilai tinggi sel maksimum dari seluruh sel dalam satu rekord.
					//$max =sel dengan tinggi maksimum
					//tinggi aktual sel = $max dikali satuan tinggi sel yaitu 6 point = $max*6
					//cara menghitung $max:
					//hitung $kandidat. strlen($isi)*(40/15) diambil dari perbandingan bahwa (40 panjang aktual sel:15 panjang karakter) sehingga panjang aktual isi sel = (strlen($isi)*(40/15)
					//kemudian $kandidat adalah rasio panjang aktual isi dibagi panjang aktual panjang sel yang ditetapkan sebelumnya, lalu dibulatkan ke atas.
					//menghasilkan $max.
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:NULL;
					//$okbro[$k][$s]=strlen($isi);
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
			//alert("tinggi_tiap_baris: ".implode('  ',$tinggi_tiap_baris));
			$lebar_page=max((array_sum($panjang_tiap_sel)+40),210,$lebar_hal);
			$tinggi_page=$tinggi_hal;

			$nama_file_laporan==''?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			BasicTable_tcpdf($fields,$data,'D',$filename.'.pdf',$nama_satker=''.$this->config->item('nama_opd').' Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban '.$this->config->item('nama_opd').'',$panjang_tiap_sel,$lebar_page,$tinggi_tiap_baris,$tinggi_page,$orientasi);
		}else{
			//alert('OK MASUK BAGIAN QUERY BRO: '.$input_query);
			$tes=sanitasi_kata_berbahaya($input_query);
			
			if($tes){
				alert("Maaf query tidak boleh memuat kata yang otoritasnya selain SELECT");
				exit();
			}
			
			$query_ok = $this->db->query($input_query);
			$fields=array();
			$k=0;
			foreach ($query_ok->list_fields() as $field){
				$fields[$k]=$field;
				$k++;
			}
			//alert(implode('  ',$fields));
			//$ok=implode('_',$fields);
			//alert("INI ok: ".$fields[0]);

			$i=0;
			foreach ($query_ok->result() as $row){
				$j=0;
				foreach($fields as $field){
					$data[$i][$j]=$row->$field;
					$j++;
				}
				$i++;
			}

			//penentuan panjang tiap-tiap sel:
			$panjang_tiap_sel=array();
			$i=0;
			foreach($fields as $k=>$field){
				//Semua perhitungan disini di dasarkan pada perbandingan untuk 15 karakter ukuran 12 = kira-kira 40 point jarak di pdf.
				strlen($field)>15&&strlen($field)<40?$panjang_tiap_sel[$i]=ceil(strlen($field)*40/15)+ceil(40/15):$panjang_tiap_sel[$i]=40;

				//SETTINGAN INI KHUSUS, TIDAK GENERAL, HANYA BERLAKU UNTUK STRUKTUR DATA BKD PEMPROV SULSEL YANG BERLAKU SEKARANG:
				$field=='NIP'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='KGolRu'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='STMT'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NSTTPP'?$panjang_tiap_sel[$i]=18:NULL;
				$field=='KPej'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='NtBAKN'?$panjang_tiap_sel[$i]=22:NULL;
				$field=='gldepan'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kgoldar'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrt'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alrw'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='suku'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kskawin'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='kduduk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjpeg'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kstatus'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kagama'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kjkel'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='altelp'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='alkoprop'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkokab'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='alkokec'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='alkodes'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='kpos'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='kaparpol'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='npap'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='glblk'?$panjang_tiap_sel[$i]=15:NULL;
				$field=='tlahir'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='npap_g'?$panjang_tiap_sel[$i]=15:NULL;

				$field=='nkarpeg'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='naskes'?$panjang_tiap_sel[$i]=30:NULL;
				$field=='ntaspen'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='nkaris_su'?$panjang_tiap_sel[$i]=20:NULL;
				$field=='aljalan'?$panjang_tiap_sel[$i]=30:NULL;

				$i++;
			}

			$panjang_tiap_sel[0]=ceil(7*40/15)+5;
			//$fields[0]='id';

			$tinggi_tiap_baris=array();
			$max=1; //1 = ukuran 1 sel.
			$kandidat=0;
			$o=0;
			//$okbro=array();
			foreach($data as $k=>$row){
				foreach($row as $s=>$isi){
					//obselet:
					//strlen($isi)>15?$kandidat=ceil(strlen($isi)/15):NULL;//kenapa 15? karena untuk panjang sel 40 = kira-kira minimal 15 karakter
					//$kandidat>$max?$max=$kandidat:NULL;
					
					$kandidat=ceil((strlen($isi)*(40/15))/$panjang_tiap_sel[$s]);
					$kandidat>$max?$max=$kandidat:NULL;
					//$okbro[$k][$s]=strlen($isi);
				}
				$tinggi_tiap_baris[$k]=$max*6;
			}
			//alert("tinggi_tiap_baris: ".implode('  ',$tinggi_tiap_baris));
			$lebar_page=max((array_sum($panjang_tiap_sel)+40),210);
			$tinggi_page=800;

			$nama_file_laporan==NULL?$filename = 'laporan_tabel_'.$table.'_'.implode("_",array (date("d-m-Y"),mt_rand (1000,9999),microtime())):$filename=$nama_file_laporan;
			BasicTable_tcpdf($fields,$data,'D','laporan_pdf.pdf',$nama_satker=''.$this->config->item('nama_opd').' Prov. Sulawesi Selatan',$nama_bidang='Ruang Kaban '.$this->config->item('nama_opd').'',$panjang_tiap_sel,$lebar_page,$tinggi_tiap_baris,$tinggi_page,$orientasi);
		}
	}
	

	public function proses_cetak_laporan(){
		if($_POST['luaran']=='excel'){
			if(isset($_POST['query'])&&$_POST['query']!==''){
				//alert('MASUK ATAS BRO');
				$this->export2excel($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],$_POST['query']);
			}else{
				//alert('MASUK BAWAH BRO');
				$fields = $this->db->list_fields($_POST['pilihan_tabel']);
				$kolom_cetak=array();
				foreach($fields as$k=>$field){
					if(isset($_POST[$field]))$kolom_cetak[$k]=$_POST[$field];
				}
				$this->export2excel($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],'',$kolom_cetak);
			}
		}else if($_POST['luaran']=='pdf'){
			if($_POST['luaran']=='pdf'&&$_POST['query']!==''){
				//alert('MASUK ATAS BRO');
				//alert('ISI QUERY '.$_POST['query']);
				$this->export2pdf($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],$_POST['query'],$kolom_cetak=NULL,$_POST['orientasi'],$_POST['tinggi_hal'],$_POST['lebar_hal']); 
			}else{
				//alert('MASUK BAWAH BRO');
				$fields = $this->db->list_fields($_POST['pilihan_tabel']);
				$kolom_cetak=array();
				foreach($fields as$k=>$field){
					if(isset($_POST[$field]))$kolom_cetak[$k]=$_POST[$field];
				}
				
				//alert("orientasi: ".$_POST['orientasi']."  tinggi_hal: ".$_POST['tinggi_hal']."  lebar_hal: ".$_POST['lebar_hal']);
				//alert(implode('  ',$kolom_cetak));
				$this->export2pdf($_POST['nama_file'],$_POST['pilihan_tabel'],$_POST['jumlah_rekord'],$_POST['mulai'],$_POST['urutan_tabel'],'',$kolom_cetak,$_POST['orientasi'],$_POST['tinggi_hal'],$_POST['lebar_hal']);
			}
		}else{
			alert('tipe luaran '.$_POST['luaran'].' masih dalam rencana konstruksi');
		}
	}

	public function cetak_laporan(){
		echo "
			<style>
				.tampilan_standar{
					display:block;
				}
				.tampilan_query{
					display:none;
				}
				.tampilan_pdf{
					display:none;
				}
			</style>
		";


		echo "<h5>Cetak Laporan</h5>";//target='targetprosescetaklaporan'
		echo "
		<label style=\"margin-right:1px;\" onclick=\"$('.tampilan_query').hide();$('.tampilan_standar').show();\"><input type=\"radio\" name=\"luaran\" id=\"standar\" value=\"standar\" checked> <span class=\"badge badge-success\" style=\"margin-top:-21px;\">Laporan Standar</span></label>
		<label style=\"margin-right:1px;\" onclick=\"$('.tampilan_standar').hide();$('.tampilan_query').show();\"><input type=\"radio\" name=\"luaran\" id=\"lanjut\" value=\"lanjut\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">Laporan Lanjut</span></label>
		";
		echo "
			<form  action=\"".site_url('Frontoffice/proses_cetak_laporan')."\" method='post'>
			<div class=\"form-group tampilan_standar\" align=\"left\">
			<label for=\"pilihan_tabel\">Pilih tabel yang hendak dicetak</label>
			<select class=\"form-control\" id=\"pilihan_tabel\" name=\"pilihan_tabel\">
				<option value=\"user\">Pilih nama tabel berikut</option>";
				$tables = $this->db->list_tables();
				foreach ($tables as $table)
				{
						echo "<option value=\"$table\">".ucwords(implode(' ',explode('_',$table)))."</option>";
				}
				
		
		echo "
			</select>
			</div>";
		echo "
			<div class=\"form-group tampilan_query\" align=\"left\">
			<label for=\"mulai\">Buat query untuk dicetak: </label>
			<input type=\"text\" class=\"form-control\" id=\"query\" name=\"query\" >
			</div>";

		
		echo "
			<div class=\"form-group tampilan_standar\" align=\"left\">
			<label for=\"mulai\">Mulai rekord: <input type=\"text\" class=\"form-control\" id=\"mulai\" name=\"mulai\" value=\"0\"></label>
			</div>
			<div class=\"form-group tampilan_standar\" align=\"left\">
			<label for=\"jumlah_rekord\">Jumlah rekord: <input type=\"text\" class=\"form-control\" id=\"jumlah_rekord\" name=\"jumlah_rekord\" value=\"20\"></label>
			</div>
			<div class=\"form-group tampilan_standar\" align=\"left\">
			<label for=\"nama_file\">Nama file yang diberikan (opsional): <input type=\"text\" class=\"form-control\" id=\"nama_file\" name=\"nama_file\"></label>
			</div>
			<div class=\"form-group tampilan_standar\" align=\"left\">
			<label for=\"sampai\">Urutkan tabel sebelum cetak: <select class=\"form-control\" id=\"pilihan_tabel\" name=\"urutan_tabel\">
			<option value=\"asc\">Pilih urutan dalam tabel</option><option value=\"desc\">Descending (Mulai rekord paling akhir)</option><option value=\"asc\">Ascending (Mulai rekord paling pertama)</option></select></label>
			</div>";
		
		echo "
		<div class=\"form-group tampilan_standar\" align=\"left\">
			<a style=\"cursor:pointer;color:white;\" class=\"d-sm-inline-block btn btn-sm btn-success shadow-sm\" id=\"list_kolom\" ><i class=\"fas fa-list fa-sm text-white-50\"></i> Pilih kolom yang mau dicetak [opsional]</a>
		</div>
		";

		echo "
			<center>
			<div id='pra_tabel_list_kolom' style='width:40%;display:none;' align='center' >
			<div class=\"progress\" style=\"margin-top:10px;margin-bottom:10px; height:20px\">
				<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"90\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\">
				mohon tunggu...
				</div>
			</div>
			</div>
			</center>
			<div id=penampil_tabel_list_kolom align=\"center\" style='width:100%;overflow:auto;'></div>
		";

		//Kode ajax untuk tampilkan kolom tabel:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#list_kolom\").click(function(){
                  var loading = $(\"#pra_tabel_list_kolom\");
				  var tampilkan = $(\"#penampil_tabel_list_kolom\");
				  var table=$(\"#pilihan_tabel\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/penampil_list_kolom")."',{ data:table},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
		<div class=\"form-group tampilan_standar tampilan_pdf\" align=\"left\">
		<label for=\"orientasi\">Orientasi Halaman (Portrait | Landscape): 
			<select class=\"form-control\" id=\"orientasi\" name=\"orientasi\">
			<option value=\"P\" selected>Portrait</option>
			<option value=\"L\">Landscape</option>
			</select></label>
		</div>

		<div class=\"form-group tampilan_standar tampilan_pdf\" align=\"left\">
			<label for=\"lebar_hal\">Lebar halaman (mm): <input type=\"text\" class=\"form-control\" id=\"lebar_hal\" name=\"lebar_hal\" value=\"210\"></label>
			</div>

		<div class=\"form-group tampilan_standar tampilan_pdf\" align=\"left\">
		<label for=\"tinggi_hal\">Tinggi halaman (mm): <input type=\"text\" class=\"form-control\" id=\"tinggi_hal\" name=\"tinggi_hal\" value=\"800\"></label>
		</div>
		";

		echo "
			<script>
              $(document).ready(function(){
                $(\"#luaran_pdf\").click(function(){
					$(\".tampilan_pdf\").show();
				  });
				$(\"#luaran_excel\").click(function(){
					$(\".tampilan_pdf\").hide();
				  });
				$(\"#luaran_json\").click(function(){
					$(\".tampilan_pdf\").hide();
					alert('Maaf Tipe Json masih dalam rencana konstruksi');
				  });
				$(\"#luaran_csv\").click(function(){
					$(\".tampilan_pdf\").hide();
					alert('Maaf Tipe CSV masih dalam rencana konstruksi');
				  });
				$(\"#luaran_xml\").click(function(){
					$(\".tampilan_pdf\").hide();
					alert('Maaf Tipe XML masih dalam rencana konstruksi');
				  });
				});
			</script>
        ";

		echo "
			<div class=\"radio\">
			<label style=\"margin-right:1px;\" id=\"luaran_excel\"><input type=\"radio\" name=\"luaran\" value=\"excel\" checked> <span class=\"badge badge-primary\" style=\"margin-top:-21px;\">Excel</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_pdf\"><input type=\"radio\" name=\"luaran\" value=\"pdf\"> <span class=\"badge badge-warning\" style=\"margin-top:-21px;\">PDF</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_json\"><input type=\"radio\" name=\"luaran\" value=\"json\"> <span class=\"badge badge-success\" style=\"margin-top:-21px;\">Json</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_csv\"><input type=\"radio\" name=\"luaran\" value=\"csv\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">CSV</span></label>
			<label style=\"margin-right:1px;\" id=\"luaran_xml\"><input type=\"radio\" name=\"luaran\" value=\"xml\"> <span class=\"badge badge-info\" style=\"margin-top:-21px;\">XML</span></label>
			</div>
			<button type=\"submit\" class=\"btn btn-primary\" style=\"width:100%;\"><i class=\"fas fa-paper-plane fa-sm \"></i> Export</button>
		</form> 
		";
		
		echo "<iframe name='targetprosescetaklaporan' width='0' height='0' frameborder='0'></iframe>";
	}

	public function penampil_list_kolom(){
		$fields = $this->db->list_fields($_POST['data']);
		$i=0;
		foreach($fields as $field){
			echo "<div class='checkbox tampilan_standar' align='left'>";
			echo "<label><input type='checkbox' value=\"$field\" name=\"$field\"> <span class=\"badge badge-info\" style=\"margin-top:-20px;\"><i class=\"fas fa-check fa-sm \"></i> $field</span></label>";
			echo "</div>";
			$i++;
		}
		//echo "<input type='hidden' name='jumlah_kolom_cetak' value=\"".($i-1)."\">";
	}

	//===============END FUNGSI PERCOBAAN EXCEL==========================================

	//===============FUNGSI UNTUK SEARCHING GENERAL DI NAVBAR ATAS=======================
	/**
	 * Filosofi dari rencana fungsi ini adalah ketika kita melakukan searching, maka seraching terjadi di sisi server
	 * mencari seluruh tabel dan seluruh kolom yang memuat kata tersebut, lalu me list nya dalam list aktif yang kemudian
	 * bisa menampilkan tabel bersangkutan jika di klik.
	 * Tabel ditampilkan di ruang utama.
	 */

	public function search_general($table='identpeg'){
		echo "<h5>Hasil pencarian terdapat pada tabel dan kolom berikut di basisdata:</h5>";

		//$this->db->select();
		//$this->db->where($dataDek['nama_kolom'], $dataDek['nilai_kolom']);
		//$query = $this->db->get($tableDek);

		$tables = $this->db->list_tables();
		
		//echo $this->db->count_all_results();
		echo "<table class=\"table table-hover table-striped\">";
		$total_count=0;
		foreach ($tables as $table){
			$count=0;
			$fields = $this->db->list_fields($table);
			foreach ($fields as $field){
				$this->db->or_like($field, $_POST['data']);
			}
				$this->db->from($table);
				$count=$this->db->count_all_results();
			if($count>0){
				echo "<tr align='left'>";
				echo "<td style='margin-left:20px;' >Kata pencarian <span class='badge badge-success'>".$_POST['data']."</span> terdapat di dalam tabel $table untuk seluruh kolom sebanyak <span class='badge badge-danger'>".$count."</span> rekord </td>";
				echo "<td><button class='btn btn-xs btn-primary' id='cari_$table'>Rincian</button></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='2'>
						<center>
							<div id='pra_$table' style='width:40%;display:none;' align='center' >
							<div class='progress' style='margin-top:10px; height:30px'>
							<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
							mohon tunggu, sedang menghitung data...
							</div>
							</div>
							</div>
						</center>
						<div id=penampil_$table align='center' style='width:100%;overflow:auto;'></div>
					 </td>";
				echo "</tr>";
			}
			echo "
				<script>      
					$(document).ready(function(){
						$(\"#cari_$table\").click(function(){
							var loading = $(\"#pra_$table\");
							var tampilkan = $(\"#penampil_$table\");
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/lihat_hasil_pencarian/".$table."/".$_POST['data'])."',{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
						});
					});
				</script>
			";
			$total_count=$total_count+$count;
		}
		if($total_count==0)echo "<tr><td align='center'><span class='badge badge-danger'>Tidak ditemukan</span> hasil pencarian yang sesuai di seluruh tabel basisdata</td></tr>";
		echo "</table>";
		echo "<div>Total hasil pencarian adalah <span class='badge badge-danger'>$total_count</span> rekord di seluruh tabel basisdata</div>";
		
	 }

	 public function lihat_hasil_pencarian($table,$data){
		echo "<table class=\"table\">";
		$fields = $this->db->list_fields($table);
			foreach ($fields as $field){
				$this->db->like($field, $data);
				$this->db->from($table);
				$count=$this->db->count_all_results();
				if($count>0){
					echo "<tr align='left'>";
					echo "<td style='margin-left:20px;' >Kata <span class='badge badge-success'>".$data."</span> pada kolom <span class='badge badge-warning'>$field</span> di tabel <span class='badge badge-info'>$table</span sebanyak <span class='badge badge-danger'>".$count."</span> rekord </td>";
					echo "<td><button class='btn btn-xs btn-success' id=\"cari_".$table."_".$field."\" data-toggle=\"modal\" data-target=\"#myModal_suratbaru\">Lihat</button></td>";
					echo "</tr>";
				}
				//Kode untuk id=lakukanpencarian
				echo "
					<script>
					$(document).ready(function(){
						$(\"#cari_".$table."_".$field."\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=20;
						var page=1;
						var page_awal=1;
						var jumlah_page_tampil=4;
						var kolom_cari=\"".$field."\";
						var nilai_kolom_cari=\"".$data."\";
			
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid_search/".$table."/".$fields[0]."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			}
		echo "</table>";//xx7
				
				
	 }

	 public function tampil_tabel_cruid_search($table,$nama_kolom_id,$order='desc',$limit=20,$currentpage=1,$page_awal=1,$jumlah_page_tampil=4,$mode=TRUE,$kolom_cari=NULL,$nilai_kolom_cari=NULL){
		$awal=($currentpage-1)*$limit;
		$numrekord=$this->db->count_all($table);
		$jumlah_halaman=ceil($numrekord/$limit);

		//echo "INI JUMLAH HALAMAN: ".$jumlah_halaman;
		//echo "<br>INI mode: ".$mode;
		//echo "<br>INI kolom_cari: ".$kolom_cari;
		//echo "<br>INI nilai_kolom_cari: ".$nilai_kolom_cari;

		echo "<div align=left>Basisdata >> ".ucwords(implode(' ',explode('_',$table)))." >> Halaman ".$currentpage."</div>";
		echo "<h4>Kelola Tabel ".ucwords(implode(' ',explode('_',$table)))."</h4>";
		echo "<hr><div align=right>";
		echo "<button style=\"position:absolute; left:11px;\" id=\"tambah_data\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#modal_tambah_data\">Tambahkan data +</button>";
		echo "<button id=\"pencarian_lanjut_atas\" class=\"btn btn-xs btn-info\" data-toggle=\"modal\" data-target=\"#searchmodal\">Pencarian Lanjut</button>";
		echo "</div><hr>";
		
		//Kode untuk tambah data:
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tambah_data\").click(function(){
                  var loading = $(\"#pra_modal_tambah_data\");
				  var tampilkan = $(\"#penampil_modal_tambah_data\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tambah_data/".$table)."',{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
        ";

		echo "
			<!-- Modal Tambah Data -->
			<div class='modal fade' id='modal_tambah_data' role='dialog' style='z-index:100000;'>
				<div class='modal-dialog modal-lg'>
				
				<!-- Modal content-->
				<div class='modal-content' ".$this->config->item('style_modal_admin').">
					<div class='modal-header'>
					<h4 class='modal-title'>".$this->config->item('nama_opd')." Provinsi Sulawesi Selatan</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
					<center>
					<div id='pra_modal_tambah_data' style='width:65%;' align='center' >
					<i class='fa-3x fas fa-spinner fa-pulse' ".$this->config->item('style_progres_bulat_admin')."></i>
					<!--
					<div class='progress' style='margin-top:50px; height:20px'>
						<div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='90' aria-valuemin='0' aria-valuemax='100' style='width:100%'>
						mohon tunggu...
						</div>
					</div>
					-->
					</center>
					<div id=penampil_modal_tambah_data align='center' style='width:100%;'></div>
					</div>
					<div class='modal-footer'>
					<button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
					</div>
				</div>
				
				</div>
			</div>
		";

		echo "
			<style>
				#myInput{
					width:30%;
				}
				#quantity{
					margin-left:5px;
					width:70px;
				}
				#tampilbaris{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#myInput{
						width:100%;
					}
					#quantity{
						margin-left:0px;
						width:40%;
					}
					#tampilbaris{
						margin-left:0px;
						width:59%;
					}
				  }
			</style>
			<script>
				$(document).ready(function(){
				$(\"#myInput\").on(\"keyup\", function() {
					var value = $(this).val().toLowerCase();
					$(\"#myTable tr\").filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
					});
				});
				});
			</script>
				<div align=left>
				<label for=\"quantity\" style=\"float:left;line-height:2.2;\">Tampilkan jumlah maksimal rekord: </label>
				<input type=\"number\" class=\"form-control\" id=\"quantity\" name=\"quantity\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-info\" id=\"tampilbaris\" style=\"height:35px;\">Tampilkan</button>
				<input type=\"text\" class=\"form-control\" id=\"myInput\" style=\"float:right;height:35px;min-width:100px;\" placeholder=\"Filter...\">
				</div>
		";
		echo "
			<script>
              $(document).ready(function(){
                $(\"#tampilbaris\").click(function(){
                  var loading = $(\"#pra_myModal_suratbaru\");
				  var tampilkan = $(\"#penampil_myModal_suratbaru\");
				  var limit=$(\"#quantity\").val();
                  tampilkan.hide();
                  loading.fadeIn(); 
                  $.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit,{ data:\"okbro\"},
                  function(data,status){
                    loading.fadeOut();
                    tampilkan.html(data);
                    tampilkan.fadeIn(2000);
                  });
                });
				});
			</script>
		";

		$mode==NULL?$query=$this->sanitasi_controller("select * from $table order by $nama_kolom_id $order limit $awal,$limit"):$query=$this->sanitasi_controller("select * from $table where $kolom_cari LIKE ")."'%".$this->sanitasi_controller($nilai_kolom_cari)."%'".$this->sanitasi_controller(" order by $nama_kolom_id $order limit 0,$limit");
		//echo "<br>INI query: ".$query;
		//$query=$this->sanitasi_controller($query);
		//echo "<br> INI sehabis disanitasi: ".$query;
		$this->penampil_tabel_no_foto_controller($table,$nama_kolom_id,$array_atribut=array("","id=\"myTable\" class=\"table table-condensed table-hover table-striped\"",""),$query,$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		echo "
			<style>
				#blokpage{
					display:flex; justify-content:center;
				}
				@media screen and (max-width: 480px) {
					#blokpage{
						justify-content:left;
					}
				}
			</style>
			<div id=\"blokpage\">
			<nav aria-label='...'>
			<ul class='pagination'>";

			//Siapkan nomor-nomor page yang mau ditampilkan
			$array_page=NULL;
			$j=0;
			for($i=$page_awal;$i<=($page_awal+($jumlah_page_tampil-1));$i++){
				$array_page[$j]=$i;
				if($limit*$i>$numrekord)break;
				$j++;
			}
			//print_r($array_page);;
				
			if($currentpage<=$jumlah_page_tampil){
				echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
			}else{
				echo "<li class='page-item' id='Previous'><a class='page-link' href='#'>Previous</a></li>";
				$current_pagePrevious=$array_page[0]-1;
				$page_awalPrevious=$current_pagePrevious-($jumlah_page_tampil-1);
				echo "
						<script>
						$(document).ready(function(){
							$(\"#Previous\").click(function(){
							var loading = $(\"#pra_myModal_suratbaru\");
							var tampilkan = $(\"#penampil_myModal_suratbaru\");
							var limit=$(\"#quantity\").val();
							tampilkan.hide();
							loading.fadeIn(); 
							$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_pagePrevious+'/'+$page_awalPrevious+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
							function(data,status){
								loading.fadeOut();
								tampilkan.html(data);
								tampilkan.fadeIn(2000);
							});
							});
							});
						</script>
				";
			}

			
			//echo "<br>INI current_page: ".$currentpage;
			//echo "<br>INI page_awal: ".$page_awal;

			//Tampilkan nomor-nomor halaman di paging
			for($i=$array_page[0];$i<=$array_page[sizeof($array_page)-1];$i++){
				if($currentpage==$i){
					//echo "<br>INI DALAM currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item active' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";				
				}else{
					//echo "<br>INI LUAR currentpage: ".$currentpage;
					//echo "<br>INI i: ".$i;
					echo "<li class='page-item' id=\"page$i\"><a class='page-link' href='#'>$i</a></li>";
					echo "
					<script>
					$(document).ready(function(){
						$(\"#page$i\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$i+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
					";
				}
				//if($i==$jumlah_page_tampil){break;}
			}
		
		//echo "<br>INI jumlah_halaman: ".$jumlah_halaman;
		//echo "<br>INI jumlah_page_tampil: ".$jumlah_page_tampil;
		//echo "<br>INI currentpage: ".$currentpage;
		//echo "<br>INI TOTAL HITUNG: ".($array_page[0]+$jumlah_page_tampil-1);
		//if($jumlah_halaman>$jumlah_page_tampil && !($currentpage==$jumlah_halaman)){

		//Kode untuk tombol Next:
		if(($array_page[0]+$jumlah_page_tampil-1)<$jumlah_halaman){
			echo "<li class='page-item' id=\"Next\"><a class='page-link' href='#'>Next</a></li>";
			$current_page=$array_page[sizeof($array_page)-1]+1;
			$page_awal=$current_page;
			echo "
					<script>
					$(document).ready(function(){
						$(\"#Next\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+$current_page+'/'+$page_awal+'/'+$jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
			";
		}
		else{
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
		}

		echo "
			<li class='page-item disabled'><a class='page-link' href='#'>$jumlah_halaman page</a></li>
			<li class='page-item disabled'><a class='page-link' href='#'>$numrekord rekord</a></li>
			</ul>
			</nav>
			</div>
		";

		//go to page:
		echo "
			<style>
				#gotopage{
					margin-left:5px;
					width:70px;
				}
				#go{
					margin-left:5px;
				}
				@media screen and (max-width: 480px) {
					#pencarianlanjut{
						width:100%;
					}
					#gotopage{
						margin-left:0px;
						width:40%;
					}
					#go{
						margin-left:3px;
					}
				}
			</style>
				<div align=left>
				<div style=\"float:left;\">
				<label for=\"gotopage\" style=\"float:left;line-height:2.2;\">Page: </label>
				<input type=\"number\" class=\"form-control\" id=\"gotopage\" name=\"gotopage\" min=\"1\" value=\"".$currentpage."\" style=\";height:35px;float:left;\">
				<button class=\"btn btn-xs btn-primary\" id=\"go\" style=\"height:35px;width:40px;\">go</button>
				</div>
				<button class=\"btn btn-xs btn-primary\" id=\"pencarianlanjut\" data-toggle=\"modal\" data-target=\"#searchmodal\" style=\"height:35px;float:right;\">Pencarian Lanjut</button>
				</div>
			";

			//Kode untuk id=gotopage dan id=go 
			echo "
					<script>
					$(document).ready(function(){
						$(\"#go\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#quantity\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";
			
			//Modal untuk pencarian lanjut:
			$fields = $this->model_frommyframework->penarik_semua_nama_kolom_sebuah_tabel($table);
			echo "
				<!-- Modal Searching-->
				<div class=\"modal fade\" id=\"searchmodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\" aria-hidden=\"true\">
					<div class=\"modal-dialog\" role=\"document\">
					<div class=\"modal-content\" ".$this->config->item('style_modal_admin').">
						<div class=\"modal-header\">
						<h5 class=\"modal-title\" id=\"exampleModalLabel\">Mode Pencarian Lanjut</h5>
						<button class=\"close\" type=\"button\" data-dismiss=\"modal\" aria-label=\"Close\">
							<span aria-hidden=\"true\">×</span>
						</button>
						</div>
						<div class=\"modal-body\" style=\"display:flex; justify-content:center;flex-wrap: wrap;\">
						
						<input class=\"form-control\" type=\"text\" id=\"nilai_kolom_cari\" placeholder=\"Search...\"> 
						<button class=\"btn btn-xs\" disabled>Berdasarkan</button> 
						<select class=\"form-control\" id=\"kolom_cari\" name=\"kolom_cari\">";
						echo "<option value=".$fields[0].">Pilih nama kolom tabel</option>";
						foreach ($fields as $field){
							echo "<option value=\"$field\">".ucwords(implode(' ',explode('_',$field)))."</option>";
						}
						echo "
						</select>
						</div>
						<hr>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<label for=\"limicari\" style=\"float:left;line-height:2.2;\">Jumlah maksimal rekord: </label>
							<input type=\"number\" class=\"form-control\" id=\"limicari\" name=\"limicari\" min=\"1\" value=\"".$limit."\" max=\"100000\" style=\";height:35px;float:left;width:75px;\">
						</div>
						<div style=\"display:flex; justify-content:center;padding-bottom:20px;\">
							<button class=\"btn btn-xs btn-danger\" id=\"lakukanpencarian\" data-dismiss=\"modal\">Lakukan pencarian</button>
						</div>
						<div class=\"modal-footer\">
						<button class=\"btn btn-secondary\" type=\"button\" data-dismiss=\"modal\">Cancel</button>
						</div>
					</div>
					</div>
				</div>
			";

			//Kode untuk id=lakukanpencarian
			echo "
					<script>
					$(document).ready(function(){
						$(\"#lakukanpencarian\").click(function(){
						var loading = $(\"#pra_myModal_suratbaru\");
						var tampilkan = $(\"#penampil_myModal_suratbaru\");
						var limit=$(\"#limicari\").val();
						var page=$(\"#gotopage\").val();
						var page_awal=1;
						var jumlah_page_tampil=$jumlah_page_tampil;
						var kolom_cari=$(\"#kolom_cari\").val();
						var nilai_kolom_cari=$(\"#nilai_kolom_cari\").val();

						tampilkan.hide();
						loading.fadeIn(); 
						$.post('".site_url("/Frontoffice/tampil_tabel_cruid/".$table."/".$nama_kolom_id."/desc/")."'+limit+'/'+page+'/'+page_awal+'/'+jumlah_page_tampil+'/TRUE/'+kolom_cari+'/'+nilai_kolom_cari,{ data:\"okbro\"},
						function(data,status){
							loading.fadeOut();
							tampilkan.html(data);
							tampilkan.fadeIn(2000);
						});
						});
						});
					</script>
				";

	}

	 

	 //=====================END FUNGSI PENCARIAN=========================================
	
	//===============FUNGSI KHUSUS UNTUK MIGRASI=========================================
	public function migrasi_password_pegawai(){
		//baca semua nipbaru:
		$query = $this->db->get('identpeg');
		$i=0;
		foreach ($query->result() as $row)
		{
				if($i>8790){
					$this->db->set('password',password_hash($row->nipbaru, PASSWORD_BCRYPT));
					$this->db->where('nipbaru',$row->nipbaru);
					$this->db->update('identpeg');
				}
				$i++;
		}
	}

	public function cek(){
		//baca semua nipbaru:
		$query = $this->db->get('identpeg');
		$i=0;
		$tanda=true;
		foreach ($query->result() as $row)
		{
				echo "<br>$i: nipbaru:".$row->nipbaru."   username: ".$row->username."   password: ".$row->password;
				if($tanda && $row->password=='') {$simpan=$i;$tanda=false;}
				$i++;
		}
		echo "<br>INI BATASNYA BRO".$simpan;
	}

	//=====================================TAMBAHAN UNTUK UBAH PASSWORD==================================

	public function proses_ubah_password($pesan_password_lama=NULL){
		if($_POST['password_lama']||$pesan_password_lama!==NULL){
			if(($_POST['password']&&$_POST['password_ulang'])||$pesan_password_lama!==NULL){
				//tes apakah password dan password ulang sama?
				if(($_POST['password']==$_POST['password_ulang'])||$pesan_password_lama!==NULL){
					
					if($pesan_password_lama==NULL){
						$table=$this->enkripsi->enkapsulasiData($_POST['table']);
						$data=$this->enkripsi->enkapsulasiData(array('nama_kolom'=>$_POST['nama_kolom'],'nilai'=>$_POST['nilai_kolom']));
						$data_password=$this->enkripsi->enkapsulasiData(array('nama_kolom'=>'password','nilai'=>$_POST['password_lama']));
						$data_password_baru=$this->enkripsi->enkapsulasiData(array('nama_kolom'=>'password','nilai'=>$_POST['password']));
						$token=$this->enkripsi->enkapsulasiData('andisinra');
						redirect($this->config->item('bank_data')."/index.php/Frontoffice/cek_data_general/$table/$data/$data_password/$data_password_baru/$token");
					}else{
						//ketik disini jika password lama cocok atau tidak.
						if($pesan_password_lama=='cocok'){
							alert("Password telah diubah");
							//redirect("https://localhost/admin_bankdata/index.php/Frontoffice/ubah_data_general/$table/$data/$data_password/$token");
							
						}else if($pesan_password_lama=='tidakcocok'){
							//jika tidak cocok, buat alert bahwa password lama tidak cocok dengan basisdata di bankdata.
							alert("2:Maaf password lama anda tidak cocok dengan data di bank data, silahkan ulangi submit");
						}else{
							alert("Terjadi error data yang dikirim balik dari bank data, mohon ulangi submit");
						}
					}
					
				}else{
					alert("Mohon mengulangi ketik password baru di kolom ulangi password, karena tidak cocok");
				}
			}else{
				alert("Silahkan masukkan terlebih dulu password baru dan ulangi ketik di kolom ulangi password");
			}
		}else{
			alert("Silahkan memasukkan terlebih dulu password lama anda");
		}
	}
	
	public function ubah_password_pegawai($nipbaru){
		echo "<h5>Form Ubah Password</h5>";

		echo "
		<style>
			.pass_show{position: relative} 
			.pass_show .ptxt { 
				position: absolute; 
				top: 50%; 
				right: 10px;
				color: #f36c01;
				margin-top: -10px;
				cursor: pointer; 
				transition: .3s ease all;
			} 
			.pass_show .ptxt:hover{color: #333333;} 
		</style>
		";

		echo "
		<script>
			$(document).ready(function(){
				$('.pass_show').append('<span class=\"ptxt\">Show</span>');  
				});
				$(document).on('click','.pass_show .ptxt', function(){ 
				$(this).text($(this).text() == \"Show\" ? \"Hide\" : \"Show\"); 
				$(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 
				}); 
		</script> 
		";//

		echo "
		<form target=\"targetubahpassword\"  action=\"".site_url('Akuntamupegawai/proses_ubah_password')."\" method=\"post\" style=\"width:90%;\">
			
			<div align=left><label for=\"password_lama\">Masukkan password lama anda:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"password_lama\" name=\"password_lama\">
			</div>
			
			<div align=left><label for=\"password\" >Masukkan password baru:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\">
			</div>

			<div align=left><label for=\"password_ulang\" >Masukkan ulang password baru:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"password_ulang\" name=\"password_ulang\">
			</div>

			<input type=\"hidden\" class=\"form-control\" id=\"table_pwd\" name=\"table\" value=\"identpeg\" >
			<input type=\"hidden\" class=\"form-control\" id=\"nipbaru\" name=\"nama_kolom\" value=\"nipbaru\" >
			<input type=\"hidden\" class=\"form-control\" id=\"nilai_kolom\" name=\"nilai_kolom\" value=\"".$nipbaru."\" >
			<button type=\"submit\" class=\"btn btn-primary\" style=\"width:100%;\">Submit</button>
		</form> 
		";
		echo "<iframe name='targetubahpassword' width='0' height='0' frameborder='0'></iframe>";
	}
	
	public function proses_ubah_password_tamu(){
		if($_POST['password_lama']){
			if(($_POST['password']&&$_POST['password_ulang'])){
				//tes apakah password dan password ulang sama?
				if($_POST['password']==$_POST['password_ulang']){
						$this->db->select('password');
						$this->db->where('username', $_POST['username']);
						$query = $this->db->get('tamu');
						foreach($query->result() as $row){
							if(password_verify($_POST['password_lama'],$row->password)){//ingat untuk kelak menyimpan handling error disini atau di dalam fungsi update_style_CI
								$this->model_frommyframework->update_style_CI('tamu',array('nama_kolom'=>'username', 'nilai'=>$_POST['username']),array('password'=>password_hash($_POST['password'],PASSWORD_BCRYPT)));
							}else{
								alert("Maaf password lama anda tidak cocok dengan data di bank data, silahkan ulangi submit");
							}
						}
				}else{
					alert("Mohon mengulangi ketik password baru di kolom ulangi password, karena tidak cocok");
				}
			}else{
				alert("Silahkan masukkan terlebih dulu password baru dan ulangi ketik di kolom ulangi password");
			}
		}else{
			alert("Silahkan memasukkan terlebih dulu password lama anda");
		}
	}

	public function ubah_password_tamu($username=NULL){
		echo "<h5>Form Ubah Password</h5>";

		echo "
		<style>
			.pass_show{position: relative} 
			.pass_show .ptxt { 
				position: absolute; 
				top: 50%; 
				right: 10px;
				color: #f36c01;
				margin-top: -10px;
				cursor: pointer; 
				transition: .3s ease all;
			} 
			.pass_show .ptxt:hover{color: #333333;} 
		</style>
		";

		echo "
		<script>
			$(document).ready(function(){
				$('.pass_show').append('<span class=\"ptxt\">Show</span>');  
				});
				$(document).on('click','.pass_show .ptxt', function(){ 
				$(this).text($(this).text() == \"Show\" ? \"Hide\" : \"Show\"); 
				$(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; }); 
				}); 
		</script> 
		";//

		echo "
		<form target=\"targetubahpassword\"  action=\"".site_url('Akuntamupegawai/proses_ubah_password_tamu')."\" method=\"post\" style=\"width:90%;\">
			
			<div align=left><label for=\"password_lama\">Masukkan password lama anda:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"password_lama\" name=\"password_lama\">
			</div>
			
			<div align=left><label for=\"pwd\" >Masukkan password baru:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"pwd\" name=\"password\">
			</div>

			<div align=left><label for=\"pwd_ulang\" >Masukkan ulang password baru:</label></div>
			<div class=\"form-group pass_show\" align=left>
			<input type=\"password\" class=\"form-control\" id=\"pwd_ulang\" name=\"password_ulang\">
			</div>

			<input type=\"hidden\" class=\"form-control\" id=\"nilai_kolom\" name=\"username\" value=\"".$username."\" >
			<button type=\"submit\" class=\"btn btn-primary\" style=\"width:100%;\">Submit</button>
		</form> 
		";
		echo "<iframe name='targetubahpassword' width='0' height='0' frameborder='0'></iframe>";


		/*
		//bagian kirim
		$dataEng=$this->enkripsi->enkapsulasiData($data);
		$tableEng=$this->enkripsi->enkapsulasiData('identpeg');
		$token=$this->enkripsi->enkapsulasiData('andisinra');
		redirect("http://localhost/admin_bankdata/index.php/Frontoffice/ubah_data_general/$tableEng/$dataEng/$token");
		*/
	}

	//================================END TAMBAHAN UNTUK UBAH PASSWORD==============================================

	public function index_unggah(){
		//cek session, jika ada langsung arahkan ke agamas
		$user = $this->session->userdata('user_frontoffice');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');
        if (($user!==FALSE)&&($str==$hash))
        {   
			$this->load->view("index");
		} else {
			//alert("Maaf session anda sudah kadaluarsa");
			redirect('Akuntamupegawai/index');
		}
	}

	//========TEMPAT TAMBAHAN DILUAR KELAS Frontoffice=========================================================================================
	public function tampilkan_profil_tamupegawai($username){
		$this->viewfrommyframework->penampil_tabel_akun_tamu_pegawai ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from tamu where username=\"".$username."\"",$submenu='',$kolom_direktori='direktori_foto',$direktori_avatar='/public/img/no-image.jpg');
	} 

	public function tampilkan_profil_pegawai($nipbaru=NULL){
		$user_pegawai=unserialize($this->session->userdata('user_pegawai'))['nipbaru'][0];
		//print_r($user_pegawai);
		/*
		foreach($user_pegawai as $key=>$unit){
			if(!is_int($key)){
				$user[$key]=$unit;
			}
		}
		*/
		$this->viewfrommyframework->penampil_tabel_akun_pegawai ($array_atribut=array(""," class=\"table table-bordered\"",""),$user_pegawai,$submenu='',$kolom_direktori='direktori_foto',$direktori_avatar='/public/img/no-image.jpg');
	} 
	
	//========END TAMBAHAN DILUAR KELAS Frontoffice============================================================================================

    //===============TES OPEN PDF==================
    public function tesopenpdf($src_ok){
		$src_ok=explode("/",$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($src_ok)));
		$src_berkas=NULL;
		foreach($src_ok as $key=>$k){
			if($key!==0){$src_berkas=$src_berkas."/".$k;}
		}
		//echo "INI DIA BRO src_ok: ".$src_berkas;
		if($src_berkas){
			echo "<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".base_url($src_berkas)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:500px;\"></iframe>";
		}else {
			echo "MAAF TIDAK ADA FILE YANG DIUNGGAH";
		}
    }

    //===============END TES OPEN PDF==============

	public function tampilkan_tabel(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_masuk_frontoffice($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_masuk order by idsurat_masuk desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	public function tampilkan_tabel_surat_terusan(){
		//$Recordset=$this->user_defined_query_controller_as_array($query='select * from surat_masuk',$token="andisinra");
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_terusan($array_atribut=array(""," class=\"table table-bordered\"",""),$query='select * from surat_terusan order by idsurat_terusan desc',$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}	

	
	public function tampilkan_tabel_surat_terusan_di_akun_tamu($username){
		$Recordset=$this->user_defined_query_controller_as_array($query="select idtamu from tamu where username=\"".$username."\"",$token="andisinra");
		//print_r($Recordset);
		$noreg=$Recordset[0]['idtamu'];
		//echo "INI BRO idtamu: ".$noreg;
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_balasan_akun($array_atribut=array(""," class=\"table table-bordered\"",""),$query="select * from surat_terusan where no_registrasi_tamu=\"".$noreg."\" order by idsurat_terusan desc",$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	public function tampilkan_tabel_surat_terusan_di_akun_pegawai($nipbaru){
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_balasan_akun($array_atribut=array(""," class=\"table table-bordered\"",""),$query="select * from surat_terusan where nip=\"".$nipbaru."\" order by idsurat_terusan desc",$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	public function tampilkan_tabel_surat_terupload_di_akun_pegawai($nipbaru){
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_balasan_akun($array_atribut=array(""," class=\"table table-bordered\"",""),$query="select * from surat_masuk where nip=\"".$nipbaru."\" order by idsurat_masuk desc",$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	public function tampilkan_tabel_surat_dibalas_di_akun_pegawai($nipbaru){
		$this->viewfrommyframework->penampil_tabel_no_foto_untuk_surat_balasan_akun($array_atribut=array(""," class=\"table table-bordered\"",""),$query="select * from surat_balasan_tamupegawai where ditujukan_ke=\"".$nipbaru."\" order by idsurat_balasan desc",$submenu='',$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}
	
	


	public function gerbang($pilihan){
		switch ($pilihan) {
			case ("rincian_pegawai_table_tab") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				echo "<h3>Rincian Data Pegawai</h3>";
				$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from identpeg where nipbaru=".$json->nipbaru,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
			break;
			
			//Bagian ini adalah fungsi yang bereaksi terhadap tombol "verifikasi" di halaman admin frontoffice. Memunculkan rincian surat
			//dan memiliki tombol teruskan surat yang memicu fungsi persiapan yaitu fungsi teruskan_surat().
			case ("rincian_penampil_tabel") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS</span>";
					$tabel="surat_masuk";
					$coba=array();
					$id='idsurat_masuk';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}

					$coba[17][7]='Sekretariat '.$this->config->item('nama_opd').'';
					$coba[19][7]='dibaca';
					$coba[21][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[28][4]='';
					$coba[28][0]='combo_database';
					$coba[28][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[28][8]=$surat[0][28];
					$coba[18][4]='';
					$coba[18][0]='area';
					$coba[19][4]='';
					$coba[19][0]='combo_database';
					$coba[19][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[17][4]='';
					$coba[17][6]='<b>Diteruskan ke</b>';
					$coba[17][0]='combo_database';
					$coba[17][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan ke Sekretariat','');
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			case ("rincian_penampil_tabel_terusan") :
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from surat_terusan where idsurat_terusan=".$json->idsurat_terusan,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS</span>";
					$tabel="surat_masuk";
					$coba=array();
					$id='idsurat_masuk';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}

					
					//$coba[17][7]='Sekretariat '.$this->config->item('nama_opd').'';
					$coba[19][7]='dibaca';
					$coba[21][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[28][4]='';
					$coba[28][0]='combo_database';
					$coba[28][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[28][8]=$surat[0][28];
					$coba[18][4]='';
					$coba[18][0]='area';
					$coba[19][4]='';
					$coba[19][0]='combo_database';
					$coba[19][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[17][4]='';
					$coba[17][6]='<b>Diteruskan ke</b>';
					$coba[17][0]='combo_database';
					$coba[17][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Frontoffice/teruskan_surat/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			case ("rincian_penampil_tabel_akun_terusan") :
				echo "OK BRO MASUK";
				/*
				$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$surat=$this->user_defined_query_controller_as_array($query="select * from tamu where username=".$json->username,$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">RINCIAN SURAT DAN BERKAS BALASAN</span>";
					$tabel="tamu";
					$coba=array();
					$id='idtamu';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][4]=' readonly ';
					}

					
					//$coba[17][7]='Sekretariat '.$this->config->item('nama_opd').'';
					/*
					$coba[19][7]='dibaca';
					$coba[21][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[28][4]='';
					$coba[28][0]='combo_database';
					$coba[28][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[28][8]=$surat[0][28];
					$coba[18][4]='';
					$coba[18][0]='area';
					$coba[19][4]='';
					$coba[19][0]='combo_database';
					$coba[19][7]=array("nama_status","nama_status",'status_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[17][4]='';
					$coba[17][6]='<b>Diteruskan ke</b>';
					$coba[17][0]='combo_database';
					$coba[17][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					*/
/*
					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					//$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Surat dan berkas sedang dimuat ke memori','Teruskan','');
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					//$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
				*/
			break;
			case ("rincian_penampil_kelola_profil") :
				//$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				$user=$_POST['data'];
				$surat=$this->user_defined_query_controller_as_array($query="select * from tamu where username=\"".$user."\"",$token="andisinra");
				if(!$surat){
					alert('Surat yang dimaksud tidak tercatat');
				}else{
					$judul="<span style=\"font-size:20px;font-weight:bold;\">EDIT PROFIL AKUN</span>";
					$tabel="tamu";
					$coba=array();
					$id='username';
					$aksi='tambah';
					if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($coba as $key=>$k){
						$coba[$key][7]=$surat[0][$key];
						$coba[$key][8]=$surat[0][$key];
					}

					$coba[1][4]="required placeholder='wajib diisi...contoh: emailku@gmail.com'";

					$coba[2][4]="required placeholder='wajib diisi...'";
					$coba[4][4]="required placeholder='wajib diisi...'";
					$coba[7][4]="required placeholder='wajib diisi...'";
/*
					$coba[3][0]='password';
					$coba[3][4]="required placeholder='wajib diisi...'";
					$coba[3][6]="Masukkan Password Baru";
*/

					$coba[2][0]="text";
					$coba[3][0]='text';
					$coba[3][4]=" readonly ";

					$coba[5][6]='<b>NIP (jika pegawai)</b>';

					$coba[8][0]='combo_database';
					$coba[8][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					$coba[8][6]='<b>Asal Satuan Kerja/OPD (jika pegawai)</b>';
					//$coba[8][8]='Yang Lain (Others)';

					$coba[9][0]='combo_database';
					$coba[9][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[9][8]='Yang Lain (Others)';
					$coba[9][6]='<b>Asal Bidang (jika pegawai)</b>';

					$coba[10][0]='combo_database';
					$coba[10][7]=array("nama_subbidang","nama_subbidang",'subbidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[10][8]='Yang Lain (Others)';
					$coba[10][6]='<b>Asal Subbidang (jika pegawai)</b>';

					$coba[11][0]='combo_database';
					$coba[11][7]=array("nama_provinsi","nama_provinsi",'provinsi'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[11][8]='SULAWESI SELATAN';

					$coba[12][0]='combo_database';
					$coba[12][7]=array("nama_kabupaten","nama_kabupaten",'kabupaten'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[12][8]='Kota Makassar';

					$coba[13][0]='combo_database';
					$coba[13][7]=array("nama_kecamatan","nama_kecamatan",'kecamatan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[13][8]='Yang Lain (Others)';

					$coba[14][0]='combo_database';
					$coba[14][7]=array("nama_kelurahan","nama_kelurahan",'kelurahan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
					//$coba[14][8]='Yang Lain (Others)';

					$coba[15][0]='date';
					$coba[15][6]='<b>Password berlaku mulai</b>';
					$coba[16][0]='date';
					$coba[16][6]='<b>Password berlaku sampai</b>';
					$coba[17][0]='hidden';
					$coba[18][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
					$coba[18][4]='readonly';
					$coba[19][0]='file';
					$coba[19][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Foto</span>';

					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					//$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					//$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Perubahan data dikirim','Ubah data','');
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action="Akuntamupegawai/ubah_data_tamupegawai/";
					$submenu='submenu';
					$aksi='tambah';
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				}
			break;
			
			case ("rincian_penampil_kelola_profil_pegawai") :
				$user_pegawai=unserialize($this->session->userdata('user_pegawai'))['nipbaru'][0];
				foreach($user_pegawai as $key=>$unit){
					if(is_int($key)){
						$user[$key]=$unit;
					}else{
						$user2[$key]=$unit;
					}
				}
				//$json=json_decode($this->enkripsi->dekapsulasiData($_POST['data_json']));
				//print_r($json);
				//$user=$_POST['data'];
				//$surat=$this->user_defined_query_controller_as_array($query="select * from tamu where username=\"".$user."\"",$token="andisinra");
					$judul="<span style=\"font-size:20px;font-weight:bold;\">EDIT PROFIL AKUN</span>";
					//$tabel="tamu";
					$coba=array();
					//$id='username';
					//$aksi='tambah';
					//if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
					//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
					//$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
					//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

					//reset form sebelum dibuka:
					foreach($user as $key=>$k){
						$coba[$key][0]='text';
						$coba[$key][7]=$k;
						$coba[$key][8]=$k;
						$coba[$key][2]="form-control";
						$coba[$key][4]="";
						$coba[$key][5]="";
					}
					$i=0;
					foreach($user2 as $key=>$k){
						$coba[$i][1]=$key;
						$coba[$i][3]=$key;
						$coba[$i][6]=$key;

						//jangan munculkan element password dan username, buat tersendiri untuk merubahnya.
						if($key=='password'){
							$coba[$i][4]=' readonly ';
							$coba[$i][7]=$k;
						}

						preg_grep("#file#i",array($key))?$coba[$i][0]='file':NULL;
						preg_grep("#direktori#i",array($key))?$coba[$i][0]='hidden':NULL;
						preg_grep("#tgl#i",array($key))?$coba[$i][0]='date':NULL;
						preg_grep("#tanggal#i",array($key))?$coba[$i][0]='date':NULL;
						preg_grep("#pass_berlaku_mulai#i",array($key))?$coba[$i][0]='date':NULL;
						preg_grep("#cookie#i",array($key))?$coba[$i][0]='hidden':NULL;

						//if($key=='username'){
						//	$coba[$i][0]='hidden';
						//	$coba[$i][7]=$k;
						//}
						$i++;
					}
					$komponen=$coba;
					$atribut_form='';
					$array_option='';
					$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
					//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7, $value_selected_combo 8 tetapi untuk tombol dia adalah target_ajax yang bisa berbeda dengan target_ajax form)
					
					//$src_surat=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[15][7]));
					//$src_berkas=$this->enkripsi->strToHex($this->enkripsi->enkripSimetri_data($coba[16][7]));
					
					//$tombol[0]=array('button_ajax_pdf','button01','btn btn-info','button01','','myModal_baca_surat','Membuka Surat...','Baca Surat',"Frontoffice/tesopenpdf/".$src_surat);
					//$tombol[1]=array('button_ajax_pdf','button11','btn btn-info','button11','','myModal_baca_berkas','Membaca Berkas...','Baca Berkas Pendukung',"Frontoffice/tesopenpdf/".$src_berkas);
					$tombol[2]=array('submit','submit','btn btn-primary','submit','','','Perubahan data dikirim','Ubah data','');
					//$tombol[3]=array('button_ajax_post_CI','button12','btn btn-warning','button12','','','','Pending','');
					//$tombol[4]=array('button_ajax_post_CI','button21','btn btn-danger','button21','','','','Tolak','');
					//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
					$value_selected_combo='';
					$target_action=$this->config->item('bank_data')."/index.php/Frontoffice/ubah_data_pegawai/";
					$submenu='submenu';
					$aksi='tambah';//xxx
					$perekam_id_untuk_button_ajax='';
					$class='form-control';
					$this->session->set_userdata('toggle',TRUE);//ini hanya untuk agar alert yang tidak perlu tidak
					//$this->session->set_userdata('teks_modal','Surat dan berkas sedang dimuat ke memori');
					//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
					$this->form_general_2_vertikal_non_iframe_pegawai_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
					
					//$this->penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan="select * from surat_masuk where idsurat_masuk=".$json->idsurat_masuk,$submenu='',$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg');
				
			break;
			case ("edit_penampil_tabel") :
				echo "OK BRO MASUK EDIT";
			break;
			case ("tes_penampil_tabel_perhalaman") :
				echo "OK BRO MASUK EDIT";
			break;
			
		}
	}

	public function ubah_data_tamupegawai(){
		//echo "OK BRO MASUK";
		if(isset($_POST['data_nama'])){
			$data_post=array();
			$directory_relatif_file_upload='./public/image_tamu/';	
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
			//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
			//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
			//jadi biarkan saja demikian.
			
			if($data_post['direktori_foto']['nilai']){
				$upload=array();
				$upload1=upload('direktori_foto', $folder=$directory_relatif_file_upload, $types="jpeg,gif,png,jpg");
			}
						
			//BISMILLAH:
			//pindahkan isi $data_post ke $kiriman:
			$kiriman=array();
			foreach($data_post as $key=>$k){
				//if($key=='password'){
				//	array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
				//}else 
				if(($key=='pass_berlaku_mulai') || ($key=='pass_sampai_tgl')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($k['nilai']));
				}else{
					array_push($kiriman,$k['nilai']);
				}
			}
			
			if(isset($upload1[0])) {$kiriman[19]=$directory_relatif_file_upload.$upload1[0];}
			//echo "<br> ini kiriman: ";

			$tabel='tamu';
			$this->general_update_controller($kiriman,$tabel);
			$this->load->view('admin_frontoffice/dashboard_tamupegawai');
		}else{
			$this->load->view('admin_frontoffice/dashboard_tamupegawai');
		}
	}

	//Fungsi ini untuk meload surat dan berkas ke memory dengan menyematkannya ke $_POST
	//Kemudian menyajikan tombol untuk mengirim file yang sudah di load serta memberi informasi jika ukuran melampaui batas.
	public function teruskan_surat(){
		/*
		$user = $this->session->userdata('user');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');
				//print_r($data_post);

				//Ambil file untuk diteruskan:

				//PERHATIKAN INI, KALAU MELAKUKAN DEBUG, HAPUS error_reporting()
				//error_reporting(0);
				if($data_post['direktori_surat_masuk']['nilai']){
					$handle_surat = file_get_contents($data_post['direktori_surat_masuk']['nilai']);
					$handle_enkrip_surat=$this->enkripsi->enkripSimetri_data($handle_surat);
					$handle_hex_surat=$this->enkripsi->strToHex($handle_enkrip_surat);
				}else{
					$handle_hex_surat=NULL;
				}
		
				if($data_post['direktori_berkas_yg_menyertai']['nilai']){
					$handle_berkas = file_get_contents($data_post['direktori_berkas_yg_menyertai']['nilai']);
					$handle_enkrip_berkas=$this->enkripsi->enkripSimetri_data($handle_berkas);
					$handle_hex_berkas=$this->enkripsi->strToHex($handle_enkrip_berkas);
				}else {
					$handle_hex_berkas=NULL;
				}

				$data_post=array_merge($data_post,array('handle_hex_surat'=>array('nilai'=>$handle_hex_surat,'file'=>NULL)));
				$data_post=array_merge($data_post,array('handle_hex_berkas'=>array('nilai'=>$handle_hex_berkas,'file'=>NULL)));
				//print_r($data_post);

				//Enkrip data_post
				$data_post_enkrip=$this->enkripsi->enkripSimetri_data(serialize($data_post));
				$data_post_enkrip_hex=$this->enkripsi->strToHex($data_post_enkrip);
				$data['data_post_enkrip_hex']=$data_post_enkrip_hex;

				$this->load->view('admin_frontoffice/dashboard',$data);

				/*
				echo "INI UKURAN POST: ".strlen($data_post_enkrip_hex)."<br>";
				$ok=trim(ini_get('post_max_size'),'M');
				$ok=$ok*1024*1024;
				echo "BATAS MAKSIMUM ADALAH: ".$ok;
				*/

				/*
				echo "<br> INI adalah nilai sehabis trim: ".$ok;
				if(strlen($data_post_enkrip_hex)>$ok) {alert('file anda melampaui batas upload\nbatas ukuran kirim file terkirim adalah 40M\nanda dapat menyampaikan ke admin server \nuntuk merubah nilai post_max_size pada PHP.ini');} else{
					echo "
					<form name=\"myform\" action=\"".site_url('Frontoffice/coba_kirim')."\" method=\"POST\">
						<input type=\"hidden\" name=\"data_post_enkrip_hex\" value=\"".$data_post_enkrip_hex."\">
						<button id=\"Link\" class=\"btn btn-primary\" onclick=\"document.myform.submit()\" >Kirim</button>
					</form>
					";
				}
				*/
				
				




			} else {
				alert('Tidak ada surat dan berkas yang hendak diteruskan');
				$this->load->view('admin_frontoffice/dashboard');
			}
		/*
		}else{
			alert('Maaf Session anda kadaluarsa');
			redirect('Frontoffice/index');
		}
		*/
		
	} 

	
	public function coba_kirim(){
		/*
		$user = $this->session->userdata('user');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_post_enkrip_hex'])){
				$data_post_terima=$_POST['data_post_enkrip_hex'];

				//Dekrip dan uraikan:
				$data_post_terima=unserialize($this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($data_post_terima)));
				if($data_post_terima['handle_hex_surat']['nilai']){
					$handle_hex_surat=$data_post_terima['handle_hex_surat']['nilai'];
					$pasca_dekrip_surat=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_surat));
					$oksurat=file_put_contents("./public/surat_dan_berkas_terusan/".$data_post_terima['nama_file_surat']['nilai'], $pasca_dekrip_surat);
				}
				
				if($data_post_terima['handle_hex_berkas']['nilai']){
					$handle_hex_berkas=$data_post_terima['handle_hex_berkas']['nilai'];
					$pasca_dekrip_berkas=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_berkas));
					$okberkas=file_put_contents("./public/surat_dan_berkas_terusan/".$data_post_terima['nama_file_berkas']['nilai'], $pasca_dekrip_berkas);
				}
				if(isset($oksurat)){$data['pesan_kirim_surat']=$oksurat;}
				if(isset($okberkas)){$data['pesan_kirim_berkas']=$okberkas;}

				//Insersi ke tabel surat_terusan jika file surat atau berkas berhasil masuk, jika tidak maka jangan insersi.
				if(isset($oksurat) || isset($okberkas)){
					$buffer=array();
					foreach($data_post_terima as $key=>$k){
						if(!($key=='handle_hex_surat') && !($key=='handle_hex_berkas')){
							if($key=='timestamp_masuk'){
								array_push($buffer,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
							}else if($key=='posisi_surat_terakhir'){
								array_push($buffer,"Sekretariat ".$this->config->item('nama_opd')."");//sesuaikan jawaban ini dengan bidangnya, jika ini di sekretariat maka ganti dengan sekretariat BKD
							}else if($key=='direktori_surat_masuk') {
								array_push($buffer,str_replace('surat_dan_berkas_masuk','surat_dan_berkas_terusan',$k['nilai']));
							}else if($key=='direktori_berkas_yg_menyertai'){
								array_push($buffer,str_replace('surat_dan_berkas_masuk','surat_dan_berkas_terusan',$k['nilai']));
							}else{
								array_push($buffer,$k['nilai']);
							}
						}
					}
				$kiriman=array_merge(array(0=>NULL),$buffer);
				$tabel='surat_terusan';
				//print_r($kiriman);
				$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
				}
				$this->load->view('admin_frontoffice/dashboard',$data);
			} else{
				$this->load->view('admin_frontoffice/dashboard');
			}
		//}


	}

	//Fungsi ini untuk menerima balasan kiriman surat dari sekretariat tetapi bukan surat yang datang dari upload form
	//tetapi datang dari fungsi file_get_contents().
	public function terima_balasan_surat_dari_sekretariat(){
		/*
		$user = $this->session->userdata('user');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
		$hash=$this->session->userdata('hash');
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
				$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload='');

				//Terima kiriman file:
				if($data_post['handle_hex_surat']['nilai']){
					$handle_hex_surat=$data_post['handle_hex_surat']['nilai'];
					$pasca_dekrip_surat=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_surat));
					file_put_contents("./public/surat_dan berkas _balasan/".$data_post['nama_file_surat']['nilai'], $pasca_dekrip_surat);
				}
				
				if($data_post['handle_hex_berkas']['nilai']){
					$handle_hex_berkas=$data_post['handle_hex_berkas']['nilai'];
					$pasca_dekrip_berkas=$this->enkripsi->dekripSimetri_data($this->enkripsi->hexToStr($handle_hex_berkas));
					file_put_contents("./public/surat_dan berkas _balasan/".$data_post['nama_file_berkas']['nilai'], $pasca_dekrip_berkas);
				}


				$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Front Office ".$this->config->item('nama_opd')."");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
				
				//print_r($kiriman);
				//print_r($data_post);
				$tabel='surat_balasan_sekretariat';
				$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
				//print_r($kiriman);
				if($hasil_insersi_surat_berkas){
					$tabel_notifikasi='tbnotifikasi';
					$notifikasi=array();
					$notifikasi[1]=$data_post['pengirim']['nilai'];
					$notifikasi[2]=$kiriman[29];
					$notifikasi[3]='masuk';
					$notifikasi[4]=$data_post['timestamp_masuk']['nilai'];
					$notifikasi[5]='';
					$notifikasi[6]='balasan dari sekretariat';
					$this->general_insertion_controller($notifikasi,$tabel_notifikasi);
				}
				$this->frontoffice_admin();
			} else {
				echo "GA MASUK BRO";
			}
		/*
		}else{
			alert('Maaf Session anda kadaluarsa');
		}
		*/
	}

	//======================================================BATAS SENDING SURAT KE SEKRETARIAT================================================
	public function frontoffice_index()
	{
		/*
		$user = $this->session->userdata('user');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');
		
		if(($user!==FALSE)&&($str==$hash)){
		*/
			if(isset($_POST['data_nama'])){
				$data_post=array();
				$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/';	
				$upload=array();
				$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="bbc,doc,pdf,jpeg,gif,png,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="bbc,doc,pdf,jpeg,gif,png,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
				
				if($upload1[0] || $upload2[0]){
					//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
					$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
					$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload);
					//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
					//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
					//jadi biarkan saja demikian.

					//print_r($data_post);echo "<br>";
					//BISMILLAH:
					//pindahkan isi $data_post ke $kiriman:
					$kiriman=array();
					foreach($data_post as $key=>$k){
						if($key=='timestamp_masuk'){
							array_push($kiriman,implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime())));
						}else if($key=='posisi_surat_terakhir'){
							array_push($kiriman,"Front Office ".$this->config->item('nama_opd')."");
						}else{
							array_push($kiriman,$k['nilai']);
						}
					}
					$kiriman[13]=$upload1[0];
					$kiriman[14]=$upload2[0];
					if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[15]=NULL;}
					if($kiriman[14]) {$kiriman[16]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[16]=NULL;}

					//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
					//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
					//signatur diluar kolom id, simple_signature, digest_signature, diluar kolom timestamp selain timestamp_masuk, dispose, keterangan, status_surat.
					$persiapan_signature=$kiriman[1].$kiriman[2].$kiriman[3].$kiriman[4].$kiriman[5].$kiriman[6].$kiriman[7].$kiriman[8].$kiriman[9].$kiriman[10].$kiriman[11].$kiriman[12].$kiriman[13].$kiriman[14];
					$signature=$this->enkripsi->simplesignature_just_hashing($persiapan_signature);
					$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
					$kiriman[29]=hash('ripemd160',$signature);

					//print_r($kiriman);
					//print_r($data_post);
					$tabel='surat_masuk';
					$hasil_insersi_surat_berkas=$this->general_insertion_controller($kiriman,$tabel);
					//print_r($kiriman);
					//Persiapan notifikasi
					/*
					if($hasil_insersi_surat_berkas){
						$tabel_notifikasi='tbnotifikasi';
						$notifikasi=array();
						$notifikasi[1]=$data_post['pengirim']['nilai'];
						$notifikasi[2]=$kiriman[29];
						$notifikasi[3]='masuk';
						$notifikasi[4]=$data_post['timestamp_masuk']['nilai'];
						$notifikasi[5]='';
						$this->general_insertion_controller($notifikasi,$tabel_notifikasi);
					}*/
				}
				
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi '.$this->config->item('nama_opd').' Provinsi Sulawesi Selatan');
				array_push($upload,$upload1);
				array_push($upload,$upload2);
				$data_upload['data_upload']=$upload;
				$data_upload['src']="Frontoffice/pdf/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				//print_r($data_upload);
				$this->load->view('index',$data_upload);
			} else {
				$data_upload['data_upload']=NULL;
				$this->load->view('index',$data_upload);
			}
		/*
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage_tamupegawai");
		}
		*/
	}

	public function frontoffice_admin(){
		$user = $this->session->userdata('user_frontoffice');
        $str = $user['email'].$user['username']."1@@@@@!andisinra";
        $str = hash("sha256", $str );
        $hash=$this->session->userdata('hash');
		
		if(($user!==FALSE)&&($str==$hash)){
			$this->load->view('admin_frontoffice/dashboard');
		}else {
			$this->session->set_userdata('percobaan_login','gagal');
			//redirect( site_url('login/login') );
			$this->load->view("loginpage_tamupegawai");
		}
	}


	public function penampil_iframe_pdf($src='Frontoffice/pdf'){
		echo "<iframe id=\"target_pdf\" name=\"target_pdf\" src=\"".site_url($src)."\" style=\"left:5%;right:5%;top:5%;bottom:5%;border:0px solid #000;position:absolute;width:90%;height:70%\"></iframe>";
	}
	
	//Fungsi ini dipanggil oleh halaman index.php di view secara asinkron lewat iframe
	//ditampilkan setelah user selesai dan berhasil unggah surat atau berkas, sebagai nota unggah
	public function pdf($data_kiriman,$date_note){
			$data_kiriman=unserialize($this->enkripsi->hexToStr($data_kiriman));
			$date_note=unserialize($this->enkripsi->hexToStr($date_note));
			$data_key=array_keys($data_kiriman);
			$data=array(
				'NOTA UNGGAH SURAT DAN BERKAS',
				'RINCIAN SURAT DAN BERKAS YANG TERUNGGAH:'
			);
			foreach($data_key as $k){
				$temp=$k.": ".$data_kiriman[$k]['nilai'];
				array_push($data,$temp);
			}
			$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi '.$this->config->item('nama_opd').' Provinsi Sulawesi Selatan');
			$data=array_merge($data,$date_note);
			cetak_tiket_pdf($data);
	}
	
	public function frontoffice_unggahberkas()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">UPLOAD SURAT DAN BERKAS PENDUKUNG</span>";
		$tabel="surat_masuk";
		$coba=array();
		$id='idsurat_masuk';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		$coba[7][0]='combo_database';
		$coba[7][7]=array("target","target",'target_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[7][8]='Kepala '.$this->config->item('nama_opd').'';

		$coba[8][0]='combo_database';
		$coba[8][7]=array("status_pengirim","status_pengirim",'status_pengirim'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[8][8]='ASN internal';

		$coba[9][0]='combo_database';
		$coba[9][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[9][8]='Yang Lain (Others)';

		$coba[10][0]='combo_database';
		$coba[10][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[10][8]='Yang Lain (Others)';

		$coba[11][0]='combo_database';
		$coba[11][7]=array("nama_subbidang","nama_subbidang",'subbidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[11][8]='Yang Lain (Others)';

		$coba[12][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
		$coba[12][4]='readonly';

		$coba[13][0]='file';
		$coba[14][0]='file';

		$coba[13][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Surat</span>';
		$coba[14][6]='<span style="font-size:20px;color:red;font-weight:bold;">Unggah Berkas Pendukung</span>';

		$coba[15][0]='hidden';
		$coba[16][0]='hidden';

		$coba[17][0]='hidden';
		$coba[18][0]='hidden';

		$coba[19][0]='hidden';
		$coba[20][0]='hidden';

		$coba[21][0]='hidden';
		$coba[22][0]='hidden';

		$coba[23][0]='hidden';
		$coba[24][0]='hidden';

		$coba[25][0]='hidden';
		$coba[26][0]='hidden';
		$coba[29][0]='hidden';

		$coba[27][0]='combo_manual';
		$coba[27][7]=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[27][8]=3;

		$coba[28][0]='combo_database';
		$coba[28][7]=array("nama_urgensi_surat","nama_urgensi_surat",'urgensi_surat'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[28][8]='Yang Lain (Others)';

		/*
		UNTUK DIPAHAMI ULANG:
		case ("upload") :
			//echo "submenu_userpelanggan";	
			$oke=$_SESSION['perekam1'];
			$nama=$_GET['nama'];
			$lokasi=$_GET['lokasi'];
			echo "HKJHKJHASK";
			foreach ($oke as $isi) {
			if (!(($isi[type]=='button') || ($isi[type]=='button_ajax') || ($isi[type]=='submit'))) {echo "<br />".$_POST[$isi[nama_komponen]];}}
			upload($nama,$lokasi,'txt,jpg,jpeg,gif,png');
		*/
		//$coba[9][6]='target_surat'; //ini label
		$komponen=$coba;
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Submit','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$target_action="Frontoffice/frontoffice_index/";
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		
	}

	public function frontoffice_login($asal_login)
	{
		if($asal_login=='loginbyadmin'){
			$judul="<span style=\"font-size:20px;font-weight:bold;\">Login Admin Front Office</span>";
		}else{
			$judul="<span style=\"font-size:20px;font-weight:bold;\">Login Akun Tamu atau Pegawai</span>";
		}
		$tabel="user";
		$coba=array();
		$id='idadmin';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		$coba[1][0]='hidden';
		$coba[2][7]='';
		$coba[3][7]='';
		$coba[4][0]='hidden';
		$coba[5][0]='hidden';
		$coba[6][0]='hidden';
		$coba[7][0]='hidden';
		$coba[8][0]='hidden';
		$coba[9][0]='hidden';
		$coba[10][0]='hidden';
		$coba[11][0]='hidden';
		$coba[12][0]='hidden';

		$komponen=$coba;
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Submit','');
		//$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		if($asal_login=='loginbyadmin'){
			$target_action="Frontoffice/frontoffice_responlogin/";
		}else{
			$target_action="Frontoffice/frontoffice_responlogin_akun/";
		}
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
	}

	public function frontoffice_responlogin()
	{	
		redirect('Frontoffice/frontoffice_admin');
		#$data['data']='Halaman Admin Front Office';
		#$this->load->view('underconstruction',$data);
	}

	public function frontoffice_responlogin_akun()
	{	
		$data['data']='Halaman Akun Tamu atau Pegawai';
		$this->load->view('underconstruction',$data);
	}

	public function frontoffice_register1()
	{
		//$this->header_lengkap_bootstrap_controller();
		$judul="<span style=\"font-size:20px;font-weight:bold;\">REGISTER UNTUK TAMU</span>";
		$tabel="tamu";
		$coba=array();
		$id='idtamu';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal_atau_nilai_combo 7. $selected 8)
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//deskripsi combo_database: $type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected

		//reset form sebelum dibuka:
		foreach($coba as $key=>$k){
			$coba[$key][7]='';
		}

		$coba[1][4]="required placeholder='wajib diisi...contoh: emailku@gmail.com'";

		$coba[2][4]="required placeholder='wajib diisi...'";
		$coba[4][4]="required placeholder='wajib diisi...'";
		$coba[7][4]="required placeholder='wajib diisi...'";

		$coba[3][0]='password';
		$coba[3][4]="required placeholder='wajib diisi...'";

		$coba[5][6]='<b>NIP (jika pegawai)</b>';

		$coba[8][0]='combo_database';
		$coba[8][7]=array("nama_satker","nama_satker",'satuan_kerja'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[8][6]='<b>Asal Satuan Kerja/OPD (jika pegawai)</b>';
		$coba[8][8]='Yang Lain (Others)';

		$coba[9][0]='combo_database';
		$coba[9][7]=array("nama_bidang","nama_bidang",'bidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[9][8]='Yang Lain (Others)';
		$coba[9][6]='<b>Asal Bidang (jika pegawai)</b>';

		$coba[10][0]='combo_database';
		$coba[10][7]=array("nama_subbidang","nama_subbidang",'subbidang'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[10][8]='Yang Lain (Others)';
		$coba[10][6]='<b>Asal Subbidang (jika pegawai)</b>';

		$coba[11][0]='combo_database';
		$coba[11][7]=array("nama_provinsi","nama_provinsi",'provinsi'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[11][8]='SULAWESI SELATAN';

		$coba[12][0]='combo_database';
		$coba[12][7]=array("nama_kabupaten","nama_kabupaten",'kabupaten'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[12][8]='Kota Makassar';

		$coba[13][0]='combo_database';
		$coba[13][7]=array("nama_kecamatan","nama_kecamatan",'kecamatan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[13][8]='Yang Lain (Others)';

		$coba[14][0]='combo_database';
		$coba[14][7]=array("nama_kelurahan","nama_kelurahan",'kelurahan'); //inshaa Allah gunakan ini sekarang untuk mendefinisikan combo_database, soalnya core sudah dirubah.
		$coba[14][8]='Yang Lain (Others)';

		$coba[15][0]='date';
		$coba[15][6]='<b>Password berlaku mulai</b>';
		$coba[16][0]='date';
		$coba[16][6]='<b>Password berlaku sampai</b>';
		$coba[17][0]='hidden';
		$coba[18][7]=implode("-",array (date("d/m/Y"),mt_rand (1000,9999),microtime()));
		$coba[18][4]='readonly';

		$komponen=$coba;
		$atribut_form='';
		$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[0]=array('submit','submit','btn btn-primary','submit','','','','Submit','');
		$tombol[1]=array('reset','reset','btn btn-warning','reset','','','','Reset','');
		//$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Kirim','');
		$value_selected_combo='';
		$target_action="Frontoffice/frontoffice_indexregister/";
		$submenu='submenu';
		$aksi='tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		//$this->form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		$this->form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='',$data_ajax=NULL);
		
	}

	public function frontoffice_indexregister() {		
		/*
		$directory_relatif_file_upload='./public/surat_dan_berkas_masuk/';	
		$upload=array();
		$upload1=upload('nama_file_surat', $folder=$directory_relatif_file_upload, $types="bbc,doc,pdf,jpeg,gif,png,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
		$upload2=upload('nama_file_berkas', $folder=$directory_relatif_file_upload, $types="bbc,doc,pdf,jpeg,gif,png,docs,docx,xls,xlsx,ppt,pptx,txt,sql,csv,xml,json,rar,zip,bmp,jpg,htm,html");
		*/
		if(isset($_POST['data_nama'])){
			//$nama_file_setelah_unggah=array('nama_file_surat' => $upload1, 'nama_file_berkas' => $upload2);
			$data_post=array();
			$data_nama_masuk=$this->enkripsi->dekapsulasiData($_POST['data_nama']);
			$data_post=pengambil_data_post_get($data_nama_masuk,$directory_relatif_file_upload=NULL);
			//catatan: walaupun $data_post[0] sebagai idsurat_masuk sudah terisi default karena sifat browser yang menchas data input
			//akan tetapi insersi tidak melibatkan field idsurat_masuk atau $data_post[0] pada core fungsi general_insertion_controller
			//jadi biarkan saja demikian.

			//Tanda tangan sebelum ada idsurat_masuk dalam basisdata, tapi buat nanti tand atangan dengan cara memeriksa ulang di basisdata setelah abru saja terjadi insersi
			//agar diketahui idsurat_masuk, untuk yang ini hanya percobaan saja sementara.
			$signature=$this->enkripsi->simplesignature_just_hashing($data_post);
			$data_post=array_merge($data_post,array('simple_signature'=>array('nilai'=>$signature,'file'=>NULL)));
			//print_r($data_post);echo "<br>";
			
			//BISMILLAH:
			//pindahkan isi $data_post ke $kiriman:
			$kiriman=array();
			foreach($data_post as $key=>$k){
				if($key=='password'){
					array_push($kiriman,password_hash($k['nilai'], PASSWORD_BCRYPT));
				}else if(($key=='pass_berlaku_mulai') || ($key=='pass_sampai_tgl')){
					array_push($kiriman,konversi_format_tgl_ttttbbhh_ke_hhbbtttt($k['nilai']));
				}else{
					array_push($kiriman,$k['nilai']);
				}
			}
			/*
			$kiriman[13]=$upload1[0];
			$kiriman[14]=$upload2[0];
			if($kiriman[13]) {$kiriman[15]=$directory_relatif_file_upload.$upload1[0];}else{$kiriman[15]=NULL;}
			if($kiriman[14]) {$kiriman[16]=$directory_relatif_file_upload.$upload2[0];}else{$kiriman[16]=NULL;}
			*/
			//echo "<br> ini kiriman: ";
			//print_r($kiriman);
			//print_r($data_post);
			$tabel='tamu';
			$oke=$this->general_insertion_controller($kiriman,$tabel);
			//print_r($kiriman);
		
			if($oke){
				//Penetapan lokasi, tanggal dan tertanda frontoffice untuk bagian bawah nota unggah:
				$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi '.$this->config->item('nama_opd').' Provinsi Sulawesi Selatan');
				$data_upload['src_register']="Frontoffice/pdf_registrasi/".$this->enkripsi->strToHex(serialize($data_post))."/".$this->enkripsi->strToHex(serialize($date_note));
				$this->load->view('index',$data_upload);
			} else{
				$this->load->view('index');
			}
		} else {
			$this->load->view('index');
		}
		
	
	}

	//Fungsi ini dipanggil oleh halaman index.php di view secara asinkron lewat iframe
	//ditampilkan setelah user selesai dan berhasil unggah surat atau berkas, sebagai nota unggah
	public function pdf_registrasi($data_kiriman,$date_note){
		$data_kiriman=unserialize($this->enkripsi->hexToStr($data_kiriman));
		$date_note=unserialize($this->enkripsi->hexToStr($date_note));
		$data_key=array_keys($data_kiriman);
		$data=array(
			'NOTA REGISTRASI TAMU',
			'Yang bersangkutan telah registrasi, dengan rincian:'
		);
		foreach($data_key as $k){
			$temp=$k.": ".$data_kiriman[$k]['nilai'];
			array_push($data,$temp);
		}
		//$date_note=array(' ','Makassar ',date("d/m/Y"),'Tertanda:','Frontoffice Sistem Terintegrasi '.$this->config->item('nama_opd').' Provinsi Sulawesi Selatan');
		$data=array_merge($data,$date_note);
		cetak_tiket_pdf_registrasi($data);
	} 






















//=========================================BATAS, SEMUA FUNGSI DIBAWAH ADALAH FUNGSI PUSTAKA YANG DIRENCANAKAN UNTUK DIPINDAHKAN KE LIBRRAY ATAU CORE==========================
//TES 
	//ALHAMDULILLAH SUKSES, YANG ARTINYA:
	//BISA KOMUNIKASI ANTAR FILE CONTROLLER, SALING KIRIM DATA DAN SEBAGAINYA
	//BISA KIRIM DATA TERENKRIPSI.
	public function tes1($ok="NOT YET",$ok1="NOT YET 2")
	{
		//$dataenkrip=$this->pengirim_terenkripsi_simetri('select nama from identpeg');
		//$tokenenkrip=$this->pengirim_terenkripsi_simetri('andisinra');
		/*
		$pageNum_Recordset1=1;
		$maxRows_Recordset1=100;
		$kolom_cari='nama';
		$key_cari='andi';*/

		/*
		//INI TES FUNGSI general_insertion_model
		$tabel='admin';
		$kiriman=array(28,'update@jskjs.com','','','cKamos');
		$kiriman=$this->strtohex(serialize($kiriman));
		*/
		/*
		$tabel='admin';
		$id=28;*/
		
		/*
		global $coba;
		$id=3;
		$tabel='admin';
		echo "<br>sebelum: <br>";
		$coba1=$this->penarik_key_controller('admin');
		$coba2=$this->penarik_key_controller('identpeg');
		print_r($coba1);
		echo "<br>INI BRO: ".$coba1[1];
		$i=0;
		
		//$coba=array();
		for($i=0;$i<sizeof($coba1);$i++){
			$coba_panel['admin'][$i][0]=$coba1[$i];
			//$coba_panel['agama'][$i][0]=$coba1[$i];
		}
		for($i=0;$i<sizeof($coba2);$i++){
			//$coba_panel['admin'][$i][0]=$coba1[$i];
			$coba_panel['identpeg'][$i][0]=$coba2[$i];
		}
		//for($i=0;$i<sizeof($coba1);$i++){
			$tabel_panel[0]='admin';
			$tabel_panel[1]='identpeg';
			$id_panel[0]=3;
			$id_panel[1]=195412311974041002;
		//}$id_panel,$tabel_panel
		//print_r($coba_panel);
		$this->session->set_userdata('coba_panel', $coba_panel);
		$tabel_panel=$this->enkripsi->enkapsulasiData($tabel_panel);
		$id_panel=$this->enkripsi->enkapsulasiData($id_panel);
		*/
		/*
		$kolom_value='idadmin';
		$kolom_label='username';
		$tabel='admin';
		$id=30;
		*/
		/*
		$tabel_panel[0]='admin';
		$tabel_panel[1]='identpeg';
		$tabel_panel=$this->enkripsi->enkapsulasiData($tabel_panel);
		redirect(site_url('frontoffice/tes2/'.$tabel_panel));
		*/
		//redirect(site_url('frontoffice/tes2/'.$pageNum_Recordset1.'/'.$maxRows_Recordset1.'/'.$tabel.'/'.$kolom_cari.'/'.$key_cari));

		/*
		TES AKSES KONFIGURASI DATABASE DI database.php di folder config
		echo $this->db->hostname;
		echo "<br>".$this->db->username;
		echo "<br>".$this->db->password;
		echo "<br>".$this->db->database;
		*/

		//print_r($this->penarik_key_string_ut_sebarang_query_controller($query='select * from admin'));
		echo "OK BRO MASUK";
		//echo "INI DATA name: ".$this->enkripsi->dekapsulasiData($_POST['data_json']);
		//echo "<br>INI DATA username: ".$_POST['username'];
		echo "<br>INI DATA proses: ".$_GET['proses'];
		echo "<br>INI DATA ok: ".$ok;
		echo "<br>INI DATA ok1: ".$ok1;
	}

	public function tes2()
	{
		//$tabel_panel=$this->enkripsi->dekapsulasiData($tabel_panel);
		//$id=$this->enkripsi->dekapsulasiData($id);
		/*
		echo "Nama Tabel: ".$tabel."<br>";
		$kiriman=unserialize($this->hextostr($kiriman));
		print_r($kiriman);
		*/
		/*
        echo "INI pageNum_Recordset1: ".$pageNum_Recordset1;
        echo "<br>INI maxRows_Recordset1: ".$maxRows_Recordset1;
        echo "<br>INI tabel: ".$tabel;
        echo "<br>INI kolom_cari: ".$kolom_cari;
		echo "<br>INI key_cari: ".$key_cari;
		*/
        //echo "<br>INI query_Recordset1: ".$query_Recordset1;
		//$datatodekrip=$this->penerima_terenkripsi_simetri($query_Recordset1,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'));
		//echo "<br>INI query_Recordset1: ".$datatodekrip;
		//$coba=$this->session->userdata('coba');
		//print_r($this->penarik_key_controller_panel($tabel_panel));
		//echo "<br>setelah: <br>";
		//global $coba;
		//print_r($coba);
		//var_dump($coba);
		//foreach ($coba as $row){echo "<br>".$row->nama;}

		$this->header_lengkap_bootstrap_controller();
		
		/*
		$array_option=array('ok'=>'bro','ok1'=>'bro1');
		$this->form_input('checkbox','tes_text','checkbox','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('checkbox','tes_text','checkbox disabled','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('number','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('checkbox','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('color','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_input('text','tes_text','form-control','text_tes',$atribut="style=\"margin:20px\"",$event='');
		echo "<br>";
		$this->form_area('text_area','form-control','text_tes',$atribut="style=\"margin:20px\"");
		echo "<br>";
		$this->form_combo_manual('tes_combo','form-control','tes_combo',$atribut="style=\"margin:20px\"",$array_option,$selected);
		echo "<br>";
		*/
		//$this->form_combo_database_controller('tes_combo_database','form-control','tes_combo_database',"style=\"margin:20px\"",array('username','email'),'admin','noeng.hunter@gmail.com');
		
		/*
		//TES form_general_controller:
		$komponen=array('Username'=>'text','email'=>'email','keterangan'=>'area','Radio'=>'radio','Search'=>'search','Checkbox'=>'checkbox','Warna'=>'color','Range'=>'range','Image'=>'image','Bilangan'=>'number','Tanggal'=>'date','Kirim Kueri'=>'submit','Ulangi'=>'reset','Tombol'=>'button');
		$array_option=array('Onde-onde'=>'onde','Doko-doko'=>'doko','Beppa Apang'=>'apang');
		$judul='<center>UJI COBA FORM<center>';
		$this->session->set_userdata('perekam',array());
		$selected='Beppa Apang';
		$array_value_label_checkbox=array('bajabu', 'botting', 'tahu', 'bumi','kambing');
		$disable_checkbox=array('tahu', 'bumi');
		$array_value_label_radio=array('radiobajabu', 'radiobotting', 'radiotahu', 'radiobumi','radiokambing');
		$disable_radio=array('radiotahu', 'radiokambing');
		echo "<div style=\"width:70%;\">";
		$hasil=$this->form_general_controller($komponen,$atribut_form=" class=\"form-group\" ",$array_option,$atribut_table=array('table'=>" class=\"table table-hover\" ",'tr'=>'','td'=>''),$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio);
		echo "</div>";
		print_r($hasil);
		*/
		
		/*
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='date',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='email',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='datetime-local',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='url',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='search',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='range',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='button',$nama_komponen='text1',$class='btn btn-warning',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='area',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='file',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='password',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='number',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='time',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='week',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='month',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='button_ajax2',$nama_komponen='text1',$class='form-control',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='checkbox',$nama_komponen='text1',$class='checkbox',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='radio',$nama_komponen='text1',$class='radio',$id='text1',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='reset',$nama_komponen='text1',$class='btn btn-info',$id='text1',$atribut='',$event='',$label='',$value='Submit',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='submit',$nama_komponen='text1',$class='btn btn-primary',$id='text1',$atribut='',$event='',$label='',$value='Reset',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		*/
		/*
		$value_manual=array('bumi','bulan','dna','yupiter','matahari');
		$value_database=array('username','email','admin');
		$this->buat_komponen_form_controller($type='combo_manual',$nama_komponen='combo_manual',$class='form-control',$id='combo_manual',$atribut='',$event='',$label='',$value_manual,$value_selected_combo='bulan',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='combo_database',$nama_komponen='combo_database',$class='form-control',$id='combo_database',$atribut='',$event='',$label='',$value_database,$value_selected_combo='bagus',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text2',$class='form-control',$id='text2',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$this->buat_komponen_form_controller($type='text',$nama_komponen='text3',$class='form-control',$id='text3',$atribut='',$event='',$label='',$value='',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		
		//$this->buat_komponen_form_controller($type='button_iframe',$nama_komponen='text1',$class='btn btn-primary',$id='text1',$atribut='',$event='',$label='',$value='Button_iframe',$value_selected_combo='',$submenu='',$aksi='',$perekam_id_untuk_button_ajax=NULL);
		$perekam_id_untuk_button_ajax[1]['id']='combo_manual';
		$perekam_id_untuk_button_ajax[2]['id']='combo_database';
		$perekam_id_untuk_button_ajax[3]['id']='text2';
		$perekam_id_untuk_button_ajax[4]['id']='text3';
		$this->buat_komponen_form_controller($type='button_ajax',$nama_komponen='text1',$class='btn btn-warning',$id='text1',$atribut='',$event='',$label='',$value='Button Ajax',$value_selected_combo='',$submenu='pilihan',$aksi='tambah',$perekam_id_untuk_button_ajax);
		*/
		
		
		//$this->header_lengkap_bootstrap_controller();
		$judul="Tambahkan Kandidat";
		$tabel="admin";
		//$database="dbdatacenter";
		//$key_cari=$_GET['kolom_cari'];
		//$kolom_cari="nama_alternatif";
		$coba=array();
		$id='idadmin';
		$aksi='tambah';
		if (!($aksi=="cari") and !($aksi=="tampil_semua")) $coba=$this->pengisi_komponen_controller($id,$tabel,$aksi);
		//print_r($coba);
		//deskripsi $komponen=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		
		$coba=$this->pengisi_awal_combo ($id,$tabel,$coba);
		//print_r($coba);
		
		$coba[1][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		$coba[2][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//$coba[2][0]="hidden";
		//$coba[2][6]="";
		//$coba[0][6]="<font style=\"color:white;\">No Id (biarkan tidak diisi)</font>";
		//$coba[1][6]="<font style=\"color:white;\">Nama Jamkesmas</font>";
		
		$coba[2][4]='';//"cols='60' style='border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		$coba[3][4]='';//"style='padding:5px;border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//$coba[3][0]="hidden";
		//$coba[3][6]="";
		$coba[3][4]='';//"cols='60' style='border-radius:5px 5px 5px 5px;box-shadow:0pt 3px 3px rgba(20, 20, 20, 0.5) inset;'";
		//print_r($coba);
		$komponen=$coba;
		//$atribut_form='';
		//$array_option='';
		$atribut_table=array('table'=>"class=\"table table-condensed\"",'tr'=>"",'td'=>"",'th'=>"");
		//deskripsi untuk tombol ke-i, $tombol[$i]=array($type 0,$nama_komponen 1,$class 2,$id 3,$atribut 4,$event 5,$label 6,$nilai_awal 7)
		$tombol[2]=array('submit','submit','btn btn-primary','submit','','','','Tombol Submit');
		//$tombol[0]=array('button_ajax2','button_ajax2','btn btn-info','button_ajax2','','','','Tombol Ajax2','');
		$tombol[0]=array('button_ajax_get_CI','button_ajax_get_CI','btn btn-info','button_ajax_get_CI','','','','Tombol Ajax4','');
		//$tombol[0]=array('button_ajax_post_CI','button_ajax_post_CI','btn btn-info','button_ajax_post_CI','','','','Tombol Ajax4','');

		$tombol1[0]=array('button_ajax','button_ajax','btn btn-info','button_ajax','','','','Tombol Ajax','');
		$value_selected_combo='';
		$target_action='target_action';
		$submenu='ini_pesan_submenu';
		$aksi='ini_pesan_tambah';
		$perekam_id_untuk_button_ajax='';
		$class='form-control';
		$this->form_general_2_view_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax='Frontoffice/tes1/123/234',$data_ajax=NULL);
		//$this->form_general_2_view_vertikal_controller($komponen,$atribut_form='',$array_option='',$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control');
		//print_r($komponen);
		/*
		$panel[0]['judul']='Judul Panel ke-0';
     	$panel[0]['komponen']=$komponen;
		$panel[0]['tombol']=$tombol1;
		$panel[0]['value_selected_combo']=2;
		$panel[0]['target_action']=site_url('/frontoffice/tes1');
		$panel[0]['submenu']='submenu1';
		$panel[0]['aksi']='tambah';
		$panel[0]['atribut_form']='';
		$panel[0]['array_option']=array('sabar','kuat','cerah','diam');
		$panel[0]['atribut_table']=$atribut_table;
			
     	$panel[1]['judul']='Judul Panel ke-1';
     	$panel[1]['komponen']['Nama']='text';
     	$panel[1]['komponen']['Alamat']='area';
		$panel[1]['komponen']['Pilihan']='combo_manual';
		$panel[1]['tombol']=$tombol;
		$panel[1]['value_selected_combo']=3;
		$panel[1]['target_action']=site_url('/frontoffice/tes1');
		$panel[1]['submenu']='submenu1';
		$panel[1]['aksi']='tambah';
		$panel[1]['atribut_form']='';
		$panel[1]['array_option']=array('sabar','kuat','cerah','diam');
		$panel[1]['atribut_table']=$atribut_table;
		
		//print_r($panel);
		//$this->form_general_2_view_panel_controller($panel,$perekam_id_untuk_button_ajax,$class='form-control');
		*/

		//$this->penampil_tabel_tanpa_CRUID_controller ($array_atribut=array(""," class=\"table table-bordered\"",""),$query_yang_mau_ditampilkan='select * from tbchat',$submenu='',$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg');
		//$this->header_lengkap_bootstrap_controller();
		//$count_tbchat=$this->model_frommyframework->jumlah_rekord('tbchat');
		//$this->penampil_tabel_komentar_controller($array_atribut=array(""," class=\"table table-hover\"",""),$query_chat='SELECT * FROM `tbchat` order by idchat ASC',$count_tbchat,$jumlah_komen_ditampilkan=3,$submenu='');
	}

	public function tes3(){
		$this->header_lengkap_bootstrap_controller();
		$this->user_defined_query_controller_as_array_terenkripsi($query_terenkripsi,$token_terenkripsi);
		$tes=$this->user_defined_query_controller_as_array($query='select * from admin',$token="andisinra");
		echo "is array? ".is_array($tes)."<br>";
		print_r($tes);
	}

	public function tes4(){
		$this->header_lengkap_bootstrap_controller();
		$this->penampil_tabel_tab_pegawai_controller ($array_atribut=array(""," class=\"table table-condensed\"",""),$Query_pegawai_terbatas='select * from identpeg limit 1,20',$submenu='',$tab='',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg',$target_ajax='Frontoffice/gerbang/rincian_pegawai_table_tab');
	}

	public function tes5(){
		$this->header_lengkap_bootstrap_controller();
		$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');

	}

	public function tes6(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->penampil_tabel_perhalaman ($maxRows_Recordset1=10,$tabel='identpeg',$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1='select * from identpeg limit 0,10',$submenu='tes_penampil_tabel_perhalaman',$tab='');
	}

	public function tes7(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->default_cruid_controller ($tabel='admin',$judul='PERCOBAAN',$pilihan1='tes_penampil_tabel_perhalaman',$aksi='tambah');
	}
//[END TES]

//[START TERJEMAHAN CONTROLLER DARI FRAMEWORK SEBELUMNYA]

	//OK, INSHAA ALLAH TINGGAL DI UJI
	//SUDAH DI UJI, ADA KEKURANGAN: TETAPI INI DIANGGAP OBSELET JADI DITINGGALKAN SEMENTARA.
	/*
	public function tes6(){
		$this->header_lengkap_bootstrap_controller();
		//$this->penampil_tabel_controller($array_atribut=array(""," class=\"table table-condensed\"",""),$query_yang_mau_ditampilkan='select * from identpeg limit 0,10',$submenu='rincian',$kolom_direktori='',$direktori_avatar='public/img/no-image.jpg');
		$this->penampil_tabel_perhalaman ($maxRows_Recordset1=10,$tabel='identpeg',$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1='select * from identpeg limit 0,10',$submenu='tes_penampil_tabel_perhalaman',$tab='');
	}
	*/
	function penampil_tabel_perhalaman ($maxRows_Recordset1,$tabel,$array_atribut=array(""," class=\"table table-condensed\"",""),$style='',$query_Recordset1,$submenu,$tab) {
		//Definisi Style:
		echo $style;
		$currentPage = $_SERVER["PHP_SELF"];
		if (!$query_Recordset1) {
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->jumlah_rekord ($tabel);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page($maxRows_Recordset1,$tabel);
		$Recordset1 = $this->page_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel);
		$key_kolom=$this->penarik_key_controller($tabel); 
		$Recordset=$this->konvers_recordset_CI_to_array_controller($Recordset1,$key_kolom);
		} 
		else {
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->jumlah_rekord_query ($query_Recordset1);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page_query($maxRows_Recordset1,$query_Recordset1);
		$Recordset =$this->page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1);
		//$key_kolom=$this->penarik_key_query_CI_controller($query_Recordset1);
		//$Recordset=$this->konvers_recordset_PDOStatement_to_array_controller($Recordset1);
		}
		
		//$row_Recordset1 = $this->konvers_recordset_to_array_controller($Recordset1);
		
		//penampil_tabel ($array_atribut,$Recordset1,$row_Recordset1,$submenu,$tab); //BAGIAN INI MUNGKIN SALAH, CEK NANTI JIKA ADA ERROR
		
		$this->penampil_tabel_with_no_query_controller ($array_atribut,$Recordset,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
		
		$startRow_Recordset1 = $this->start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1);
		$this->tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1);//echo "GGJGJHG".$submenu;
		if($pageNum_Recordset1=NULL){$pageNum_Recordset1=$this->session->userdata('pageNum_Recordset1');}
		echo "<div align='center' ><table border='0' width='22%' align='center'><tr style='cursor:pointer;'><td width='30' align='center'  onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pilihan=".$submenu."&pageNum_Recordset1=0"."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Awal";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".max(0, $pageNum_Recordset1 - 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Sebelumnya";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".min($totalPages_Recordset1, $pageNum_Recordset1 + 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Berikutnya";} // Show if not last page 
		echo "</td><td width='39' align='center' onclick='tampilkandata(\"GET\",\"".base_url('Frontoffice/gerbang/tes_penampil_tabel_perhalaman')."\",\"pageNum_Recordset1=".$totalPages_Recordset1."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Akhir";} // Show if not last page 
		echo "</td></tr></table></div>";
	}
	
	//INI DIANGGAP OBSELET, DITINGGALKAN SEMENTARA
	//Fungsi menampilkan navigasi (ALHAMDULILLAH, SUDAH DITES, OK)
	function penampil_tabel_perhalamanLAMA ($maxRows_Recordset1,$tabel,$array_atribut,$style,$Recordset1,$submenu) {
		//Definisi Style:
		echo $style;
		$currentPage = $_SERVER["PHP_SELF"];
		$pageNum_Recordset1 = $this->nomor_halaman(); 
		$totalRows_Recordset1= $this->controller_jumlah_rekord ($tabel,$database);
		$queryString_Recordset1 = $this->penangkap_query_string ($totalRows_Recordset1);
		$totalPages_Recordset1 = $this->jumlah_page($maxRows_Recordset1,$tabel);
		
		if (!$Recordset1) $Recordset1 = $this->page_Recordset1($pageNum_Recordset1,$maxRows_Recordset1,$tabel);
		//$row_Recordset1 = mysql_fetch_assoc($Recordset1);
		
		//penampil_tabel ($array_atribut,$Recordset1,$row_Recordset1,$submenu); //BAGIAN INI MUNGKIN SALAH, CEK NANTI JIKA ADA ERROR
		$this->penampil_tabel_with_no_query_controller ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');

		$startRow_Recordset1 = $this->start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1);
		$this->tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1);
		echo "<div align='center' ><table border='0' width='22%' align='center'><tr style='cursor:pointer;'><td width='30' align='center'  onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=0$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Awal";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".max(0, $pageNum_Recordset1 - 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 > 0) {echo "Sebelumnya";} // Show if not first page 
		echo "</td><td width='30' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".min($totalPages_Recordset1, $pageNum_Recordset1 + 1)."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Berikutnya";} // Show if not last page 
		echo "</td><td width='39' align='center' onclick='tampilkandata(\"GET\",\"../controller/gerbang.php\",\"pilihan=$submenu&pageNum_Recordset1=".$totalPages_Recordset1."$queryString_Recordset1\",\"#penampil\",\"#pra\")'>";
		if ($pageNum_Recordset1 < $totalPages_Recordset1) {echo "Akhir";} // Show if not last page 
		echo "</td></tr></table></div>";
		echo "pageNum_Recordset1 = ".$pageNum_Recordset1; 
	} 

	//ALHAMDULILLAH SUDAH DITES SUKSES.
	//Fungsi Pengisi label komponen: $id digunakan jika mode nya adalah edit atau rincian, artinya semua komponen diisi berdasar id=$id, sbg awal.
	function pengisi_komponen_controller($id,$tabel,$type_form) {
		$komponen=array();$key_kolom=$this->penarik_key_controller($tabel); 
		//$komponen=array($type,$nama_komponen,$class,$id,$atribut,$event,$label,$nilai_awal)
		//---type
		$i=0;
		foreach ($key_kolom as $isi) {$komponen[$i][0]="text";$komponen[$i][2]="text";$komponen[$i][4]='';$komponen[$i][5]='';$i++;} 
		//----name/id
		$i=0;
		foreach ($key_kolom as $isi) {$komponen[$i][1]=$isi;$komponen[$i][3]=$isi;$i++;} 
		//----value
		if (!($type_form==NULL) && !($type_form=="tambah")) {
			$i=0;
			$Recordset=$this->user_defined_query_controller ("SELECT * FROM $tabel WHERE $key_kolom[0]=$id ",$token='andisinra');
			//$RowRecordset=mysql_fetch_assoc($Recordset);
			$RowRecordset=$Recordset->fetch(PDO::FETCH_ASSOC);
			foreach ($RowRecordset as $isi) {
				$komponen[$i][7]=$isi;$i++;
			}
		}
		//----label
		$i=0;
		//foreach ($key_kolom as $isi) {$key_kolom=ucwords($isi);} 
		foreach ($key_kolom as $isi) {$komponen[$i][6]=join("",array("<b>",ucwords(implode(" ",explode("_",ucwords($isi)))),"</b>"));$i++;}   
		return $komponen;
	} //end pengisi_komponen

	//BELUM TES
	//ALHAMDULILLAH SUDAH DITES OK.
	function pengisi_awal_combo ($id,$tabel,$coba) {
		//global $coba;//jangan gunakan perintah global, gunakan saja session.ini perintah lama.
		//$coba=$this->session->userdata('coba');
		$key_combo=$this->penarik_key_controller($tabel);
		
		if ($id) {
			$Recordset1=$this->user_defined_query_controller ("SELECT * FROM $tabel WHERE $key_combo[0]=$id",$token='andisinra');
			$RowRecordset1=$Recordset1->fetch(PDO::FETCH_ASSOC);
			if($coba){
				for($i=0;$i<sizeof($coba);$i++){
					$coba[$i][7]=$RowRecordset1[$key_combo[$i]];
					$coba[$i][8]='';
				}
			}
		}
		//$this->session->set_userdata('coba', $coba);
		return $coba;
	}

	//ALHAMDULILLAH SUDAH DITES SUKSES.
	function pengisi_awal_combo_panel ($id_panel,$tabel_panel) {
		//global $coba_panel; DEKLARASI CARA INI OBSELET, digantikan dengan session saja.
		$coba_panel=$this->session->userdata('coba_panel');
		foreach($tabel_panel as $key=>$k){
			$key_combo[$key]=$this->penarik_key_controller($k);
			if ($id_panel[$key]) {
				$Recordset1[$key]=$this->user_defined_query_controller ("SELECT * FROM $k WHERE {$key_combo[$key][0]}=$id_panel[$key]",$token='andisinra');
				$RowRecordset1[$key]=$Recordset1[$key]->fetch(PDO::FETCH_ASSOC);
				if($coba_panel){
					for($i=0;$i<sizeof($key_combo[$key]);$i++){
						$coba_panel[$k][$i][7]=$RowRecordset1[$key][$key_combo[$key][$i]];
					}
				}
			}
		}
		$this->session->set_userdata('coba_panel', $coba_panel);
		return $coba_panel;
	}

	//LANGSUNG AJA, KARENA SUDAH DITES, HANYA UNTUK KOMPATIBILITAS DENGAN FRAMEWORK SEBELUMNYA
	//membungkus fungsi page dari model.php
	function tabel_perhalaman($halaman_ke,$maxRows_Recordset1,$tabel) {
		return $this->page_row_Recordset1($halaman_ke,$maxRows_Recordset1,$table);
	}

	//LANGSUNG AJA. GA DI TES KARENA SIMPLE.
	//Fungsi menemukan nomor halaman (SUDAH DITES, OK)
	function nomor_halaman () {
		if (isset($_POST['pageNum_Recordset1'])) {$pageNum_Recordset1 = $_POST['pageNum_Recordset1'];} 
		else if (isset($_GET['pageNum_Recordset1'])){$pageNum_Recordset1 = $_GET['pageNum_Recordset1'];} 
		else {$pageNum_Recordset1 = 0;}
		return $pageNum_Recordset1;
	}

	//LANGSUNG AJA.
	//Fungsi penghitung rekord awal (SUDAH DITES, OK)
	function start_baris_rekord($maxRows_Recordset1,$pageNum_Recordset1) {return $pageNum_Recordset1*$maxRows_Recordset1;}

	//LANGSUNG AJA. UNTUK KOMPATIBILITAS.
	//Fungsi penghitung jumlah rekord dari controller (SUDAH DITES, OK)
	function controller_jumlah_rekord($tabel) {return $this->jumlah_rekord($tabel);}

	//LANGSUNG AJA, MASIH SIMPLE.
	//Fungsi penghitung jumlah page, (SUDAH DITES, OK)
	function jumlah_page($maxRows_Recordset1,$tabel) {
		if (isset($_GET['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_GET['totalRows_Recordset1'];} 
		else if (isset($_POST['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_POST['totalRows_Recordset1'];} 
		else {$totalRows_Recordset1 = $this->jumlah_rekord($tabel);}
		$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
		return $totalPages_Recordset1;
	}

	//LANGSUNG AJA.
	//Fungsi penghitung jumlah page, (SUDAH DITES, OK)
	function jumlah_page_query($maxRows_Recordset1,$query) {
		if (isset($_GET['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_GET['totalRows_Recordset1'];} 
		else if (isset($_POST['totalRows_Recordset1'])) {$totalRows_Recordset1 = $_POST['totalRows_Recordset1'];} 
		else {$totalRows_Recordset1 = $this->jumlah_rekord_query($query,$token='andisinra');}
		$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;
		return $totalPages_Recordset1;
	}
	
	//LANGSUNG AJA. 
	//TAPI INI MESTI DIPERHATIKAN NANTI, MUNGKIN SUDAH OBSELET KARENA CODEIGNITER MENGGUNAKAN ATURAN URI YANG BERBEDA.
	//Sudah dites (SUDAH DITES, OK) tetapi logikanya belum dites, tunggu hasil sebenarnya.
	function penangkap_query_string ($totalRows_Recordset1) {
		$queryString_Recordset1 = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
			$params = explode("&", $_SERVER['QUERY_STRING']);
			$newParams = array(); 
			foreach ($params as $param) {
				if (stristr($param, "pageNum_Recordset1") == false && stristr($param, "totalRows_Recordset1") == false) {
					array_push($newParams, $param);
				}
			}
			if (count($newParams) != 0) {
				$queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
			}
		}
		$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
		return $queryString_Recordset1;
	}
	
	//Fungsi menampilkan halaman yg sudah dibrowse : (ALHAMDULILLAH, SUDAH DITES, OK)
	function tanda_halaman ($startRow_Recordset1,$maxRows_Recordset1,$totalRows_Recordset1) {
		echo "<div align='center'>Records".($startRow_Recordset1 + 1)." to ".min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1)." of ".$totalRows_Recordset1." </div>";
	}

	//OK INI LANGSUNG AJA
	//--------------------------------------------------------------------
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
		switch ($theType) {
		case "text":$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";break;    
		case "long":case "int":$theValue = ($theValue != "") ? intval($theValue) : "NULL";break;
		case "double":$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";break;
		case "date":$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";break;
		case "defined":$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;break;
	}
		return $theValue;
	}

	function editFormAction(){
		$editFormAction = $_SERVER['PHP_SELF'];
		if (isset($_SERVER['QUERY_STRING'])) {$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);}
		return $editFormAction;
	}

	

	//[END TERJEMAHAN CONTROLLER]

	
	//[START TERJEMAHAN VIEW DARI FRAMEWORK SEBELUMNYA]

	function penampil_tombol_add_controller ($add,$toolbar,$src_wh){
		$this->viewfrommyframework->penampil_tombol_add ($add,$toolbar,$src_wh);
	}

	public function penampil_tabel_with_no_query_controller ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg'){
		$this->viewfrommyframework->penampil_tabel_with_no_query ($array_atribut,$Recordset1,$submenu,$kolom_direktori='direktori',$direktori_avatar='/public/img/no-image.jpg');
	}

	function penampil_tabel_tab_pegawai_controller ($array_atribut,$Query_pegawai_terbatas,$submenu,$tab,$kolom_direktori=NULL,$direktori_avatar='/public/img/no-image.jpg',$target_ajax){
		$this->viewfrommyframework->penampil_tabel_tab_pegawai ($array_atribut,$Query_pegawai_terbatas,$submenu,$tab,$kolom_direktori,$direktori_avatar,$target_ajax);
	}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_searching_controller ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3) {
		$this->viewfrommyframework->penampil_bar_searching ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3);
	}

    //UNTUK KOMPATIBILITAS
    function penampil_bar_judul_controller ($judul,$style) {
		$this->viewfrommyframework->penampil_bar_judul($judul,$style);
	}
	function tampil_add ($add,$toolbar,$src_wh) {
		$this->viewfrommyframework->penampil_tombol_add ($add,$toolbar,$src_wh);
	}	
	function tampil_bar_searching($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3) {
		$this->viewfrommyframework->penampil_bar_searching ($cari,$tabel_cari,$tabel_ctr,$tabel_cd1,$tabel_cd2,$input1,$input2,$input3);
	}
	function penampil_bar_judul_c ($judul,$style){
		$this->viewfrommyframework->penampil_bar_judul ($judul,$style);
	}  
	
	function penampil_tabel_komentar_controller ($array_atribut,$query_chat='SELECT * FROM `tbchat` order by idchat DESC',$count_tbchat,$jumlah_komen_ditampilkan,$submenu){
		$this->viewfrommyframework->penampil_tabel_komentar ($array_atribut,$query_chat,$count_tbchat,$jumlah_komen_ditampilkan,$submenu);
	}

	public function penampil_tabel_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar){
		return $this->viewfrommyframework->penampil_tabel($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	//hanya untuk jaga-jaga, untuk kompatibilitas.
	public function penampil_tabel_LAMA_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	public function penampil_tabel_tanpa_CRUID_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel_tanpa_CRUID($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	public function penampil_tabel_tanpa_CRUID_vertikal_controller ($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori='direktori',$direktori_avatar='../../public/img/pegawai/no-image.jpg'){
		return $this->viewfrommyframework->penampil_tabel_tanpa_CRUID_vertikal($array_atribut,$query_yang_mau_ditampilkan,$submenu,$kolom_direktori,$direktori_avatar);
	}

	function buat_komponen_form_controller($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->buat_komponen_form($type,$nama_komponen,$class,$id,$atribut,$event,$label,$value,$value_selected_combo,$submenu,$aksi,$perekam_id_untuk_button_ajax,$target_ajax,$data_ajax);
	}

	function form_general_2_view_panel_controller($panel,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view_panel($panel,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}
	
	function form_general_2_view_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}
	
	function form_general_2_view_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_view($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_vertikal_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL){
		$this->viewfrommyframework->form_general_2_vertikal($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_vertikal_non_iframe_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
		$this->viewfrommyframework->form_general_2_vertikal_non_iframe($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_2_vertikal_non_iframe_pegawai_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax=NULL) {
		$this->viewfrommyframework->form_general_2_vertikal_non_iframe_pegawai($komponen,$atribut_form,$array_option,$atribut_table,$judul,$tombol,$value_selected_combo,$target_action,$submenu,$aksi,$perekam_id_untuk_button_ajax,$class='form-control',$target_ajax,$data_ajax);
	}

	function form_general_controller($komponen,$atribut_form,$array_option,$atribut_table,$judul,$selected,$class='form-control',$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio){
		$this->viewfrommyframework->form_general($komponen,$atribut_form,$array_option,$atribut_table,$judul,$selected,$class,$array_value_label_checkbox,$disable_checkbox,$array_value_label_radio,$disable_radio);
	}

	function form_combo_database_controller($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected){
		$this->viewfrommyframework->form_combo_database($type='combo_database',$nama_komponen,$class,$id,$atribut,$kolom,$tabel,$selected);
	}

	function bootstrap_css_controller($path='assets/bootstrap/css/bootstrap.min.css'){
		$this->viewfrommyframework->bootstrap_css($path);
	}

	function fontawesome_css_controller($path='assets/fontawesome-free/css/all.min.css'){
		$this->viewfrommyframework->fontawesome_css($path);
	}

	function jquery_controller($path='assets/jquery/jquery.min.js'){
		$this->viewfrommyframework->jquery($path);
	}

	function bootstrap_js_controller($path='/login/vendor/bootstrap/js/bootstrap.min.js'){
		$this->viewfrommyframework->bootstrap_js($path);
	}

	function header_lengkap_bootstrap_controller($charset='utf-8',$content='width=device-width, initial-scale=1',$path_boostrap_js='/login/vendor/bootstrap/js/bootstrap.min.js',$path_jquery='/login/vendor/jquery/jquery-3.2.1.min.js',$path_fontawesome='/assets/fontawesome-free/css/all.min.css',$path_bootstrap_css='/login/css/css/bootstrap.css'){
		$this->viewfrommyframework->header_lengkap_bootstrap($charset,$content,$path_boostrap_js,$path_jquery,$path_fontawesome,$path_bootstrap_css);
	}

	function css_lain_controller($path){
		$this->viewfrommyframework->css_lain($path);
	}

	function js_lain_controller($path){
		$this->viewfrommyframework->js_lain($path);
	}
	//====================================================================================================================================

	function form_input_controller($type,$nama_komponen,$class='form-control',$id,$atribut,$event){
		$this->viewfrommyframework->form_input($type,$nama_komponen,$class,$id,$atribut,$event);
	}

	function form_area_controller($nama_komponen,$class='form-control',$id,$atribut){
		$this->viewfrommyframework->form_area($nama_komponen,$class,$id,$atribut);
	}

	function form_combo_manual_controller($nama_komponen,$class='form-control',$id,$atribut,$array_option,$selected){
		$this->viewfrommyframework->form_combo_manual($nama_komponen,$class,$id,$atribut,$array_option,$selected);
	}

//[END BATAS]

//[START BATAS]
//BATAS SEMUA FUNGSI YANG MERPRESENTASIKAN MODEL DAN FUNGSI-FUNGSI BANTU

	public function penarik_key_controller_panel($tabel_panel)
	{
		return $this->model_frommyframework->penarik_key_model_panel($tabel_panel);
	}

	//Fungsi ini bertujuan menarik semua nama kolom dari tabel_panel.
	//tabel_panel = tabel yang memuat nama-nama tabel, strukturnya: array('index'=>'nama_tabel'), itu saja
	//penarik_key_model_panel = menghasilkan semua nama kolom dari daftar tabel di tabel_panel.
	//dikatakan tabel_panel karena ada sebuah panel yang menggunakan n buah tabel untuk tampil pada frontend.
	//sehingga perlu menarik informasi nama kolom semua n buah tabel tersebut.
	public function penarik_kolom_controller($kolom_value,$kolom_label,$tabel)
	{
		return $this->model_frommyframework->penarik_kolom_model($kolom_value,$kolom_label,$tabel);
	}

	public function strToHex($str)
	{
		return $this->enkripsi->strToHex($str);
		
	}

	public function hexToStr($str)
	{
		return $this->enkripsi->hexToStr($str);
	}

	public function hapus_rekord($tabel,$id)
	{
		return $this->model_frommyframework->hapus_rekord($tabel,$id);
	}

	public function general_update_controller($kiriman,$tabel)
	{
		return $this->model_frommyframework->general_update_model($kiriman,$tabel);
	}

	public function general_insertion_controller($kiriman,$tabel)
	{
		return $this->model_frommyframework->general_insertion_model($kiriman,$tabel);
	}

	public function page_Recordset1_search($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$kolom_cari,$key_cari)
	{
		return $this->model_frommyframework->page_Recordset1_search($pageNum_Recordset1,$maxRows_Recordset1,$tabel,$kolom_cari,$key_cari);
	}

	//Fungsi ini mengenkripsi data yang hendak dikirim kemudian menerjemahkannya ke hex
	public function pengirim_terenkripsi_simetri($dataToEnkrip,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'))
	{
		$dataToEnkrip=str_replace('%20',' ',$dataToEnkrip);//data ga boleh memuat %20, terjadi jika dimasukkan lewat addressbar browser.
		$this->enkripsi->initialize($setting);
		$dataTerenkripsi=$this->enkripsi->enkripSimetri_data($dataToEnkrip);
		return $this->enkripsi->strToHex($dataTerenkripsi);
	}

	//Fungsi ini untuk mendekrip data
	public function penerima_terenkripsi_simetri($dataToDekrip,$setting=array('chiper'=>'aes-256','key'=>'1@@@@@!andisinra','mode'=>'ctr'))
	{
		$dataToDekrip=$this->enkripsi->hexToStr($dataToDekrip);
		$this->enkripsi->initialize($setting);
		return $this->enkripsi->dekripSimetri_data($dataToDekrip);
	}

	//Fungsi penarik dengan query user defined dimana menerima query dan token yang terenkripsi 
	//menerima enkripsi simetri dari kelas Enkripsi.php
	function user_defined_query_controller_terenkripsi($query_terenkripsi,$token_terenkripsi)
	{
		$query=$this->penerima_terenkripsi_simetri($query_terenkripsi);//jangan tambahakn $setting pada penerima_terenkripsi_simetri($query_terenkripsi,$setting)
		$token=$this->penerima_terenkripsi_simetri($token_terenkripsi);//karena error, dianggap menimpa default setting padahal kosong sehingga menghasilkan setingan kosong
		return $this->user_defined_query_controller($query,$token);
		//kembalian ini berupa array dengan key adalah nama-nama kolom 
		//TES: foreach ($hasil_query as $row){echo "<br>".$row['username'];}
		
	}

	public function page_row_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table)
	{
		return $this->model_frommyframework->page_row_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table);
		//hasilnya langsung berupa array, tinggal dipanggil menggunakan nama kolomnya, misal $testabel->namakolom
	}

	public function page_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table,$order='DESC')
	{
		return $this->model_frommyframework->page_Recordset1($halaman_ke,$jumlah_rekord_perhalaman,$table,$order);
		//buat tes: foreach ($testabel->result() as $row){echo "<br>".$row->username;}
		//ini berupa objek hasilnya, bukan item hyang siap pakai, untuk menggunakannya pake result() dulu baru pake nama kolomnya.
		//fungsi ini hanya untuk memelihara kompatibilitas sebelum migrasi
	}	

	public function page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1)
	{
		return $this->model_frommyframework->page_Recordset1_byquery($pageNum_Recordset1,$maxRows_Recordset1,$query_Recordset1);
		//foreach ($testabel as $row){echo "<br>".$row['nama'];}
		//ini berupa objek hasilnya, bukan item hyang siap pakai, untuk menggunakannya pake result() dulu baru pake nama kolomnya.
		//fungsi ini hanya untuk memelihara kompatibilitas sebelum migrasi
	}	

	public function penarik_key_controller($table)
	{
		return $this->model_frommyframework->penarik_key_model($table);
		//kembalian berupa array nama kolom tabel
	}

	public function jumlah_rekord($table)
	{
		return $this->model_frommyframework->jumlah_rekord($table);
		//kembaliannya hanyalah bilangan tunggal
	}


	public function jumlah_rekord_query($query,$token='andisinra')
	{
		return $this->model_frommyframework->jumlah_rekord_query($query,$token);
	}

	public function total_halaman($maxRows_Recordset1,$table)
	{
		return $this->model_frommyframework->total_halaman($maxRows_Recordset1,$table);
		//return $testabel; //kembaliannya hanyalah bilangan tunggal
	}

	//ALHAMDULILLAH SUKSES
	public function alert($e){$e=str_replace('%20',' ',$e);alert($e);}

	//ALHAMDULILLAH SUKSES
	public function user_defined_query_controller($query,$token="oke")
	{
		return $this->model_frommyframework->user_defined_query_model($query,$token);
		//foreach ($coba as $row){echo "<br>".$row['username'];}
		//haslnya adalah objek PDOStatment, untuk menggunakan anggap saja dia array, misal $coba['username'], secara umum $coba['nama_kolom']
		//atau hanya menggunakan indexnya $coba['$i'] dimana $i adalah integer. ini karena saat dia didefinisikan di kelas model_frommyframework
	}

	public function user_defined_query_controller_as_array($query,$token="oke")
	{
		return $this->model_frommyframework->user_defined_query_model_as_array($query,$token);
		//foreach ($coba as $row){echo "<br>".$row['username'];}
		//haslnya adalah objek PDOStatment, untuk menggunakan anggap saja dia array, misal $coba['username'], secara umum $coba['nama_kolom']
		//atau hanya menggunakan indexnya $coba['$i'] dimana $i adalah integer. ini karena saat dia didefinisikan di kelas model_frommyframework
	}

	//INI HANYA CONTOH PENGGUNAAN ENKRIPSI, SEJATINYA DISANA MESTI ADA DEKRIP SEBELUM QUERY, BTW INSHAA ALLAH BISA DITERAPKAN PADA LOGIC DILUAR FUNGSI
	//Fungsi penarik dengan query user defined dimana menerima query dan token yang terenkripsi 
	//menerima enkripsi simetri dari kelas Enkripsi.php
	function user_defined_query_controller_as_array_terenkripsi($query_terenkripsi,$token_terenkripsi)
	{
		$query=$this->penerima_terenkripsi_simetri($query_terenkripsi);//jangan tambahakn $setting pada penerima_terenkripsi_simetri($query_terenkripsi,$setting)
		$token=$this->penerima_terenkripsi_simetri($token_terenkripsi);//karena error, dianggap menimpa default setting padahal kosong sehingga menghasilkan setingan kosong
		return $this->user_defined_query_controller_as_array($query,$token);
		//kembalian ini berupa array dengan key adalah nama-nama kolom 
		//TES: foreach ($hasil_query as $row){echo "<br>".$row['username'];}
		
	}

	//ALHAMDULILLAH FUNGSI INI DITUJUKAN UNTUK MENGAMBIL SEMUA KEY DARI TABEL ATAU SEMBARANG QUERY YANG MENGHASILKAN TABEL UNTUK DITAMPILKAN
	public function penarik_key_string_ut_sebarang_query_controller($query){
		return $this->model_frommyframework->penarik_key_string_ut_sebarang_query_model($query);
	}

	public function konvers_recordset_PDOStatement_to_array_controller($recordset){
		return $this->model_frommyframework->konvers_recordset_PDOStatement_to_array($recordset);
	}

	public function konvers_recordset_CI_to_array_controller($Recordset1,$nama_kolom){
        return $this->model_frommyframework->konvers_recordset_CI_to_array($Recordset1,$nama_kolom);
    }

    //penarik key untuk query yang dihasilkan oleh perintah $this->db() milik CI
    public function penarik_key_query_CI_controller($query){
		return $this->model_frommyframework->penarik_key_query_CI($query);
    }
//[END BATAS]

/**
 * Pertanyaan tersisa:
 * Dimana menempatkan gerbang.php?
 * Bagaimana menerapkan php moderen peritem fungsi?
 * Bagaimana generator?
 * Bagaimana exeption?
 * Bagaimana error handler?
 */
}
