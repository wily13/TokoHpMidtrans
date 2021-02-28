<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
		integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

	<title>Data Cart</title>
</head>

<body>

	<div class="container my-4">
		<div class="row">
			<div class="col-12">
				<h2>Test Payment Gateway Midtrans</h2>
				<button type="button" class="btn btn-sm btn-primary mb-2" data-toggle="modal"
					data-target="#exampleModal">Tambah Barang</button>
				<br>
				<?= $this->session->flashdata('message'); ?>

				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Nama Barang</th>
							<th scope="col">Harga</th>
							<th scope="col">Sub Total</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($keranjang) : ?>
						<?php $i = 1;
                            $total   = 0;
                            foreach ($keranjang as $krjg) : ?>

						<?php $total += $krjg['price'] * $krjg['jumlah'];  ?>
						<tr>
							<th scope="row"><?= $i++; ?></th>
							<td><?= $krjg['product']; ?> x <?= $krjg['jumlah']; ?></td>
							<td><?= $krjg['price']; ?></td>
							<td><?= $krjg['price'] * $krjg['jumlah']; ?></td>
						</tr>
						<?php endforeach; ?>
						<tr>
							<td colspan="3">Total Belanja</td>
							<td>Rp. <?= $total; ?></td>
						</tr>
						<?php else : ?>
						<div class="alert alert-danger" role="alert">
							Belum ada data keranjang
						</div>
						<?php endif; ?>
					</tbody>
				</table>
				<?php if ($keranjang) : ?>
				<button class="btn btn-sm btn-success float-right" data-amount="<?= $total; ?>"
					id="tombol-bayar">Bayar</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- Button trigger modal -->

	<form id="payment-form" method="post" action="<?= site_url() ?>/snap_product/finish">
		<input type="hidden" name="result_type" id="result-type" value=""></div>
		<input type="hidden" name="result_data" id="result-data" value=""></div>
	</form>



	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
		data-backdrop="static">
		<div class="modal-dialog">
			<form action="<?= site_url('product/simpan') ?>" method="POST">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Tambah produk</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="pilihproduk">Pilih produk</label>
							<select class="form-control" id="pilihproduk" name="produkid">
								<?php foreach ($semuaproduk as $produk) : ?>
								<option value="<?= $produk['id']; ?>">
									<?= $produk['product']; ?> - Rp. <?= $produk['price']; ?>
								</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="jumlah">Jumlah</label>
							<input type="text" class="form-control" name="jumlah" id="jumlah"
								placeholder="masukan jumlah" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Optional JavaScript; choose one of the two! -->
	<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
		data-client-key="SB-Mid-client-gyhpBKc6ndA2IJbT"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
	<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
	</script>

	<script>
		$('#tombol-bayar').click(function (event) {
			var amount = $(this).data('amount')
			event.preventDefault();
			$(this).attr("disabled", "disabled");

			$.ajax({
				url: '<?= site_url() ?>snap_product/token',
				cache: false,
				data: {
					amount: amount
				},

				success: function (data, url) {
					//location = data;

					console.log('token = ' + data);
					console.log('base = ' + '<?= base_url() ?>');
					console.log('site = ' + '<?= site_url() ?>');

					var resultType = document.getElementById('result-type');
					var resultData = document.getElementById('result-data');

					function changeResult(type, data) {
						$("#result-type").val(type);
						$("#result-data").val(JSON.stringify(data));
						//resultType.innerHTML = type;
						//resultData.innerHTML = JSON.stringify(data);
					}

					snap.pay(data, {

						onSuccess: function (result) {
							changeResult('success', result);
							console.log(result.status_message);
							console.log(result);
							$("#payment-form").submit();
						},
						onPending: function (result) {
							changeResult('pending', result);
							console.log(result.status_message);
							$("#payment-form").submit();
						},
						onError: function (result) {
							changeResult('error', result);
							console.log(result.status_message);
							$("#payment-form").submit();
						}
					});
				}
			});
		});
	</script>

</body>

</html>