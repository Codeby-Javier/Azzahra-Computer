$(document).ready(function() {
	const sukses 	= $('.sukses').data('sukses');
	const login 	= $('.login').data('login');

	// Check if this is the first page load after login
	const justLoggedIn = sessionStorage.getItem('just_logged_in');

	if (sukses){
		Swal.fire({
			title: 'DATA',
			text: 'BERHASIL ' + sukses,
			icon: 'success'
		});
	}else if (login && !localStorage.getItem('login_popup_shown')) {
		// Show login success popup only once per browser session
		Swal.fire({
			title: 'ANDA BERHASIL LOGIN',
			text: 'Selamat datang ' + login,
			icon: 'success',
			showClass: {
				popup: 'animated rubberBand'
			}
		});
		// Set flag to prevent showing again
		localStorage.setItem('login_popup_shown', 'true');
	}

	//hapus
	$(document).on('click', '.tombol-hapus', function (e){
		e.preventDefault();
		const href = $(this).prop('href');
		const name = $(this).data('nama');
		Swal.fire({
			title: 'Konfirmasi Hapus Data',
			html: `Apakah Anda yakin ingin menghapus data customer <strong>${name}</strong>?`,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#e74c3c',
			cancelButtonColor: '#95a5a6',
			confirmButtonText: 'Ya, Hapus',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				document.location.href = href;
			}
		});
	});

	//Batal transaksi
	$(document).on('click', '.tombol-batal', function (e){
		e.preventDefault();
		const href = $(this).prop('href');
		const name = $(this).data('nama');
		Swal.fire({
			  title: 'Apa kamu yakin akan membatalkan transaksi ini ?',
			  text: 'Dengan Nama Customer ' + name,
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Iya saya akan batalkan'
			}).then((result) => {
			  if (result.isConfirmed) {
			    document.location.href = href;
			  }
			})
	});

	//Batal Tindakan
	$(document).on('click', '.tombol-batal-tindakan', function (e){
		e.preventDefault();
		const href = $(this).prop('href');
		const name = $(this).data('nama');
		Swal.fire({
			title: 'Konfirmasi Pembatalan Tindakan',
			html: `Apakah Anda yakin ingin membatalkan tindakan <strong>${name}</strong>?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#e74c3c',
			cancelButtonColor: '#95a5a6',
			confirmButtonText: 'Ya, Batalkan',
			cancelButtonText: 'Batal',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				document.location.href = href;
			}
		});
	});

	//Return Pembayaran
	$(document).on('click', '.tombol-return', function (e){
		e.preventDefault();
		const href = $(this).prop('href');
		const name = $(this).data('nama');
		Swal.fire({
			  title: 'Apa kamu yakin akan mengembalikan pembayaran ini ?',
			  text: 'Dengan Nama Customer ' + name,
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Iya saya akan kembalikan'
			}).then((result) => {
			  if (result.isConfirmed) {
			    document.location.href = href;
			  }
			})
	});
});
