<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ppenjualan_m extends CI_Model
{
    protected $table = 'piutang';
    protected $primary = 'id_piutang';
    
	    public function Bayar($id){
        $user = infoLogin();
        $nominal = $this->input->post('nominal');
        $data = array(
          'TGL_BAYAR'   => date('Y-m-d H:i:s'),
          'NOMINAL'     => $nominal,
          'ID_USER'     => $user['ID_USER'],
          'ID_PIUTANG'  => $id,
        );
        $this->db->insert('detil_piutang', $data);
        $get_bayar = "SELECT SUM(nominal) AS nominal FROM detil_piutang WHERE id_piutang = '$id'";
        $get_jml_piutang = "SELECT jml_piutang FROM piutang WHERE id_piutang = '$id'";
        $bayar = implode($this->db->query($get_bayar)->row_array());
        $jml = implode($this->db->query($get_jml_piutang)->row_array());
        $sisa = $jml - $bayar; 
        $update = array(
          'bayar' => $bayar,
          'sisa'  => $sisa
        );
        $this->db->set($update);
		    $this->db->where($this->primary, $id);
        $this->db->update($this->table);
        
        if ($sisa == 0){
            $status = array(
                'status'  => 'Lunas'
              );
              $this->db->set($status);
              $this->db->where($this->primary, $id);
              $this->db->update($this->table);
        }

        $this->db->select("RIGHT (kas.kode_kas, 7) as kode_kas", false);
        $this->db->order_by("kode_kas", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('kas');
        
        if($query->num_rows() <> 0){
          $data = $query->row();
          $kode = intval($data->kode_kas) + 1;
          
        } else {
          $kode = 1;
        }
        $kodeks = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $kodekas = "KS-".$kodeks;
        $kas = array(
          'ID_USER' 	=> $user['ID_USER'],
          'KODE_KAS'	=> $kodekas,
          'TANGGAL'	  => date('Y-m-d H:i:s'),
          'JENIS'		  => 'Pemasukan',
          'KETERANGAN'=> 'Pembayaran Piutang', 
          'NOMINAL'	  => $nominal,
        );
      
        $this->db->insert('kas', $kas);
    }
}