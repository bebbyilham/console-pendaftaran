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
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body">
                                      <div class="py-3 text-center">
                                          <button type="button" id="onsitejkn" class="btn btn-secondary btn-lg btn-block mb-4">ONSITE</button>
                                          <button type="button" id="onlinejkn" class="btn btn-secondary btn-lg btn-block" data-dismiss="modal" data-toggle="modal" data-target="#modal-kodebooking-jkn">ONLINE</button>
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
                          <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                              <div class="modal-content bg-gradient-success">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span>
                                      </button>
                                  </div>
                                  <div class="modal-body p-0">
                                      <div class="card bg-gradient-success border-0 mb-0">

                                          <div class="card-body px-lg-5 py-lg-5">
                                              <!-- <div class="text-center text-muted mb-4">
                                                  <small>Or sign in with credentials</small>
                                              </div> -->
                                              <form role="form">
                                                  <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-collection"></i></span>
                                                          </div>
                                                          <input id="kodebookingjkn" class="form-control" placeholder="Kode Booking / No. MR / No. BPJS ">
                                                      </div>
                                                  </div>
                                                  <!-- <div class="form-group">
                                                      <div class="input-group input-group-merge input-group-alternative">
                                                          <div class="input-group-prepend">
                                                              <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                                          </div>
                                                          <input class="form-control" placeholder="Password" type="password">
                                                      </div>
                                                  </div> -->
                                                  <div class="text-center">
                                                      <button type="button" id="btn_cek_kodebooking" class="btn btn-primary my-2">PROSES</button>
                                                  </div>
                                              </form>
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
              $('#onsitejkn').attr('disabled', true);
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
              });

              $('#btn_cek_kodebooking').on('click', function() {
                  var kodebooking = $('#kodebookingjkn').val();
                  $('#btn_cek_kodebooking').attr('disabled', true);
                  if (kodebooking == '') {
                      $('#btn_cek_kodebooking').attr('disabled', false);
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Masukan No. MR/No. Kartu BPJS/Kodebooking',
                          text: 'Peringatan',
                      });
                      $.ajax({
                          url: '<?php echo base_url(); ?>dashboard/cekDataKodebooking',
                          method: 'POST',
                          data: {
                              kodebooking: kodebooking
                          },
                          dataType: 'JSON',
                          success: function(data) {
                              $('#btn_cek_kodebooking').attr('disabled', false);
                              weHaveSuccess = true;
                              console.log('sep :', data);
                              //   console.log('1. :', data.metadata.sep.noSep);
                              //   console.log(data.metadata.code);
                              //   var pesan = data.metadata.message;
                              if (data.metadata.code == '200') {
                                  var noantrean = data.metadata.noantrean;
                                  var nosep = data.metadata.sep.noSep;
                                  var namapasien = data.metadata.namapasien;
                                  var nama_pasien = data.metadata.namapasien;
                                  var ut = data.metadata.ut;
                                  var jeniskelamin = data.metadata.jeniskelamin;
                                  var tgllahir = data.metadata.tgllahir;
                                  var namadokter = data.metadata.dokter;
                                  var nomr = data.metadata.nomr;
                                  var namapoli = data.metadata.namapoli;
                                  var noregistrasi = data.metadata.no_registrasi;
                                  Swal.fire({
                                      icon: 'success',
                                      title: 'Data Ditemukan',
                                      text: 'SEP Berhasil diterbitkan',
                                  });
                                  //   //LABEL
                                  //   var no_sep = nosep;

                                  //   var nama_pasien = namapasien;
                                  //   var umur_tahun = ut
                                  //   var jenis_kelamin = jeniskelamin
                                  //   var no_mr = data.metadata.nomr;
                                  //   qz.printers.find("Label").then(function(found) {
                                  //       console.log(found);
                                  //   });

                                  //   qz.printers.find("Label").then(function(printer) {
                                  //       // Create a default config for the found printer
                                  //       var config = qz.configs.create(printer);

                                  //       // Raw ZPL
                                  //       var nomorsep = ['^XA',
                                  //           '^CF0,30,20',
                                  //           '^FO130,40^FWN^FD' + '   :' + nama_pasien + ' ( ' + jenis_kelamin + ' - ' + umur_tahun + ' TH)' + '^FS',
                                  //           '^BY2,2,60',
                                  //           '^FO150,70^B3N,N,90,Y^FD' + no_sep + '^FS^',
                                  //           'XZ',
                                  //           '^XA',
                                  //           '^CF0,30,20',
                                  //           '^FO200,40^FWN^FD' + '   :' + nama_pasien + ' ( ' + jenis_kelamin + ' - ' + umur_tahun + ' TH)' + '^FS',
                                  //           '^BY4,2,60',
                                  //           '^FO220,70^B3N,N,90,Y^FD' + no_mr + '^FS^',
                                  //           'XZ'
                                  //       ];
                                  //       return qz.print(config, nomorsep);
                                  //   }).catch(function(e) {
                                  //       console.error(e);
                                  //       toastr["error"]("Printer Tidak Ditemukan");
                                  //   });

                                  //RECEIPT
                                  qz.printers.find("Receipt").then(function(printer) {
                                      // Create a default config for the found printer
                                      var config = qz.configs.create(printer);

                                      // Raw ZPL
                                      var angka = noantrean;
                                      console.log(nosep);
                                      var no_sep = nosep;
                                      var today = new Date();
                                      var month = today.getMonth() + 1;
                                      var time = today.getDate() + "/" + month + "/" + today.getFullYear() + " " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                      var data = [
                                          '\x1B' + '\x40', // init
                                          '\x1B' + '\x61' + '\x31', // center align
                                          '\x1B' + '\x21' + '\x30',
                                          'Bukti Registrasi  \x0A',
                                          '\x1B' + '\x21' + '\x0A' + '\x1B' + '\x45' + '\x0A', // em mode off
                                          '\x1B' + '\x4D' + '\x30', //F normal text
                                          'Pasien Rawat Jalan - ' + time + '\x0A', // text and line break
                                          '\x0A', // line break
                                          '\x1B' + '\x61' + '\x30', // left align
                                          '  No. RM         : ' + nomr + '\x0A',
                                          '  Nama Pasien    : ' + namapasien + '\x0A',
                                          '  Tanggal Lahir  : ' + tgllahir + '\x0A',
                                          '  Umur           : ' + ut + '\x0A',
                                          '  No. Registrasi : ' + noregistrasi + '\x0A',
                                          '  Tujuan         : ' + namapoli + '\x0A',
                                          '  Dokter/Psikolog : ' + namadokter + '\x0A',
                                          '  Tanggal        : ' + time + '\x0A',
                                          '\x0A',
                                          '\x1B' + '\x61' + '\x32', // right align
                                          '\x1B' + '\x21' + '\x30', // em mode on
                                          'No. Antrian : ' + noantrean + '\x0A',
                                          '\x1B' + '\x21' + '\x0A' + '\x1B' + '\x45' + '\x0A', // em mode off
                                          '\x0A' + '\x0A',
                                          '\x1B' + '\x61' + '\x31', // center align
                                          '------- RS Jiwa Prof. HB Saanin Padang -------' + '\x0A',
                                          '\x0A' + '\x0A',
                                          '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A',
                                          '\x1B' + '\x69', // cut paper (old syntax)
                                          '\x10' + '\x14' + '\x01' + '\x00' + '\x05', // Generate Pulse to kick-out cash drawer**
                                      ];
                                      return qz.print(config, data);
                                  }).catch(function(e) {
                                      console.log(e);
                                      //   alert("Printer Tidak Ditemukan");
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Printer Tidak Ditemukan',
                                          text: data.metadata.message,
                                      });
                                  });
                                  qz.printers.find("Receipt").then(function(printer) {
                                      // Create a default config for the found printer
                                      var config = qz.configs.create(printer);
                                      console.log(nosep);
                                      var no_sep = nosep;

                                      // Raw ZPL
                                      // var angka = noantrean;
                                      var today = new Date();
                                      var month = today.getMonth() + 1;
                                      var time = today.getDate() + "/" + month + "/" + today.getFullYear() + " " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                      var data = [{
                                          type: 'pixel',
                                          format: 'pdf',
                                          flavor: 'file',
                                          data: '<?= base_url('dashboard/lembarSepPrint/') ?>' + nosep
                                      }];
                                      return qz.print(config, data);
                                  }).catch(function(e) {
                                      console.log(e);
                                      //   alert("Printer Tidak Ditemukan");
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Printer Tidak Ditemukan',
                                          text: data.metadata.message,
                                      });

                                  });
                                  $('#modal-kodebooking-jkn').modal('hide');
                              } else {
                                  if (data.metadata.message == 'Kunjungan Sudah Ada') {
                                      console.log('Kunjungan Ada :', data);
                                      var ceknosep = data.metadata.nosep
                                      var ceknoantrean = data.metadata.noantrean
                                      var cekdokter = data.metadata.dokter
                                      var namapasien = data.metadata.namapasien
                                      var ut = data.metadata.ut
                                      var mr = data.metadata.nomr
                                      var jeniskelamin = data.metadata.jeniskelamin
                                      var noantrean = data.metadata.noantrean;
                                      var nosep = data.metadata.nosep;
                                      var namapasien = data.metadata.namapasien;
                                      var nama_pasien = data.metadata.namapasien;
                                      var ut = data.metadata.ut;
                                      var jeniskelamin = data.metadata.jeniskelamin;
                                      var tgllahir = data.metadata.tgllahir;
                                      var namadokter = data.metadata.dokter;
                                      var nomr = data.metadata.nomr;
                                      var noregistrasi = data.metadata.no_registrasi;
                                      var namapoli = data.metadata.namapoli;
                                      Swal.fire({
                                          title: data.metadata.message,
                                          text: "Apakah anda ingin mencetak Label, SEP, dan Bukti Registrasi ?",
                                          icon: 'warning',
                                          showCancelButton: true,
                                          confirmButtonColor: '#3085d6',
                                          cancelButtonColor: '#d33',
                                          confirmButtonText: 'Ya',
                                          cancelButtonText: 'Tidak'
                                      }).then((result) => {
                                          if (result.isConfirmed) {
                                              //   //LABEL
                                              //   var no_sep = ceknosep;

                                              //   var nama_pasien = namapasien;
                                              //   var umur_tahun = ut
                                              //   var jenis_kelamin = jeniskelamin
                                              //   var no_mr = mr;
                                              //   qz.printers.find("Label").then(function(found) {
                                              //       console.log(found);
                                              //   });

                                              //   qz.printers.find("Label").then(function(printer) {
                                              //       // Create a default config for the found printer
                                              //       var config = qz.configs.create(printer);

                                              //       // Raw ZPL
                                              //       var nomorsep = ['^XA',
                                              //           '^CF0,30,20',
                                              //           '^FO130,40^FWN^FD' + '   :' + nama_pasien + ' ( ' + jenis_kelamin + ' - ' + umur_tahun + ' TH)' + '^FS',
                                              //           '^BY2,2,60',
                                              //           '^FO150,70^B3N,N,90,Y^FD' + no_sep + '^FS^',
                                              //           'XZ',
                                              //           '^XA',
                                              //           '^CF0,30,20',
                                              //           '^FO200,40^FWN^FD' + '   :' + nama_pasien + ' ( ' + jenis_kelamin + ' - ' + umur_tahun + ' TH)' + '^FS',
                                              //           '^BY4,2,60',
                                              //           '^FO220,70^B3N,N,90,Y^FD' + no_mr + '^FS^',
                                              //           'XZ'
                                              //       ];
                                              //       return qz.print(config, nomorsep);
                                              //   }).catch(function(e) {
                                              //       console.error(e);
                                              //       toastr["error"]("Printer Tidak Ditemukan");
                                              //   });
                                              //RECEIPT
                                              qz.printers.find("Receipt").then(function(printer) {
                                                  // Create a default config for the found printer
                                                  var config = qz.configs.create(printer);


                                                  // Raw ZPL
                                                  var angka = ceknoantrean;
                                                  var no_sep = ceknosep;
                                                  var today = new Date();
                                                  var month = today.getMonth() + 1;
                                                  var time = today.getDate() + "/" + month + "/" + today.getFullYear() + " " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                                  var data = [
                                                      '\x1B' + '\x40', // init
                                                      '\x1B' + '\x61' + '\x31', // center align
                                                      '\x1B' + '\x21' + '\x30',
                                                      'Bukti Registrasi  \x0A',
                                                      '\x1B' + '\x21' + '\x0A' + '\x1B' + '\x45' + '\x0A', // em mode off
                                                      '\x1B' + '\x4D' + '\x30', //F normal text
                                                      'Pasien Rawat Jalan - ' + time + '\x0A', // text and line break
                                                      '\x0A', // line break
                                                      '\x1B' + '\x61' + '\x30', // left align
                                                      '  No. RM         : ' + nomr + '\x0A',
                                                      '  Nama Pasien    : ' + namapasien + '\x0A',
                                                      '  Tanggal Lahir  : ' + tgllahir + '\x0A',
                                                      '  Umur           : ' + ut + '\x0A',
                                                      '  No. Registrasi : ' + noregistrasi + '\x0A',
                                                      '  Tujuan         : ' + namapoli + '\x0A',
                                                      '  Dokter/Psikolog : ' + namadokter + '\x0A',
                                                      '  Tanggal        : ' + time + '\x0A',
                                                      '\x0A',
                                                      '\x1B' + '\x61' + '\x32', // right align
                                                      '\x1B' + '\x21' + '\x30', // em mode on
                                                      'No. Antrian : ' + noantrean + '\x0A',
                                                      '\x1B' + '\x21' + '\x0A' + '\x1B' + '\x45' + '\x0A', // em mode off
                                                      '\x0A' + '\x0A',
                                                      '\x1B' + '\x61' + '\x31', // center align
                                                      '------- RS Jiwa Prof. HB Saanin Padang -------' + '\x0A',
                                                      '\x0A' + '\x0A',
                                                      '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A' + '\x0A',
                                                      '\x1B' + '\x69', // cut paper (old syntax)
                                                      '\x10' + '\x14' + '\x01' + '\x00' + '\x05', // Generate Pulse to kick-out cash drawer**
                                                  ];
                                                  return qz.print(config, data);
                                              }).catch(function(e) {
                                                  console.log(e);
                                                  //   alert("Printer Tidak Ditemukan");
                                                  Swal.fire({
                                                      icon: 'error',
                                                      title: 'Printer Tidak Ditemukan',
                                                      text: data.metadata.message,
                                                  });
                                              });
                                              qz.printers.find("Receipt").then(function(printer) {
                                                  // Create a default config for the found printer
                                                  var config = qz.configs.create(printer);
                                                  console.log(nosep);
                                                  var no_sep = nosep;

                                                  // Raw ZPL
                                                  // var angka = noantrean;
                                                  var today = new Date();
                                                  var month = today.getMonth() + 1;
                                                  var time = today.getDate() + "/" + month + "/" + today.getFullYear() + " " + today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                                                  var data = [{
                                                      type: 'pixel',
                                                      format: 'pdf',
                                                      flavor: 'file',
                                                      data: '<?= base_url('dashboard/lembarSepPrint/') ?>' + ceknosep
                                                  }];
                                                  return qz.print(config, data);
                                              }).catch(function(e) {
                                                  console.log(e);
                                                  //   alert("Printer Tidak Ditemukan");
                                                  Swal.fire({
                                                      icon: 'error',
                                                      title: 'Printer Tidak Ditemukan',
                                                      text: data.metadata.message,
                                                  });

                                              });
                                              $('#modal-kodebooking-jkn').modal('hide');

                                          }
                                      })
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: 'Data Tidak Ditemukan',
                                          text: data.metadata.message,
                                      });
                                  }
                              }
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
          });
      </script>