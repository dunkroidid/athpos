<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ppenjualan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    cek_login();
  }
  public function index()
  {
    $sql = "SELECT b.id_jual, b.kode_jual, b.invoice, d.nama_lengkap, c.nama_cs, SUM(a.diskon) diskon, SUM(a.subtotal) as total, b.tgl, SUM(a.qty_jual) AS qty FROM detil_penjualan a, penjualan b, customer c, user d
      WHERE b.id_jual = a.id_jual AND c.id_cs = b.id_cs AND d.id_user = b.id_user AND b.is_active=0 group by a.id_jual";

    $json = $this->model->General($sql)->result_array();
    $data = array(
      'title'    => 'Daftar Pending Bayar',
      'user'     => infoLogin(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'content'  => 'pendingpenjualan/index',
      'pending'  => $json,
    );
    $this->load->view('templates/main', $data);
  }

  public function detilPenjualan($id = '')
  {
    $sql = "SELECT a.kode_detil_jual, c.barcode, c.nama_barang, c.harga_jual, a.qty_jual, a.diskon, a.subtotal
              FROM detil_penjualan a, penjualan b, barang c
              WHERE b.id_jual = a.id_jual AND c.id_barang = a.id_barang AND  a.id_jual = '$id'";
    $data = $this->model->General($sql)->result_array();
    echo json_encode($data);
  }


    public function payment($id)
  {
    $id = decrypt_url($id);
    $sql = "SELECT b.id_jual, a.id_detil_jual, d.barcode, d.nama_barang, d.harga_jual, a.qty_jual, a.diskon, 
    a.subtotal, c.nama_cs FROM detil_penjualan a, penjualan b, customer c, barang d
  WHERE b.id_jual = a.id_jual AND c.id_cs = b.id_cs AND d.id_barang = a.id_barang AND b.is_active = 0 AND b.id_jual = '$id'";
  
    $row = $this->model->General($sql)->result_array();
    $data = array(
      'title'    => 'Pembayaran',
      'user'     => infoLogin(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'customer' => $this->db->get('customer')->result_array(),
      'content'  => 'pendingpenjualan/edit',
      'val'      => $this->db->query("select a.*, sum(b.subtotal) as total from penjualan a left join detil_penjualan b on a.id_jual=b.id_jual where a.id_jual ='$id'")->row_array(),
      'detail'  => $row
    );
    $this->load->view('templates/main', $data);
  }

    public function bayar()
  {
    $id = $this->input->post('id_jual');
    $nominal = $this->input->post('nominal');
    $sql="update penjualan set is_active=1, bayar=$nominal where id_jual='$id' ";
    $this->db->query($sql);

    $det=$this->db->where('id_jual',$id);
    $det =$this->db->get('detil_penjualan')->result();
    foreach ($det as $r) {
      $idbarang= $r->ID_BARANG;
      $qty_jual= $r->QTY_JUAL;
      $sql1 = "update barang set stok = stok-$qty_jual where id_barang='$idbarang'";
      $this->db->query($sql1);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><aria-hidden="true">×</span> </button><b>Success!</b> Pembayaran berhasil.</div>');
    redirect('ppenjualan/index/' . encrypt_url($id));
  }

      public function cancel_order($id)
  {
    $id = decrypt_url($id);

    $this->db->where('id_jual', $id)->delete('detil_penjualan');
    $this->db->where('id_jual', $id)->delete('penjualan');
    $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><aria-hidden="true">×</span> </button><b>Success!</b> Penjualan berhasil di cancel.</div>');
    redirect('ppenjualan/index/' . encrypt_url($id));
  }
}
