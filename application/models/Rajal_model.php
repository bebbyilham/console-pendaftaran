<?php

class Rajal_model extends CI_Model
{
    // PENJAMIN---------------------------------------------
    public function get_kategory_penjamin()
    {
        $id = array('5', '6');

        $this->db->where_in('id_penjamin', $id);
        $query = $this->db->get('simrsj_master.referensi_penjamin');
        return $query->result();
    }

    // DATATABLE
    var $order_column3 = array(null, 'nama_penjamin', 'nama_perusahaan', 'status_perusahaan', null);

    public function make_query_perusahaan()
    {
        $this->db->select('*');
        $this->db->from('simrsj_master.referensi_perusahaan');
        $this->db->join('simrsj_master.referensi_penjamin', 'referensi_penjamin.id_penjamin = referensi_perusahaan.id_penjamin');

        if (isset($_POST["search"]["value"])) {
            $this->db->like('referensi_perusahaan.nama_perusahaan', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column3[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('referensi_perusahaan.id_perusahaan', 'DESC');
        }
    }

    public function make_datatables_perusahaan()
    {
        $this->make_query_perusahaan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_perusahaan()
    {
        $this->make_query_perusahaan();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_perusahaan()
    {
        $this->db->select("*");
        $this->db->from('simrsj_master.referensi_perusahaan');
        return $this->db->count_all_results();
    }

    public function tambah_perusahaan($data)
    {
        $this->db->insert('simrsj_master.referensi_perusahaan', $data);
    }

    public function edit_perusahaan($id, $data)
    {
        $this->db->where('id_perusahaan', $id);
        $this->db->update('simrsj_master.referensi_perusahaan', $data);
    }

    public function fetch_single_perusahaan($id)
    {
        $this->db->where('id_perusahaan', $id);
        $this->db->join('simrsj_master.referensi_penjamin', 'referensi_penjamin.id_penjamin = referensi_perusahaan.id_penjamin ');
        $query = $this->db->get('simrsj_master.referensi_perusahaan');
        return $query->result();
    }
    // END PENJAMIN---------------------------------------------





    // PENDAFTARAN KUNJUNGAN UMUM----------------------------------
    public function get_nama_perusahaan($id)
    {
        $this->db->where('id_penjamin', $id);
        $query = $this->db->get('simrsj_master.referensi_perusahaan');
        return $query->result();
    }

    public function proses_no_regis_umum($data)
    {
        $this->db->insert('pasien_kunjungan', $data);
    }

    public function tambah_pendaftaran_umum($tanggal, $record, $data)
    {
        $this->db->where('tanggal_registrasi', $tanggal);
        $this->db->where('new_record', $record);
        $this->db->where('jenis_layanan', 2);
        $this->db->update('pasien_kunjungan', $data);
    }

    //Monitoring Kunjungan
    var $order_column_kunjungan = array(null, null, 'pasien_kunjungan.nama_pasien', null, null, 'pasien_kunjungan.status_kunjungan', 'pasien_kunjungan.status_farmasi', null);

    public function make_query_monitoring_kunjungan()
    {
        $this->db->select('
                    pasien.no_mr AS nomr, 
                    pasien.nama_pasien AS namapasien, 
                    pasien.tanggal_lahir AS tgllahir, 
                    pasien.jenis_kelamin AS JK, 
                    pasien_kunjungan.no_registrasi AS noregistrasi, 
                    pasien_kunjungan.tanggal_registrasi AS wakturegistrasi, 
                    pasien_kunjungan.status_kunjungan AS statuskunjungan,
                    pasien_kunjungan.status_farmasi,
                    pasien_kunjungan.status_laboratorium,
                    pasien_kunjungan.status_radiologi,
                    pasien_kunjungan.ut AS ut,
                    pasien_kunjungan.ub AS ub,
                    pasien_kunjungan.uh AS uh,
                    pasien_kunjungan.final_kunjungan,
                    referensi_penjamin.nama_penjamin AS jenispasien,
                    ruangan.nama_ruangan AS namapoli,
                    pegawai.nama_pegawai AS namadokter,
                    pegawai.gelar_depan AS gelardepan,
                    pegawai.gelar_belakang AS gelarbelakang, 
                    bpjs_sep.noSep AS nosep,
                    antrean.nomorantreanpoli
        ');

        $this->db->from('simrsj_aplikasi.pasien_kunjungan');
        $this->db->join('simrsj_master.pasien', 'pasien.id_pasien = pasien_kunjungan.id_pasien', 'LEFT');
        $this->db->join('simrsj_master.referensi_penjamin', 'referensi_penjamin.id_penjamin = pasien_kunjungan.penjamin_pasien', 'LEFT');
        $this->db->join('simrsj_master.ruangan', 'ruangan.id_ruangan = pasien_kunjungan.id_poli', 'LEFT');
        $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = pasien_kunjungan.id_dokter', 'LEFT');
        $this->db->join('simrsj_aplikasi.bpjs_sep', 'bpjs_sep.no_registrasi = pasien_kunjungan.no_registrasi', 'LEFT');
        $this->db->join('simrsj_webservice.antrean', 'antrean.norm = pasien.no_mr', 'LEFT');
        $this->db->where('pasien_kunjungan.jenis_layanan', 2);
        $this->db->where('pasien_kunjungan.tanggal_registrasi', date('Y-m-d'));
        $this->db->where('antrean.tanggalperiksa', date('Y-m-d'));
        $this->db->where('antrean.statusantrean !=', 99);
        $this->db->group_by('pasien_kunjungan.no_registrasi');

        if (isset($_POST["search"]["value"])) {
            $this->db->like('pasien.nama_pasien', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_kunjungan[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pasien_kunjungan.id_kunjungan', 'ASC');
        }
    }
    public function make_datatables_monitoring_kunjungan()
    {
        $this->make_query_monitoring_kunjungan();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_monitoring_kunjungan()
    {
        $this->make_query_monitoring_kunjungan();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_monitoring_kunjungan()
    {
        $this->db->select("*");
        $this->db->from('simrsj_aplikasi.pasien_kunjungan');
        return $this->db->count_all_results();
    }

    public function get_dokter($id)
    {
        $profesi = ['1', '9'];
        $this->db->select('pegawai.id_pegawai, pegawai.gelar_depan, pegawai.nama_pegawai, pegawai.gelar_belakang,pegawai.kode_dpjp');
        $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        $this->db->where('ruangan_user.id_ruangan', $id);
        $this->db->where_in('pegawai.profesi', $profesi);
        $query = $this->db->get('simrsj_master.ruangan_user');
        return $query->result();
    }

    public function get_jadwal_dokter($kodedpjp, $kodepoli)
    {
        $this->db->select('dokter_kode,poli_kdsubspesialis,hari,buka,tutup,kuotajkn,kuotanonjkn,jenisjadwal,kodeantrean');
        // $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        $this->db->where('jadwal_dokter.dokter_kode', $kodedpjp);
        $this->db->where('jadwal_dokter.poli_kdsubspesialis', $kodepoli);
        $this->db->where('jadwal_dokter.hari', date("N"));
        $this->db->where('jadwal_dokter.statusjadwal', 1);
        $query = $this->db->get('simrsj_webservice.jadwal_dokter');
        return $query->result();
    }



    // PENDAFTRAN BPJS
    public function simpan_hasil_sep($data)
    {
        $this->db->insert('bpjs_sep', $data);
    }

    public function proses_no_regis_bpjs($data)
    {
        $this->db->insert('pasien_kunjungan', $data);
    }

    public function tambah_kunjungan($data)
    {
        $this->db->insert('pasien_kunjungan', $data);
    }

    public function tambah_pendaftaran_bpjs($tanggal, $record, $data)
    {
        $this->db->where('tanggal_registrasi', $tanggal);
        $this->db->where('new_record', $record);
        $this->db->where('jenis_layanan', 2);
        $this->db->update('pasien_kunjungan', $data);
    }

    // Edit Pendaftaran Pasien Umum
    public function ubah_pendaftaran_umum($noregistrasi, $idpasien, $data)
    {
        $this->db->where('no_registrasi', $noregistrasi);
        $this->db->where('id_pasien', $idpasien);
        $this->db->update('simrsj_aplikasi.pasien_kunjungan', $data);
    }

    // Edit NIK
    public function ubah_nik($idpasien, $data)
    {
        $this->db->where('id_pasien', $idpasien);
        $this->db->update('simrsj_master.pasien', $data);
    }
}
