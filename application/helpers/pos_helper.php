<?php
function cek_login(){
	
   $ci = get_instance();
    if( !$ci->session->userdata('username') ){
		
      redirect('auth');
    }
}
function cek_user(){
	$ci = get_instance();
	$user = $ci->db->get_where('user', ['username' => $ci->session->userdata('username')])->row_array();
	if($user['TIPE'] != 'Administrator'){
		
		redirect('auth/blocked');
	}
}

function infoLogin(){
	$ci = get_instance();
	return $ci->db->get_where('user', ['USERNAME' => $ci->session->userdata('username')])->row_array();
}

function tgl_indo($tanggal)
	{
		$bulan = array (
			1 => 'Januari',
				 'Februari',
				 'Maret',
				 'April',
				 'Mei',
				 'Juni',
				 'Juli',
				 'Agustus',
				 'September',
				 'Oktober',
				 'November',
				 'Desember'
		);
		$p = explode('-', $tanggal);
		return $p[2] . ' ' . $bulan[ (int)$p[1] ] . ' ' . $p[0];
	}