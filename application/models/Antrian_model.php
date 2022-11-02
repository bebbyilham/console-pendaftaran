<?php

class Antrian_model extends CI_Model
{
    var $order_column = array(null, null, null, null, null, 'kodepoli', 'nomorantrean', null, null, null, null);

    public function make_query_antrian_pendaftaran()
    {
        $kodeantrean = $_POST["poli_antrean"];
        $statuspendaftaran = array('1', '3', '98', '99');
        $checkin = array('0', '1', '2');

        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        // $kodeantrean=substr()

        if ($kodeantrean == "0") {
            $this->db->where_in('statusantrean',  $statuspendaftaran);
            $this->db->where_in('checkin',  $checkin);
            $this->db->where('tanggalperiksa', date('y-m-d'));
        } elseif ($kodeantrean == "1") {
            $kd = array('D-', 'O-', 'P-');
            $this->db->where_in('statusantrean',  $statuspendaftaran);
            $this->db->where_in('checkin',  $checkin);
            $this->db->where_in('SUBSTRING(nomorantrean, 1, 2)', $kd);
            $this->db->where('tanggalperiksa', date('y-m-d'));
        } elseif ($kodeantrean == "2") {
            $kd = array('A-', 'O-', 'N-', 'P-');
            $this->db->where_in('statusantrean',  $statuspendaftaran);
            $this->db->where_in('checkin',  $checkin);
            $this->db->where_in('SUBSTRING(nomorantrean, 1, 2)', $kd);
            $this->db->where('tanggalperiksa', date('y-m-d'));
        } elseif ($kodeantrean == "3") {
            // $kd = array('A', 'O', 'N', 'P');
            $this->db->where_in('statusantrean',  $statuspendaftaran);
            $this->db->where_in('checkin',  $checkin);
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where('nomorantreanpoli !=', NULL);
        }
        if (($_POST["search"]["value"])) {
            $this->db->like('namapasien', $_POST["search"]["value"]);
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where_in('statusantrean',  $statuspendaftaran);
            $this->db->where_in('checkin',  $checkin);
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('created_at', 'ASC');
        }
    }

    public function make_datatables_antrian_pendaftaran()
    {
        $this->make_query_antrian_pendaftaran();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_antrian_pendaftaran()
    {
        $this->make_query_antrian_pendaftaran();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_antrian_pendaftaran()
    {
        $this->db->select("*");
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function sisa_antrian_pendaftaran()
    {
        $statuspendaftaran = array('1', '2');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statuspendaftaran);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function make_query_antrian()
    {
        // $statuspendaftaran = array('1', '2', '3');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        // $this->db->where_in('statusantrean',  $statuspendaftaran);
        $this->db->from('simrsj_webservice.antrean');

        if (($_POST["search"]["value"])) {
            $this->db->like('nomorantrean', $_POST["search"]["value"]);
            $this->db->or_like('kodebooking', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('created_at', 'DESC');
        }
    }

    public function make_datatables_antrian()
    {
        $this->make_query_antrian();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_antrian()
    {
        $this->make_query_antrian();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_antrian()
    {
        $this->db->select("*");
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    //POLI
    public function make_query_antrian_poli()
    {
        $kodeantrean = $_POST["pilih_dokter"];
        if ($kodeantrean == "0") {
            // $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $statuspoli = array('3');
            $this->db->select('*');
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where_in('statusantrean',  $statuspoli);
            $this->db->where('kodepoli !=', 'IGD');
            // $this->db->where('statusantrean <',  99);
            $this->db->from('simrsj_webservice.antrean');
        } else {
            $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $statuspoli = array('3');
            $this->db->select('*');
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where_in('statusantrean',  $statuspoli);
            $this->db->where('kodepoli !=', 'IGD');
            $this->db->where('checkin', 2);
            // $this->db->where('statusantrean <',  99);
            $this->db->from('simrsj_webservice.antrean');
        }

        if (($_POST["search"]["value"])) {
            $this->db->like('namapasien', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'ASC');
        }
    }


    public function make_datatables_antrian_poli()
    {
        $this->make_query_antrian_poli();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_antrian_poli()
    {
        $this->make_query_antrian_poli();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_antrian_poli()
    {
        $this->db->select("*");
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function sisa_antrian_poli()
    {
        $statuspoli = array('3', '4');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statuspoli);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    //POLI LAYAN
    public function make_query_antrian_poli2()
    {
        $kodeantrean = $_POST["pilih_dokter"];
        if ($kodeantrean == "0") {
            # code...
            // $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $statuspoli = array('4', '99');
            $this->db->select('*');
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where_in('statusantrean',  $statuspoli);
            $this->db->where('kodepoli !=', 'IGD');
            // $this->db->where('statusantrean <',  99);
            $this->db->from('simrsj_webservice.antrean');
        } else {
            $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $statuspoli = array('4', '99');
            $this->db->select('*');
            $this->db->where('tanggalperiksa', date('y-m-d'));
            $this->db->where_in('statusantrean',  $statuspoli);
            $this->db->where('kodepoli !=', 'IGD');
            // $this->db->where('statusantrean <',  99);
            $this->db->from('simrsj_webservice.antrean');
        }


        if (($_POST["search"]["value"])) {
            $this->db->like('namapasien', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'ASC');
        }
    }


    public function make_datatables_antrian_poli2()
    {
        $this->make_query_antrian_poli2();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_antrian_poli2()
    {
        $this->make_query_antrian_poli2();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_antrian_poli2()
    {
        $this->db->select("*");
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function sisa_antrian_poli2()
    {
        $statuspoli = array('4', '5');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statuspoli);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }


    //farmasi
    var $order_column_farmasi = array(null, null, null, null, null, null, 'nomorantreanpoli', 'nomorantreanfarmasi', null, null, null);
    public function make_query_antrian_farmasi()
    {
        $statuspoli = array('5', '99');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statuspoli);
        // $this->db->where('statusantrean <',  99);
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_master.pasien', 'pasien.no_mr = antrean.norm', 'LEFT');

        if (($_POST["search"]["value"])) {
            $this->db->like('namapasien', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_farmasi[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'ASC');
        }
    }


    public function make_datatables_antrian_farmasi()
    {
        $this->make_query_antrian_farmasi();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    // public function get_filtered_data_antrian_farmasi()
    // {
    //     $this->make_query_antrian_farmasi();
    //     $query = $this->db->get();

    //     return $query->num_rows();
    // }

    // public function get_all_data_antrian_farmasi()
    // {
    //     $this->db->select("*");
    //     $this->db->from('simrsj_webservice.antrean');
    //     return $this->db->count_all_results();
    // }

    public function sisa_antrian_farmasi()
    {
        $statusfarmasi = array('5', '6');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statusfarmasi);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function make_query_antrian_farmasi2()
    {
        $statuspoli = array('6', '99');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statuspoli);
        // $this->db->where('statusantrean <',  99);
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_master.pasien', 'pasien.no_mr = antrean.norm', 'LEFT');

        if (($_POST["search"]["value"])) {
            $this->db->like('namapasien', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column_farmasi[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('nomorantrean', 'ASC');
        }
    }


    public function make_datatables_antrian_farmasi2()
    {
        $this->make_query_antrian_farmasi2();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_data_antrian_farmasi2()
    {
        $this->make_query_antrian_farmasi2();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_data_antrian_farmasi2()
    {
        $this->db->select("*");
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function sisa_antrian_farmasi2()
    {
        $statusfarmasi = array('6', '7');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statusfarmasi);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    public function sisa_antrian_batal()
    {
        $statusfarmasi = array('99');
        $this->db->select('*');
        $this->db->where('tanggalperiksa', date('y-m-d'));
        $this->db->where_in('statusantrean',  $statusfarmasi);
        $this->db->from('simrsj_webservice.antrean');
        return $this->db->count_all_results();
    }

    // data dokter
    public function get_dokter($id)
    {
        $this->db->join('simrsj_webservice.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        $this->db->where('ruangan_user.id_ruangan', $id);
        $query = $this->db->get('simrsj_webservice.ruangan_user');
        return $query->result();
    }

    //single antrian
    public function fetch_single_antrian($id)
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function fetch_single_antrian_farmasi($id)
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    //status antrian
    // public function fetch_status_antrian($id)
    // {
    //     $this->db->select('*');
    //     $this->db->from('simrsj_webservice.status_antrean');
    //     $query = $this->db->get();
    //     return $query->result();
    // }
    public function fetch_status_antrian()
    {
        $query = $this->db->get('simrsj_webservice.status_antrean');
        return $query->result();
    }

    public function ubah_status_antrian($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('simrsj_webservice.antrean', $data);
    }

    public function ubah_antrian_checkin($id, $data)
    {
        $this->db->where('kodebooking', $id);
        $this->db->update('simrsj_webservice.antrean', $data);
    }

    public function ubah_antrian_pasienbaru($id, $data)
    {
        $this->db->where('kodebooking', $id);
        $this->db->update('simrsj_webservice.antrean', $data);
    }

    public function antrian_pendaftaran_loket1()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 2);
        $this->db->where('simrsj_webservice.antrean.keterangan', "1");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.updated_at', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_pendaftaran_loket2()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 2);
        $this->db->where('simrsj_webservice.antrean.keterangan', "2");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.updated_at', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_pendaftaran_loket3()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 2);
        $this->db->where('simrsj_webservice.antrean.keterangan', "3");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.updated_at', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_pendaftaran_loket4()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 2);
        $this->db->where('simrsj_webservice.antrean.keterangan', "4");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.updated_at', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    //POLI
    public function antrian_poli_jiwa_dewasa($kodeantrean)
    {
        // $kodeantrean = $_POST["pilih_dokter1"];
        if ($kodeantrean == "0") {
            # code...
            // $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $this->db->select('*');
            $this->db->from('simrsj_webservice.antrean');
            $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
            $this->db->join('simrsj_webservice.jadwal_dokter', 'jadwal_dokter.dokter_kode = antrean.kodedokter');
            $this->db->where('simrsj_webservice.antrean.statusantrean', 5);
            $this->db->where('simrsj_webservice.antrean.kodepoli', "JIW");
            $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
            $this->db->limit(1);
            $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
            $query = $this->db->get();
            return $query->result();
        } else {
            $this->db->select('*');
            $this->db->from('simrsj_webservice.antrean');
            $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
            $this->db->join('simrsj_webservice.jadwal_dokter', 'jadwal_dokter.dokter_kode = antrean.kodedokter');
            $this->db->where('simrsj_webservice.antrean.statusantrean', 5);
            $this->db->where('simrsj_webservice.antrean.kodepoli', "JIW");
            $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
            $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
            $this->db->limit(1);
            $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function antrian_poli_jiwa_anak()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 4);
        $this->db->where('simrsj_webservice.antrean.kodepoli', "ANA");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_poli_penyakit_dalam()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 4);
        $this->db->where('simrsj_webservice.antrean.kodepoli', "INT");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_farmasi_loket1()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 7);
        // $this->db->where('simrsj_webservice.antrean.keterangan', "FARMASI-1");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->like('nomorantreanfarmasi', 'FB-', 'after');
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_farmasi_loket2()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 7);
        // $this->db->where('simrsj_webservice.antrean.keterangan', "FARMASI-2");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->like('nomorantreanfarmasi', 'FU-', 'after');
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_farmasi_loket3()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 7);
        // $this->db->where('simrsj_webservice.antrean.keterangan', "FARMASI-3");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->like('nomorantreanfarmasi', 'FP-', 'after');
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function antrian_farmasi_loket4()
    {
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->where('simrsj_webservice.antrean.statusantrean', 7);
        $this->db->where('simrsj_webservice.antrean.keterangan', "FARMASI-4");
        $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function tambah_antrian($data)
    {
        $this->db->insert('simrsj_webservice.antrean', $data);
    }

    public function get_jadwal_dokter()
    {
        $this->db->select('dokter_kode,dokter_nama,poli_kdsubspesialis,hari,buka,tutup,kuotajkn,kuotanonjkn,jenisjadwal,kodeantrean');
        // $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        // $this->db->where('jadwal_dokter.dokter_kode', $kodedpjp);
        // $this->db->where('jadwal_dokter.poli_kdsubspesialis', $kodepoli);
        $this->db->where('jadwal_dokter.hari', date("N"));
        $query = $this->db->get('simrsj_webservice.jadwal_dokter');
        return $query->result();

        // if ($kodedpjp != 0) {
        //     $this->db->select('dokter_kode,poli_kdsubspesialis,hari,buka,tutup,kuotajkn,kuotanonjkn,jenisjadwal,kodeantrean');
        //     // $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        //     // $this->db->where('jadwal_dokter.dokter_kode', $kodedpjp);
        //     // $this->db->where('jadwal_dokter.poli_kdsubspesialis', $kodepoli);
        //     $this->db->where('jadwal_dokter.hari', date("N"));
        //     $query = $this->db->get('simrsj_webservice.jadwal_dokter');
        //     return $query->result();
        // } else {
        //     $this->db->select('dokter_kode,poli_kdsubspesialis,hari,buka,tutup,kuotajkn,kuotanonjkn,jenisjadwal,kodeantrean');
        //     // $this->db->join('simrsj_master.pegawai', 'pegawai.id_pegawai = ruangan_user.id_pegawai', 'LEFT');
        //     // $this->db->where('jadwal_dokter.dokter_kode', $kodedpjp);
        //     // $this->db->where('jadwal_dokter.poli_kdsubspesialis', $kodepoli);
        //     $this->db->where('jadwal_dokter.hari', date("N"));
        //     $query = $this->db->get('simrsj_webservice.jadwal_dokter');
        //     return $query->result();
        // }
    }

    public function get_nama_video()
    {
        $this->db->select('*');
        $this->db->from('simrsj_aplikasi.video_display');
        $this->db->where('video_display.jenis_video_display', $_POST['jenis_video']);
        $this->db->limit(1);
        // $this->db->order_by('video_display.jenis_video', "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    public function simpan_video($id, $data)
    {
        $this->db->where('jenis_video_display', $id);
        $this->db->update('simrsj_aplikasi.video_display', $data);
    }

    // TABEL LIST ORDER BARANG
    // var $order_column = array(null, 'nama_obat', null, null, 'kategori_barang', null, null);

    public function make_query_video_display()
    {

        $this->db->select('
                            *
                        ');
        $this->db->from('video_display');

        if (isset($_POST["search"]["value"])) {
            $this->db->like('video_display.jenis_video_display', $_POST["search"]["value"]);
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('video_display.jenis_video_display', 'ASC');
        }
    }

    public function make_datatables_video_display()
    {
        $this->make_query_video_display();

        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_filtered_video_display()
    {
        $this->make_query_video_display();
        $query = $this->db->get();

        return $query->num_rows();
    }

    public function get_all_video_display()
    {
        $this->db->select("*");
        $this->db->from('video_display');
        return $this->db->count_all_results();
    }

    public function create_task_antrian($data)
    {
        $this->db->insert('simrsj_webservice.task_antrean', $data);
    }

    public function update_task_antrian($kodebooking, $data)
    {
        $this->db->where('kode_booking', $kodebooking);
        $this->db->update('simrsj_webservice.task_antrean', $data);
    }

    //single antrian
    public function fetch_single_antrian_poli($id)
    {
        // $this->db->select('*');
        // $this->db->from('simrsj_webservice.antrean');
        // $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        // $this->db->where('simrsj_webservice.antrean.id', $id);
        $this->db->select('*');
        $this->db->from('simrsj_webservice.antrean');
        $this->db->join('simrsj_webservice.status_antrean', 'status_antrean.id_status_antrean = antrean.statusantrean');
        $this->db->join('simrsj_webservice.jadwal_dokter', 'jadwal_dokter.dokter_kode = antrean.kodedokter');
        $this->db->where('simrsj_webservice.antrean.id', $id);
        // $this->db->where('simrsj_webservice.antrean.kodepoli', "JIW");
        // $this->db->where('simrsj_webservice.antrean.tanggalperiksa', date('y-m-d'));
        // $this->db->like('nomorantreanpoli', $kodeantrean . '-', 'after');
        $this->db->limit(1);
        $this->db->order_by('simrsj_webservice.antrean.id', "DESC");
        $query = $this->db->get();
        return $query->result();
    }
}
