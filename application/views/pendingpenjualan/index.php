		 <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $title?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <d iv class="x_panel">
                  <div class="x_title">
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				            <?php echo $this->session->flashdata('message'); ?>
                    <table id="pendingjual" width="100%" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Invoice</th>
                          <th>Kasir</th>
                          <th>Customer</th>
						              <th>Diskon</th>
                          <th>Total</th>
                          <th>Qty</th>
                          <th>Waktu</th>
                          <th>Opsi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($pending as $p) {?>
                          <tr>
                            <td><?=$p['invoice']?></td>
                            <td><?=$p['nama_lengkap']?></td>
                            <td><?=$p['nama_cs']?></td>
                            <td><?=$p['diskon']?></td>
                            <td><?=$p['total']?></td>
                            <td><?=$p['qty']?></td>
                            <td><?=$p['tgl']?></td>
                            <td>
                              <a href="<?php echo base_url('ppenjualan/payment/' . encrypt_url($p['id_jual'])) ?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Payment</a>
                              <a href="<?php echo base_url('ppenjualan/cancel_order/' . encrypt_url($p['id_jual'])) ?>" class="btn btn-danger btn-xs"><i class="fa fa-check"></i> Cancel Order</a>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		<?php include 'detilpending.php'?>
		<!-- <script src="<?php echo base_url('assets/Javascript/mod-pending-penjualan.js')?>"></script> -->