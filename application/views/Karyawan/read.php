<?php $this->load->view('Template/header'); ?>
  <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>
                    <i data-feather="user" class="w-6 h-6 inline-block mr-2"></i>Karyawan</h1>                
                <p>Welcome back, here's your business overview</p>
            </div>
            <div class="header-actions">
               <a href="javascript:;" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#add-new-karyawan">
        		Tambah Karyawan Baru
        	</a>
        	<a href="" class="button box flex items-center text-gray-700">
        		<i data-feather="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Export to Excel
        	</a>
            </div>
        </header>
<div class="content mt-5">
	<div class="sukses" data-sukses="<?php echo $this->session->flashdata('sukses');?>"></div>
	
    <div class="intro-y datatable-wrapper box p-5 mt-5">
    	<table class="table table-report table-report--bordered display datatable w-full mt-5">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">NIK KARYAWAN</th>
                    <th class="border-b-2 whitespace-no-wrap">NAMA KARYAWAN</th>
                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">JABATAN</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php
	    			foreach ($karyawan->result_array() as $row) : ?>
	    				
	    				<?php
	    				if ($row['kry_nama'] != 'Qymous Code') :?>
	    					<tr>
		    					<td class="text-center border-b"><?= ++$no?></td>
			                    <td class="text-center border-b"><?= $row['kry_nik']?></td>
			                    <td class="border-b">
			                    	<div class="font-medium whitespace-no-wrap">
			                    		<?= $row['kry_nama']?>
			                    	</div>
			                        <div class="text-gray-600 text-xs whitespace-no-wrap">
			                        	USERNAME : <?= $row['kry_username']?>
			                        </div>
			                    </td>
			                    <td class="border-b"><?= $row['kry_alamat']?></td>
			                    <td class="text-center border-b"><?= $row['kry_tlp']?></td>
			                    <td class="text-center border-b"><?= $row['kry_level']?></td>
			                    <td class="border-b w-5">
			                        <div class="flex sm:justify-center items-center">
			                            <a class="flex items-center mr-3" href="javascript:;" data-toggle="modal" data-target="#edit-karyawan-<?= $row['kry_kode']?>"> 
			                            	<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit 
			                            </a>
			                            <a class="flex items-center text-theme-6 tombol-hapus" href="<?= site_url('Karyawan/delete/'.$row['kry_kode'])?>" data-nama="<?= $row['kry_nama']?>"> 
			                            	<i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete 
			                            </a>
			                        </div>
			                    </td>
			                </tr>
	    				<?php endif; ?>
	    				.
	    			<?php endforeach; ?>    			
    		</tbody>
    	</table>
    </div>
<!-- modal tambah-->
<div class="modal" id="add-new-karyawan">
    <div class="modal__content modal__content--xl p-10">
		<div class="intro-y box px-5 pt-5 mt-5">
			<div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
				<div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-14.jpg">
                        <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
                    </div>
                    <div class="ml-5">
                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">Azzahra Computer</div>
                        <div class="text-gray-600">Tegal</div>
                    </div>
                </div>
                <div class="flex mt-12 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail" class="w-4 h-4 mr-2"></i> azzhra@gmail.com </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="instagram" class="w-4 h-4 mr-2"></i> Azzahra computer </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="twitter" class="w-4 h-4 mr-2"></i> Azzahra computer </div>
                </div>
			</div>
		</div>
		<div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
			<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
            	<a data-toggle="tab" data-target="#profile" href="javascript:;" class="py-4 sm:mr-8 active">Profile</a> 
            	<a data-toggle="tab" data-target="#account" href="javascript:;" class="py-4 sm:mr-8">Account</a>
            </div>
		</div>
		<form method="post" action="<?= site_url('Karyawan/save')?>">
			<div class="tab-content">
				<div class="tab-content__pane active" id="profile">
					<div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
						<div class="col-span-12 sm:col-span-6">
		                    <label>NIK</label>
		                    <?= form_error('nik','<small class="text-danger">','</small>')?>
		                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Masukan nik sesuai KTP" name="nik" value="<?= set_value('nik');?>" required oninvalid="this.setCustomValidity('NIK tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12 sm:col-span-6">
		                    <label>Nama</label>
		                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Nama karyawan" name="nama" required oninvalid="this.setCustomValidity('Nama tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12 sm:col-span-6">
		                    <label>Tempat</label>
		                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Tempat Lahir" name="tempat" required oninvalid="this.setCustomValidity('Tempat lahir tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12 sm:col-span-6">
		                    <label>Tanggal Lahir</label>
		                    <input type="date" class="input w-full border mt-2 flex-1" name="tgl_lahir" required oninvalid="this.setCustomValidity('Tanggal lahir tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12">
		                    <label>Alamat</label>
		                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"></textarea>
		                </div>
		                 <div class="col-span-12 sm:col-span-6">
		                    <label>No Telp</label>
		                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Masukan no tlephone" name="tlp" required oninvalid="this.setCustomValidity('No hp tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12 sm:col-span-6">
		                    <label>Tanggal Masuk</label>
		                    <input type="date" class="input w-full border mt-2 flex-1" name="tgl_masuk" required oninvalid="this.setCustomValidity('Tanggal masuk tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
					</div>
				</div>
				<div class="tab-content__pane" id="account">
					<div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
						<div class="col-span-12">
		                    <label>Jabatan</label>
		                    <select class="input w-full border mt-2 flex-1" name="level" required oninvalid="this.setCustomValidity('Jabatan tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                <option value="">-</option>
		                <option value="Admin">Admin</option>
		                <option value="Kasir">Kasir</option>
		                <option value="Customer Service">Customer Service</option>
		                <option value="Teknisi">Teknisi</option>
		                <option value="HR">HR</option>
		            </select>
		                </div>
		                <div class="col-span-12">
		                    <label>Username</label>
		                    <?= form_error('username','<small class="text-danger">','</small>')?>
		                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Username" name="username" value="<?= set_value('username');?>" required oninvalid="this.setCustomValidity('Username tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
		                <div class="col-span-12">
		                    <label>Password</label>
		                    <input type="password" class="input w-full border mt-2 flex-1" placeholder="Password" name="pswd" required oninvalid="this.setCustomValidity('Password tidak boleh kosong ?')" oninput="setCustomValidity('')">
		                </div>
					</div>
				</div>
			</div>			
			<div class="px-5 py-3 text-right border-t border-gray-200">
	            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
	            <button type="submit" class="button w-20 bg-theme-1 text-white">Simpan</button>
	        </div>
		</form>		
    </div>
</div>
<!-- modal -->
<?php
foreach ($karyawan->result_array() as $row) :?>
	<div class="modal" id="edit-karyawan-<?= $row['kry_kode']?>">
	    <div class="modal__content modal__content--xl p-10">
			<div class="intro-y box px-5 pt-5 mt-5">
				<div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
					<div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
	                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
	                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="<?php echo base_url(); ?>assets/template/beck/dist/images/profile-14.jpg">
	                        <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
	                    </div>
	                    <div class="ml-5">
	                        <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg"><?= $row['kry_nama']?></div>
	                        <div class="text-gray-600"><?= $row['kry_level']?></div>
	                    </div>
	                </div>
	                <div class="flex mt-12 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
	                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail" class="w-4 h-4 mr-2"></i> russellcrowe@left4code.com </div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="instagram" class="w-4 h-4 mr-2"></i> Instagram Russell Crowe </div>
	                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <i data-feather="twitter" class="w-4 h-4 mr-2"></i> Twitter Russell Crowe </div>
	                </div>
				</div>
			</div>
			<div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
				<div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
	            	<a data-toggle="tab" data-target="#edit-profile" href="javascript:;" class="py-4 sm:mr-8 active">Profile</a>
	            </div>
			</div>
			<form method="post" action="<?= site_url('Karyawan/update')?>">
				<div class="tab-content">
					<div class="tab-content__pane active" id="edit-profile">
						<div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
							<div class="col-span-12 sm:col-span-6">
			                    <label>NIK</label>
			                    <input type="text" name="kode" value="<?= $row['kry_kode']?>" hidden>
			                    <?= form_error('nik','<small class="text-danger">','</small>')?>
			                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Masukan nik sesuai KTP" name="nik" value="<?= $row['kry_nik']; ?>" required oninvalid="this.setCustomValidity('NIK tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                <div class="col-span-12 sm:col-span-6">
			                    <label>Nama</label>
			                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Nama karyawan" name="nama" value="<?= $row['kry_nama']; ?>" required oninvalid="this.setCustomValidity('Nama tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                <div class="col-span-12 sm:col-span-6">
			                    <label>Tempat</label>
			                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Tempat Lahir" name="tempat" value="<?= $row['kry_tempat']; ?>"required oninvalid="this.setCustomValidity('Tempat lahir tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                <div class="col-span-12 sm:col-span-6">
			                    <label>Tanggal Lahir</label>
			                    <input type="date" class="input w-full border mt-2 flex-1" name="tgl_lahir" value="<?= $row['kry_tgl_lahir']; ?>" required oninvalid="this.setCustomValidity('Tanggal lahir tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                <div class="col-span-12">
			                    <label>Alamat</label>
			                    <textarea class="input w-full border mt-2 flex-1" name="alamat" required oninvalid="this.setCustomValidity('Alamat tidak boleh kosong ?')" oninput="setCustomValidity('')"><?= $row['kry_alamat']; ?></textarea>
			                </div>
			                 <div class="col-span-12">
			                    <label>No Telp</label>
			                    <input type="text" class="input w-full border mt-2 flex-1" placeholder="Masukan no tlephone" name="tlp" value="<?= $row['kry_tlp']; ?>" required oninvalid="this.setCustomValidity('No hp tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                <div class="col-span-12 sm:col-span-6">
			                    <label>Tanggal Masuk</label>
			                    <input type="date" class="input w-full border mt-2 flex-1" name="tgl_masuk" value="<?= $row['kry_tgl_masuk']; ?>" required oninvalid="this.setCustomValidity('Tanggal masuk tidak boleh kosong ?')" oninput="setCustomValidity('')">
			                </div>
			                 <div class="col-span-12 sm:col-span-6">
			                    <label>Tanggal Keluar</label>
			                    <input type="date" class="input w-full border mt-2 flex-1" name="tgl_keluar" value="<?= $row['kry_tgl_keluar']; ?>">
			                </div>
						</div>
					</div>
				</div>			
				<div class="px-5 py-3 text-right border-t border-gray-200">
		            <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Batal</button>
		            <button type="submit" class="button w-20 bg-theme-1 text-white">Update</button>
		        </div>
			</form>		
	    </div>
	</div>
<?php endforeach;?>

<?php $this->load->view('Template/footer'); ?>