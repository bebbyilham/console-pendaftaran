  <style>
      .sweet_loader {
          width: 140px;
          height: 140px;
          margin: 0 auto;
          animation-duration: 0.5s;
          animation-timing-function: linear;
          animation-iteration-count: infinite;
          animation-name: ro;
          transform-origin: 50% 50%;
          transform: rotate(0) translate(0, 0);
      }

      @keyframes ro {
          100% {
              transform: rotate(-360deg) translate(0, 0);
          }
      }
  </style>
  <!-- Header -->
  <div class="header bg-gradient-info pb-4">
      <div class="container-fluid">
          <div class="header-body">
              <div class="row align-items-center py-2">
                  <div class="col-lg-12 col-12 text-center">
                      <h6 class="h2 text-white d-inline-block mb-0">ANJUNGAN PENDAFTARAN MANDIRI</h6>
                      <!-- <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                          <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                              <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                              <li class="breadcrumb-item active" aria-current="page">Default</li>
                          </ol>
                      </nav> -->
                  </div>
                  <!-- <div class="col-lg-6 col-5 text-right">
                      <a href="#" class="btn btn-sm btn-neutral">New</a>
                      <a href="#" class="btn btn-sm btn-neutral">Filters</a>
                  </div> -->
              </div>

              <!-- Card stats -->
              <div class="row">
                  <!-- <div class="col-12">
                      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">
                              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                          </ol>
                          <div class="carousel-inner">
                              <div class="carousel-item active">
                                  <img class="d-block w-100" src="<?php echo base_url('assets/img/image_blog/66313f014cd933236aead060f7e72e13.png'); ?>" alt="First slide">
                              </div>
                              <div class="carousel-item">
                                  <img class="d-block w-100" src="<?php echo base_url('assets/img/image_blog/66313f014cd933236aead060f7e72e13.png'); ?>" alt="Second slide">
                              </div>
                              <div class="carousel-item">
                                  <img class="d-block w-100" src="<?php echo base_url('assets/img/image_blog/66313f014cd933236aead060f7e72e13.png'); ?>" alt="Third slide">
                              </div>
                          </div>
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="sr-only">Next</span>
                          </a>
                      </div>
                  </div> -->

                  <div class="col-4">
                      <div class="card card-stats">
                          <!-- Card body -->
                          <div class="card-body">
                              <div class="row">
                                  <div class="col">
                                      <h5 class="card-title text-uppercase text-muted mb-0">Tanggal</h5>
                                      <span class="h2 font-weight-bold mb-0"><?= date('d-m-Y') ?></span>
                                  </div>
                                  <div class="col-auto">
                                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                          <i class="fas fa-calendar-alt"></i>
                                      </div>
                                  </div>
                              </div>
                              <p class="mt-3 mb-0 text-sm">
                                  <!-- <span class="text-success mr-2"><i class="fas fa-calendar-alt"></i></span> -->
                                  <!-- <span class="text-nowrap"><?= date('d-m-Y') ?></span> -->
                              </p>
                          </div>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="card card-stats">
                          <!-- Card body -->
                          <div class="text-center p-1">
                              <a href="#">
                                  <img src="<?= base_url() ?>assets/logo/logosejiwa.png">
                              </a>
                          </div>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="card card-stats">
                          <!-- Card body -->
                          <div class="card-body">
                              <div class="row">
                                  <div class="col">
                                      <h5 class="card-title text-uppercase text-muted mb-0">Jam</h5>
                                      <span class="h2 font-weight-bold mb-0"><span id="jam"></span> : <span id="menit"></span> : <span id="detik"></span></span>
                                  </div>
                                  <div class="col-auto">
                                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                          <i class="fas fa-clock"></i>
                                      </div>
                                  </div>
                              </div>
                              <p class="mt-3 mb-0 text-sm">
                                  <!-- <span class="text-success mr-2"><i class="fas fa-calendar-alt"></i></span> -->
                                  <!-- <span class="text-nowrap"><?= date('d-m-Y') ?></span> -->
                              </p>
                          </div>
                      </div>
                  </div>

              </div>
          </div>
      </div>
  </div>
  <div class="container-fluid my-5">
      <div class="row">
          <div class="col-6">
              <div class="card card-pricing bg-gradient-orange border-0 text-center mb-4">
                  <div class="card-header bg-transparent">
                      <h4 class="text-uppercase ls-1 text-white py-3 mb-0">UMUM</h4>
                  </div>
                  <div class="card-body px-lg-7">
                      <div class="display-2 text-white my-4"><img src="<?php echo base_url('assets/logo/userwh.png'); ?>" alt="logobpjs"></div>
                      <!-- <span class=" text-white">per application</span> -->
                      <!-- <ul class="list-unstyled my-4">
                          <li>
                              <div class="d-flex align-items-center">
                                  <div>
                                      <div class="icon icon-xs icon-shape bg-white shadow rounded-circle">
                                          <i class="fas fa-terminal"></i>
                                      </div>
                                  </div>
                                  <div>
                                      <span class="pl-2 text-sm text-white">Complete documentation</span>
                                  </div>
                              </div>
                          </li>
                          <li>
                              <div class="d-flex align-items-center">
                                  <div>
                                      <div class="icon icon-xs icon-shape bg-white shadow rounded-circle">
                                          <i class="fas fa-pen-fancy"></i>
                                      </div>
                                  </div>
                                  <div>
                                      <span class="pl-2 text-sm text-white">Working materials in Sketch</span>
                                  </div>
                              </div>
                          </li>
                          <li>
                              <div class="d-flex align-items-center">
                                  <div>
                                      <div class="icon icon-xs icon-shape bg-white shadow rounded-circle">
                                          <i class="fas fa-hdd"></i>
                                      </div>
                                  </div>
                                  <div>
                                      <span class="pl-2 text-sm text-white">2GB cloud storage</span>
                                  </div>
                              </div>
                          </li>
                      </ul> -->
                  </div>
                  <div class="card-footer bg-transparent">
                      <button type="button" class="btn btn-primary mb-3 btn_daftar_umum">DAFTAR</button>
                      <!-- <a href="#!" class=" text-white">Request a demo</a> -->
                  </div>
              </div>
          </div>
          <div class="col-6">
              <div class="card card-pricing bg-gradient-success border-0 text-center mb-4">
                  <div class="card-header bg-transparent">
                      <h4 class="text-uppercase ls-1 text-white py-3 mb-0">JKN</h4>
                  </div>
                  <div class="card-body px-lg-7">
                      <div class="display-2 text-white my-4"><img src="<?php echo base_url('assets/logo/bpjswhite128.png'); ?>" alt="logobpjs"></div>
                  </div>
                  <div class="card-footer bg-transparent">
                      <button type="button" id="daftar-jkn" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-daftar-jkn">DAFTAR</button>
                      <div class="modal fade" id="modal-daftar-jkn" tabindex="-1" role="dialog" aria-labelledby="modal-daftar-jkn" aria-hidden="true">
                          <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <b class="text-uppercase ls-1 py-2 text-white mb-0">PILIH JENIS DAFTAR</b>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="py-3 text-center">
                                          <button type="button" id="onsitejkn" class="btn btn-danger btn-lg btn-block mb-4"><i class="fas fa-hospital mr-2"></i>ONSITE</button>
                                          <button type="button" id="onlinejkn" class="btn btn-info btn-lg btn-block" data-dismiss="modal" data-toggle="modal" data-target="#modal-kodebooking-jkn"><i class="fas fa-mobile-alt mr-2"></i>ONLINE</button>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <!-- <button type="button" class="btn btn-white">ONLINE</button>
                                      <button type="button" class="btn btn-white ml-auto" data-dismiss="modal">ONSITE</button> -->
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal fade" id="modal-kodebooking-jkn" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-kodebooking-jkn" aria-hidden="true">
                          <div class="modal-dialog modal- modal-dialog-centered" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <b class="text-uppercase ls-1 py-2 text-white mb-0">ONLINE</b>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body p-0">
                                      <div class="card bg-gradient-success border-0 mb-0">
                                          <div class="card-body px-lg-5 py-lg-5">
                                              <form role="form">
                                                  <!-- <div class="row">
                                                      <div class="col-8"> -->
                                                  <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-collection"></i></span>
                                                          </div>
                                                          <input id="kodebookingjkn" class="form-control" placeholder="Kode Booking / No. MR / No. BPJS " autocomplete="off">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text hapus_text"><i class="fas fa-backspace text-danger"></i></span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <!-- </div>
                                                      <div class="col-4">
                                                          <div class="form-group">
                                                              <button type="button" id="btn_cek_kodebooking" class="btn btn-primary my-2">PROSES</button>
                                                          </div>
                                                      </div> -->


                                                  <!-- <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                          </div>
                                                          <input class="form-control" placeholder="Password" type="password">
                                                      </div>
                                                  </div> -->
                                                  <div class="text-center">
                                                      <button type="button" id="btn_cek_kodebooking" class="btn btn-primary btn-block my-2">PROSES</button>
                                                  </div>
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal fade" id="modal-onsite-jkn" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-kodebooking-jkn" aria-hidden="true">
                          <div class="modal-dialog modal- modal-dialog-centered" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <b class="text-uppercase ls-1 py-2 text-white mb-0">ONSITE</b>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body p-0">
                                      <div class="card bg-gradient-success border-0 mb-0">
                                          <div class="card-body px-lg-5 py-lg-5">
                                              <form role="form">
                                                  <!-- <div class="row">
                                                      <div class="col-8"> -->
                                                  <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-collection"></i></span>
                                                          </div>
                                                          <input id="kodebookingjkn_onsite" class="form-control" placeholder="Kode Booking / No. MR / No. BPJS ">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text hapus_text"><i class="fas fa-backspace text-danger"></i></span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <!-- </div>
                                                      <div class="col-4">
                                                          <div class="form-group">
                                                              <button type="button" id="btn_cek_kodebooking" class="btn btn-primary my-2">PROSES</button>
                                                          </div>
                                                      </div> -->


                                                  <!-- <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                          </div>
                                                          <input class="form-control" placeholder="Password" type="password">
                                                      </div>
                                                  </div> -->
                                                  <div class="text-center">
                                                      <button type="button" id="btn_cek_jkn_onsite" class="btn btn-primary btn-block my-2">PROSES</button>
                                                  </div>
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal fade" id="modal-poli" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-kodebooking-jkn" aria-hidden="true">
                          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <b class="text-uppercase ls-1 py-2 text-white mb-0">Pilih Poli</b>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body p-0">
                                      <div class="card bg-gradient-success border-0 mb-0">
                                          <div class="card-body px-lg-5 py-lg-5">
                                              <div class="row datapoli">

                                              </div>

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal fade" id="modal-jadwalpoli" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modal-kodebooking-jkn" aria-hidden="true">
                          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <b class="text-uppercase ls-1 py-2 text-white mb-0">Pilih Dokter</b>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body p-0">
                                      <input type="text" id="idpegawai">
                                      <input type="text" id="kodepoli">
                                      <input type="text" id="kodedokter">
                                      <input type="text" id="kodeantrean">
                                      <input type="text" id="jampraktek">
                                      <input type="text" id="idruangan">
                                      <div class="card bg-gradient-success border-0 mb-0">
                                          <div class="card-body px-lg-5 py-lg-5">
                                              <div class="card">

                                                  <div class="card-body">
                                                      <ul class="list-group list-group-flush list my--3">
                                                          <div class="datajadwaldokter"></div>
                                                      </ul>
                                                  </div>
                                              </div>

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- <div class="card shadow">
              <div class="mx-auto my-4" style="width: 10rem;">
                  <img src="#" class="card-img mb-4 rounded-circle">
              </div>

          </div> -->
      </div>
      <script>
          $(document).ready(function() {
              //   $('#onsitejkn').attr('disabled', true);
              $('.btn_daftar_umum').attr('disabled', true);
              var weHaveSuccess = false;

              //Cert
              /// Authentication setup ///
              qz.security.setCertificatePromise(function(resolve, reject) {
                  $.ajax("<?= base_url() ?>assets/override.crt").then(resolve, reject);
              });

              qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
              qz.security.setSignaturePromise(function(toSign) {
                  return function(resolve, reject) {
                      //Preferred method - from server
                      //            fetch("/secure/url/for/sign-message?request=" + toSign, {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
                      //              .then(function(data) { data.ok ? resolve(data.text()) : reject(data.text()); });
                      $.post("assets/signing/sign-message.php", {
                          request: toSign
                      }).then(resolve, reject);
                      //Alternate method - unsigned
                      // resolve(); 
                  };
              });


              //connect qz-tray
              qz.websocket.connect()

              $('#daftar-jkn').on('click', function() {
                  $('#kodebookingjkn').val('');
                  $('#kodebookingjkn_onsite').val('');
              });

              $('.hapus_text').on('click', function() {
                  $('#kodebookingjkn').val('');
                  $('#kodebookingjkn').focus();
                  $('#kodebookingjkn_onsite').val('');
                  $('#kodebookingjkn_onsite').focus();
              })

              $('#modal-kodebooking-jkn').on('shown.bs.modal', function() {
                  $('#kodebookingjkn').focus();
              })
              $('#modal-onsite-jkn').on('shown.bs.modal', function() {
                  $('#kodebookingjkn_onsite').focus();
              })

              var sweet_loader = '<div class="sweet_loader"><svg viewBox="0 0 140 140" width="140" height="140"><g class="outline"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="rgba(0,0,0,0.1)" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><g class="circle"><path d="m 70 28 a 1 1 0 0 0 0 84 a 1 1 0 0 0 0 -84" stroke="#71BBFF" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-dashoffset="200" stroke-dasharray="300"></path></g></svg></div>';

              $(document).on('click', '.sweet-alert-trigger', function() {
                  // $.ajax({
                  // 	type: 'POST',
                  // 	url:  myurl,
                  // 	data: str,
                  // 	xhr: function() {
                  // 		var xhr = $.ajaxSettings.xhr();
                  // 		xhr.upload.onprogress = function(e) {
                  swal.fire({
                      html: '<h4>Loading...</h4>',
                      onRender: function() {
                          $('.swal2-content').prepend(sweet_loader);
                      }
                  });
                  // 	};
                  // 	return xhr;
                  // },
                  // success: function(json) {
                  // 	try {
                  // 		json = JSON.parse(json);
                  // 	}
                  // 	catch(error) {
                  // 		ajax_icon_handling(false);
                  // 		return false;
                  // 	}
                  // 	if(json.success) {
                  setTimeout(function() {
                      swal.fire({
                          icon: 'success',
                          html: '<h4>Success!</h4>'
                      });
                  }, 700);
                  // 	} else {
                  // setTimeout(function() {
                  // 	swal.fire({
                  // 		icon: 'error',
                  // 		html: '<h4>Whoops!</h4><h5>Something went wrong.</h5>'
                  // 	});
                  // }, 700);
                  // 	}
                  // },
                  // error: function() {
                  // setTimeout(function() {
                  // 	swal.fire({
                  // 		icon: 'error',
                  // 		html: '<h4>Whoops!</h4><h5>Something went wrong.</h5>'
                  // 	});
                  // }, 700);
                  // }
                  // });
              });


              $('#btn_cek_kodebooking').on('click', function() {
                  var kodebooking = $('#kodebookingjkn').val();
                  $('#btn_cek_kodebooking').attr('disabled', true);
                  if (kodebooking == '') {
                      Swal.fire({
                          icon: 'error',
                          title: 'Masukan No. MR/No. Kartu BPJS/Kodebooking',
                          text: 'Peringatan',
                      });
                      $('#btn_cek_kodebooking').attr('disabled', false);
                  } else {
                      swal.fire({
                          html: '<h4>Sedang diproses</h4><span class="message_cek"></span>',
                          showCancelButton: false,
                          showConfirmButton: false,
                          onRender: function() {
                              $('.swal2-content').prepend(sweet_loader);
                          }
                      });

                      $.ajax({
                          url: '<?php echo base_url(); ?>dashboard/cek_pasien',
                          method: 'POST',
                          data: {
                              kodebooking: kodebooking
                          },
                          dataType: 'JSON',
                          success: function(data) {
                              $('#btn_cek_kodebooking').attr('disabled', false);
                              weHaveSuccess = true;
                              console.log('cekpasien :', data);
                              var code_cek_pasien = data.metadata.code
                              var message_cek_pasien = data.metadata.message
                              if (code_cek_pasien == '200') {
                                  $('.message_cek').text(message_cek_pasien);
                                  var cek_jeniskunjungan = data.metadata.result.jeniskunjungan
                                  var noMR = data.metadata.result.norm
                                  var noKartu = data.metadata.result.nomorkartu
                                  var result_kodebooking = data.metadata.result.kodebooking
                                  if (cek_jeniskunjungan == '3') {
                                      console.log('JENIS KUNJUNGAN = 3 Kontrol')
                                      var no_surkon = data.metadata.result.nomorreferensi
                                      $.ajax({
                                          url: '<?php echo base_url(); ?>dashboard/cek_kunjungan',
                                          method: 'POST',
                                          data: {
                                              kodebooking: result_kodebooking
                                          },
                                          dataType: 'JSON',
                                          success: function(data) {
                                              var code_cek_kunjungan = data.metadata.code
                                              var message_cek_kunjungan = data.metadata.message
                                              if (code_cek_kunjungan == '200') {
                                                  alert('01')

                                                  $.ajax({
                                                      url: "<?php echo base_url(); ?>dashboard/cek_surkon",
                                                      method: "POST",
                                                      dataType: 'JSON',
                                                      data: {
                                                          no_surkon: no_surkon
                                                      },
                                                      success: function(data) {
                                                          console.log('cek_surkon', data);
                                                          $('.message_cek').text('Pengecekan Berkas');
                                                          var today = new Date();
                                                          var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
                                                          var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                                          var dateTime = date + ' ' + time;

                                                          //   console.log(dateTime)

                                                          if (!data.metaData) {
                                                              var noSuratKontrol = data.noSuratKontrol

                                                              var asalRujukan = data.sep.provPerujuk.asalRujukan
                                                              var tglRujukan = data.sep.provPerujuk.tglRujukan
                                                              var noRujukan = data.sep.provPerujuk.noRujukan
                                                              var kdProviderPerujuk = data.sep.provPerujuk.kdProviderPerujuk
                                                              var poliTujuan = data.poliTujuan


                                                              var jeniskunjungan = cek_jeniskunjungan
                                                              var tglRencanaKontrol = data.tglRencanaKontrol
                                                              var kodeDokter = data.kodeDokter

                                                              if (tglRencanaKontrol == date) {
                                                                  $.ajax({
                                                                      url: '<?php echo base_url(); ?>dashboard/cek_jadwaldokter',
                                                                      method: 'POST',
                                                                      data: {
                                                                          tglRencanaKontrol: tglRencanaKontrol,
                                                                          poliTujuan: poliTujuan,
                                                                          kodeDokter: kodeDokter
                                                                      },
                                                                      dataType: 'JSON',
                                                                      success: function(data) {
                                                                          //   console.log('CEL_JADWAL DOKTER', data);
                                                                          var code_cek_jadwal = data.metadata.code
                                                                          var message_cek_jadwal = data.metadata.message

                                                                          if (code_cek_jadwal == '200') {
                                                                              $('.message_cek').text(message_cek_jadwal);
                                                                              var nama_dokter = data.metadata.result.dokter_nama
                                                                              //   console.log('OK');
                                                                              //   Swal.fire({
                                                                              //       icon: 'success',
                                                                              //       title: 'Rujukan',
                                                                              //       text: data.metadata.message,
                                                                              //   });
                                                                          } else {
                                                                              setTimeout(function() {
                                                                                  swal.fire({
                                                                                      icon: 'error',
                                                                                      title: 'Rujukan',
                                                                                      text: data.metadata.message,
                                                                                  });
                                                                              }, 700);
                                                                              //   Swal.fire({
                                                                              //       icon: 'error',
                                                                              //       title: 'Rujukan',
                                                                              //       text: data.metadata.message,
                                                                              //   });
                                                                          }
                                                                          $.ajax({
                                                                              url: '<?php echo base_url(); ?>dashboard/cariBnorujukan',
                                                                              method: 'POST',
                                                                              data: {
                                                                                  noRujukan: noRujukan,
                                                                                  asalRujukan: asalRujukan,
                                                                              },
                                                                              dataType: 'JSON',
                                                                              success: function(data) {


                                                                                  if (!data.metaData) {
                                                                                      var diagAwal = data.rujukan.diagnosa.kode
                                                                                      var ppkRujukan = data.rujukan.provPerujuk.kode
                                                                                      var noTelp = data.rujukan.peserta.mr.noTelepon
                                                                                      var klsRawatHak = data.rujukan.peserta.mr.noTelepon
                                                                                      var namappkRujukan = data.rujukan.provPerujuk.nama
                                                                                      $('.message_cek').text('Penerbitan E-SEP');
                                                                                      //   alert('RUJUKAN OK')
                                                                                      //   Swal.fire({
                                                                                      //       icon: 'info',
                                                                                      //       title: 'Rujukan',
                                                                                      //       text: 'Rujukan valid',
                                                                                      //   });
                                                                                      $.ajax({
                                                                                          url: '<?php echo base_url(); ?>dashboard/insertSEP',
                                                                                          method: 'POST',
                                                                                          data: {
                                                                                              noMR: noMR,
                                                                                              noKartu: noKartu,
                                                                                              asalRujukan: asalRujukan,
                                                                                              ppkRujukan: ppkRujukan,
                                                                                              noRujukan: noRujukan,
                                                                                              tglRujukan: tglRujukan,
                                                                                              diagAwal: diagAwal,
                                                                                              noTelp: noTelp,
                                                                                              poliTujuan: poliTujuan,
                                                                                              kodeDokter: kodeDokter,
                                                                                              kdProviderPerujuk: kdProviderPerujuk,
                                                                                              jeniskunjungan: jeniskunjungan,

                                                                                              poliTujuan: poliTujuan,
                                                                                              noSuratKontrol: noSuratKontrol,

                                                                                              user: 'APM',

                                                                                              jeniskunjungan: jeniskunjungan,
                                                                                          },
                                                                                          dataType: 'JSON',
                                                                                          success: function(data) {
                                                                                              console.log('data_SEP', data);
                                                                                              if (!data.metaData) {
                                                                                                  var nosep = data.sep.noSep;
                                                                                                  var tglSep = data.sep.tglSep;
                                                                                                  var jnsPelayanan = data.sep.jnsPelayanan;
                                                                                                  var kelasRawat = data.sep.kelasRawat;
                                                                                                  var kodeDiagnosa = diagAwal;
                                                                                                  var diagnosa = data.sep.diagnosa;
                                                                                                  var noRujukan = data.sep.noRujukan;
                                                                                                  var poli = data.sep.poli;
                                                                                                  var poliEksekutif = data.sep.poliEksekutif;
                                                                                                  var catatan = data.sep.catatan;
                                                                                                  var penjamin = data.sep.penjamin;
                                                                                                  var noKartu = data.sep.peserta.noKartu;
                                                                                                  var nama = data.sep.peserta.nama;
                                                                                                  var tglLahir = data.sep.peserta.tglLahir;
                                                                                                  var noMr = data.sep.peserta.noMr;
                                                                                                  var kelamin = data.sep.peserta.kelamin;
                                                                                                  var jnsPeserta = data.sep.peserta.jnsPeserta;
                                                                                                  var hakKelas = data.sep.peserta.hakKelas;
                                                                                                  var asuransi = data.sep.peserta.asuransi;
                                                                                                  var dinsos = data.sep.informasi.dinsos;
                                                                                                  var prolanisPRB = data.sep.informasi.prolanisPRB;
                                                                                                  var noSKTM = data.sep.informasi.noSKTM;
                                                                                                  var namaDokter = nama_dokter;
                                                                                                  var namaPpk = namappkRujukan;
                                                                                                  var kelasRawatNaik = '-';
                                                                                                  var pembiayaan = '-';
                                                                                                  var nomorTelp = noTelp;
                                                                                                  var tujuanKunj = data.sep.tujuanKunj;
                                                                                                  var flagProcedure = data.sep.flagProcedure;
                                                                                                  var kodePenunjang = 'tidak ada';
                                                                                                  var assestmenPel = data.sep.assestmenPel;
                                                                                                  $('.message_cek').text('Penerbitan E-SEP Berhasil');
                                                                                                  $.ajax({
                                                                                                      url: '<?php echo base_url(); ?>dashboard/pendaftaran_pasien',
                                                                                                      method: 'POST',
                                                                                                      data: {
                                                                                                          norm: noMR,
                                                                                                          kodepoli: poliTujuan,
                                                                                                          kodedokter: kodeDokter,
                                                                                                          tanggalperiksa: tglRencanaKontrol,
                                                                                                          kodebooking: result_kodebooking,
                                                                                                          nomorkartu: noKartu,
                                                                                                          nosep: nosep,
                                                                                                      },
                                                                                                      dataType: 'JSON',
                                                                                                      success: function(data) {
                                                                                                          console.log('KV', data);
                                                                                                          //   if (data) {
                                                                                                          //       alert('01')
                                                                                                          //   } else {
                                                                                                          //       alert('02')
                                                                                                          //   }
                                                                                                          var noregistrasi = data.metadata.result.noregistrasi

                                                                                                          $.ajax({
                                                                                                              url: '<?php echo base_url(); ?>dashboard/insert_sep_db',
                                                                                                              method: 'POST',
                                                                                                              data: {
                                                                                                                  noregistrasi: noregistrasi,
                                                                                                                  nosep: nosep,
                                                                                                                  tglSep: tglSep,
                                                                                                                  jnsPelayanan: jnsPelayanan,
                                                                                                                  kelasRawat: kelasRawat,
                                                                                                                  kodeDiagnosa: kodeDiagnosa,
                                                                                                                  diagnosa: diagnosa,
                                                                                                                  noRujukan: noRujukan,
                                                                                                                  poli: poli,
                                                                                                                  poliEksekutif: poliEksekutif,
                                                                                                                  catatan: catatan,
                                                                                                                  penjamin: penjamin,
                                                                                                                  noKartu: noKartu,
                                                                                                                  nama: nama,
                                                                                                                  tglLahir: tglLahir,
                                                                                                                  noMr: noMr,
                                                                                                                  kelamin: kelamin,
                                                                                                                  noMr: noMr,
                                                                                                                  jnsPeserta: jnsPeserta,
                                                                                                                  hakKelas: hakKelas,
                                                                                                                  asuransi: asuransi,
                                                                                                                  dinsos: dinsos,
                                                                                                                  prolanisPRB: prolanisPRB,
                                                                                                                  noSKTM: noSKTM,
                                                                                                                  namaDokter: namaDokter,
                                                                                                                  namaPpk: namaPpk,
                                                                                                                  kelasRawatNaik: kelasRawatNaik,
                                                                                                                  pembiayaan: pembiayaan,
                                                                                                                  nomorTelp: nomorTelp,
                                                                                                                  tujuanKunj: tujuanKunj,
                                                                                                                  flagProcedure: flagProcedure,
                                                                                                                  kodePenunjang: kodePenunjang,
                                                                                                                  assestmenPel: assestmenPel,
                                                                                                              },
                                                                                                              dataType: 'JSON',
                                                                                                              success: function(data) {
                                                                                                                  console.log('KV', data);
                                                                                                                  if (data) {
                                                                                                                      alert('01')
                                                                                                                  } else {
                                                                                                                      alert('02')
                                                                                                                  }

                                                                                                              }
                                                                                                          });

                                                                                                      }
                                                                                                  });

                                                                                                  setTimeout(function() {
                                                                                                      swal.fire({
                                                                                                          icon: 'success',
                                                                                                          html: '<h4>Berhasil</h4>'
                                                                                                      });
                                                                                                  }, 700);
                                                                                              } else {
                                                                                                  setTimeout(function() {
                                                                                                      swal.fire({
                                                                                                          icon: 'error',
                                                                                                          title: 'Peringatan',
                                                                                                          text: data.metaData.message,
                                                                                                      });
                                                                                                  }, 700);
                                                                                                  //   Swal.fire({
                                                                                                  //       icon: 'error',
                                                                                                  //       title: 'Peringatan',
                                                                                                  //       text: data.metaData.message,
                                                                                                  //   });
                                                                                              }

                                                                                          }
                                                                                      });

                                                                                      // Swal.fire({
                                                                                      //     icon: 'success',
                                                                                      //     title: 'Berhasil',
                                                                                      //     text: data.metaData.message,
                                                                                      // });
                                                                                  } else {
                                                                                      Swal.fire({
                                                                                          icon: 'error',
                                                                                          title: 'Peringatan',
                                                                                          text: data.metaData.message,
                                                                                      });
                                                                                  }
                                                                              }
                                                                          });

                                                                      }
                                                                  });

                                                              } else {
                                                                  Swal.fire({
                                                                      icon: 'error',
                                                                      title: 'Peringatan',
                                                                      text: 'Tanggal kontrol tidak sesuai. silahkan mendaftar di admisi.',
                                                                  });
                                                              }
                                                          } else {
                                                              Swal.fire({
                                                                  icon: 'error',
                                                                  title: 'Peringatan',
                                                                  text: data.metaData.message,
                                                              });
                                                          }


                                                          //   var result_surkon = {
                                                          //       result: '0'
                                                          //   }
                                                          //   result_surkon.push(data);

                                                          //   console.log('result_surkon', result_surkon);

                                                          //   console.log('CV', data);
                                                          //   var code_cek_surkon = data.metaData.code
                                                          //   if (code_cek_surkon == '200') {
                                                          //       alert('0')
                                                          //       var result_surkon = data.response
                                                          //       $.ajax({
                                                          //           url: '<?php echo base_url(); ?>dashboard/encrypt_surkon',
                                                          //           method: 'POST',
                                                          //           data: {
                                                          //               result_surkon: result_surkon
                                                          //           },
                                                          //           dataType: 'JSON',
                                                          //           success: function(data) {
                                                          //               console.log('KV', data);
                                                          //               if (data) {
                                                          //                   alert('01')
                                                          //               } else {
                                                          //                   alert('02')
                                                          //               }

                                                          //           }
                                                          //       });
                                                          //   } else {
                                                          //       Swal.fire({
                                                          //           icon: 'error',
                                                          //           title: 'Data Tidak Ditemukan',
                                                          //           text: data.metadata.message,
                                                          //       });
                                                          //   }
                                                      }
                                                  });
                                              } else {
                                                  setTimeout(function() {
                                                      swal.fire({
                                                          icon: 'error',
                                                          title: 'Kunjungan',
                                                          text: data.metadata.message,
                                                      });
                                                  }, 700);
                                              }

                                          }
                                      });

                                  } else if (cek_jeniskunjungan == '1') {
                                      alert('JENIS KUNJUNGAN = 1 Rujukan FKTP')
                                  } else if (cek_jeniskunjungan == '2') {
                                      alert('JENIS KUNJUNGAN = 2 Rujukan Internal')
                                  } else if (cek_jeniskunjungan == '4') {
                                      alert('JENIS KUNJUNGAN = 4 Rujukan Antar RS')
                                  } else {
                                      alert('Jenis Kunjungan Tidak di ketahui !')
                                  }
                              } else {
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Data Tidak Ditemukan',
                                      text: data.metadata.message,
                                  });
                              }
                              //   console.log('1. :', data.metadata.sep.noSep);

                              //   if (data.metadata.code == '200') {
                              //       var noantrean = data.metadata.noantrean;
                              //       var nosep = data.metadata.sep.noSep;
                              //       var namapasien = data.metadata.namapasien;
                              //       var nama_pasien = data.metadata.namapasien;
                              //       var ut = data.metadata.ut;
                              //       var jeniskelamin = data.metadata.jeniskelamin;
                              //       var tgllahir = data.metadata.tgllahir;
                              //       var namadokter = data.metadata.dokter;
                              //       var nomr = data.metadata.nomr;
                              //       var namapoli = data.metadata.namapoli;
                              //       var noregistrasi = data.metadata.no_registrasi;
                              //       Swal.fire({
                              //           icon: 'success',
                              //           title: 'Data Ditemukan',
                              //           text: 'Sedang Proses',
                              //       });

                              //       $('#modal-kodebooking-jkn').modal('hide');
                              //   } else {
                              //       Swal.fire({
                              //           icon: 'error',
                              //           title: 'Data Tidak Ditemukan',
                              //           text: data.metadata.message,
                              //       });
                              //   }
                          },
                          error: function(error) {
                              $('#btn_cek_kodebooking').attr('disabled', false);
                          },
                          complete: function() {
                              $('#btn_cek_kodebooking').attr('disabled', false);
                              if (!weHaveSuccess) {
                                  $('#btn_cek_kodebooking').attr('disabled', false);
                                  alert('Silahkan coba lagi!');
                              }

                          },
                      });
                  }

              });
              $('#onsitejkn').on('click', function() {
                  $('#onsitejkn').attr('disabled', true);
                  var weHaveSuccess = false;
                  $('.datapoli').html('');

                  $('#modal-daftar-jkn').modal('hide');

                  $.ajax({
                      url: '<?= base_url(); ?>dashboard/poli',
                      method: 'POST',
                      dataType: 'JSON',
                      success: function(data) {
                          weHaveSuccess = true;
                          var datapoli = data;
                          $.each(datapoli, function(i, result) {
                              // console.log('poli', datapoli);
                              $('#modal-poli').modal('show');

                              $('.datapoli').append(
                                  `<div class="col pilih_ruangan" idruangan="` + result.id_ruangan + `" koderuangan="` + result.kode_ruangan + `">
                                  <div class="col">
                                                    <div class="card card-stats">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <h5 class="card-title text-uppercase mb-0">` + result.nama_ruangan + `</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                  </div>
                                  </div>
                                  `
                              );
                          });
                          $('#onsitejkn').attr('disabled', false);
                      },
                      error: function(xhr, status, error) {
                          alert("Request error! " + xhr.status);
                      },
                      complete: function() {
                          if (!weHaveSuccess) {
                              $('#onsitejkn').attr('disabled', false);
                              alert('Silahkan coba lagi!');
                          }
                      }
                  })
              });

              $(document).on('click', '.pilih_ruangan', function() {
                  //   alert('TEST')
                  var idruangan = $(this).attr('idruangan');
                  var koderuangan = $(this).attr('koderuangan');
                  $('#idruangan').val(idruangan);
                  //   console.log('pilih_ruangan', idruangan);
                  $('.datajadwaldokter').html('');
                  $.ajax({
                      url: '<?php echo base_url(); ?>dashboard/jadwal_poli',
                      method: 'POST',
                      data: {
                          koderuangan: koderuangan,
                          idruangan: idruangan
                      },
                      dataType: 'JSON',
                      success: function(data) {
                          $('#modal-jadwalpoli').modal('show');
                          var datajadwaldokter = data;
                          var foto_url = '<?= $sejiwa_url . '/assets/img/foto_pegawai/' ?>';
                          $.each(datajadwaldokter, function(i, result) {
                              console.log('poli', foto_url);
                              $('#modal-poli').modal('show');

                              $('.datajadwaldokter').append(
                                  `
                                  <div>
                                  <li class="list-group-item px-0">
                                                              <div class="row align-items-center pilih_dokter" idpegawai="` + result.id_pegawai + `" kodepoli="` + result.poli_kdsubspesialis + `" kodedokter="` + result.kode_dpjp + `" kodeantrean="` + result.kodeantrean + `" jampraktek="` + result.buka + `-` + result.tutup + `">
                                                                  <div class="col-auto">
                                                                      <a href="#" class="avatar rounded-circle">
                                                                          <img alt="Image placeholder" src="` + foto_url + result.foto_pegawai + `">
                                                                      </a>
                                                                  </div>
                                                                  <div class="col ml--2">
                                                                      <h4 class="mb-0">
                                                                          <a href="#!">` + result.dokter_nama + `</a>
                                                                      </h4>
                                                                      <span class="text-success">●</span>
                                                                      <small>` + result.buka + ` - ` + result.tutup + `</small>
                                                                  </div>
                                                                  <div class="col-auto">
                                                                      <button type="button" class="btn btn-sm btn-primary">PILIH</button>
                                                                  </div>
                                                              </div>
                                  </li>
                                  </div>
                                  `
                              );
                          });

                      }
                  });
              });

              $(document).on('click', '.pilih_dokter', function() {
                  //   alert('TEST')
                  var idpegawai = $(this).attr('idpegawai');
                  var kodepoli = $(this).attr('kodepoli');
                  var kodedokter = $(this).attr('kodedokter');
                  var kodeantrean = $(this).attr('kodeantrean');
                  var jampraktek = $(this).attr('jampraktek');
                  //   console.log('pilih_ruangan', idruangan);
                  $('.datajadwaldokter').html('');
                  $.ajax({
                      url: '<?php echo base_url(); ?>dashboard/jadwal_poli',
                      method: 'POST',
                      data: {
                          koderuangan: koderuangan,
                          idruangan: idruangan
                      },
                      dataType: 'JSON',
                      success: function(data) {

                          //   $('#modal-jadwalpoli').modal('show');
                          //   var datajadwaldokter = data;
                          //   var foto_url = '<?= $sejiwa_url . '/assets/img/foto_pegawai/' ?>';
                          //   $.each(datajadwaldokter, function(i, result) {
                          //       console.log('poli', foto_url);
                          //       $('#modal-poli').modal('show');

                          //       $('.datajadwaldokter').append(
                          //           `
                          //           <div>
                          //           <li class="list-group-item px-0">
                          //                                       <div class="row align-items-center pilih_dokter">
                          //                                           <div class="col-auto">
                          //                                               <a href="#" class="avatar rounded-circle">
                          //                                                   <img alt="Image placeholder" src="` + foto_url + result.foto_pegawai + `">
                          //                                               </a>
                          //                                           </div>
                          //                                           <div class="col ml--2">
                          //                                               <h4 class="mb-0">
                          //                                                   <a href="#!">` + result.dokter_nama + `</a>
                          //                                               </h4>
                          //                                               <span class="text-success">●</span>
                          //                                               <small>` + result.buka + ` - ` + result.tutup + `</small>
                          //                                           </div>
                          //                                           <div class="col-auto">
                          //                                               <button type="button" class="btn btn-sm btn-primary">PILIH</button>
                          //                                           </div>
                          //                                       </div>
                          //           </li>
                          //           </div>
                          //           `
                          //       );
                          //   });

                      }
                  });
              });

          });
      </script>