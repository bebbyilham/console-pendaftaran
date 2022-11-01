  <!-- Header -->
  <div class="header bg-gradient-info pb-6">
      <div class="container-fluid">
          <div class="header-body">
              <div class="row align-items-center py-4">
                  <div class="col-lg-6 col-7">
                      <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
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
                  <div class="col-xl-3 col-md-6">
                      <div class="card card-stats">
                          <!-- Card body -->
                          <div class="card-body">
                              <div class="row">
                                  <div class="col">
                                      <h5 class="card-title text-uppercase text-muted mb-0">Poli</h5>
                                      <span class="h2 font-weight-bold mb-0">350,897</span>
                                  </div>
                                  <div class="col-auto">
                                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                          <i class="ni ni-active-40"></i>
                                      </div>
                                  </div>
                              </div>
                              <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                              </p>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                      <div class="card card-stats">
                          <!-- Card body -->
                          <div class="card-body">
                              <div class="row">
                                  <div class="col">
                                      <h5 class="card-title text-uppercase text-muted mb-0">Farmasi</h5>
                                      <span class="h2 font-weight-bold mb-0">2,356</span>
                                  </div>
                                  <div class="col-auto">
                                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                          <i class="ni ni-chart-pie-35"></i>
                                      </div>
                                  </div>
                              </div>
                              <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                              </p>
                          </div>
                      </div>
                  </div>

              </div>
          </div>
      </div>
  </div>
  <div class="container-fluid my-5">
      <!-- <h4 class="font-weight m-1"><?= $title; ?></h4> -->
      <div class="row">
          <div class="col-lg-12">
              <?= $this->session->flashdata('message'); ?>
          </div>
      </div>
      <div class="row col-12">
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
                      <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-daftar-jkn">DAFTAR</button>
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
                      <div class="modal fade" id="modal-kodebooking-jkn" tabindex="-1" role="dialog" aria-labelledby="modal-kodebooking-jkn" aria-hidden="true">
                          <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                              <div class="modal-content">
                                  <div class="modal-body p-0">
                                      <div class="card bg-secondary border-0 mb-0">

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
                                                          <input id="kodebookingjkn" class="form-control" placeholder="Kode Booking">
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

              $('#btn_cek_kodebooking').on('click', function() {
                  var kodebooking = $('#kodebookingjkn').val();
                  $.ajax({
                      url: '<?php echo base_url(); ?>dashboard/cekDataKodebooking',
                      method: 'POST',
                      type: 'JSON',
                      data: {
                          kodebooking: kodebooking
                      },
                      // dataType: 'JSON',
                      success: function(data) {
                          console.log(data);
                          //   if (data == '1') {
                          //       alert('Data Pemeriksaan sudah diinput')
                          //   } else {
                          //       $('#pemeriksaan').val('Cari Pemeriksaan');
                          //       $('#pemeriksaan').selectpicker('refresh');
                          //       dataTable2.ajax.reload();
                          // toastr["success"](data);
                          // var id = $('#id_order_labor').val();
                          // $.ajax({
                          //     url: "<?php echo base_url(); ?>laboratoriumrajal/hitungTotalBiaya",
                          //     method: "POST",
                          //     data: {
                          //         id: id
                          //     },
                          //     dataType: 'JSON',
                          //     success: function(data) {
                          //         $('#total_biaya').text(data.tarif_layanan);
                          //     }
                          // });
                          //   }
                      }
                  });
              });
          });
      </script>