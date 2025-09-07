<div class="container-fluid">
  <div class="page-title">
    <div class="row">
      <div class="col-6">
        <h3>Report IKU</h3>
      </div>
      <div class="col-6">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url('home'); ?>"><i data-feather="home"></i></a></li>
          <li class="breadcrumb-item active">Report IKU</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
  <div class="row">
    <!-- Data Lulusan < 6 bulan -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">Data Lulusan dengan masa tunggu < 6 bulan &> 1,2 kali UMR </h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/data_lulusan_6_bln') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Yudisium</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Yudisium</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/data_lulusan_6_bln') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Lulusan 1,2 Kali UMR -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">Data Lulusan yang telah berpenghasilan > 1,2 Kali UMR Sebelum Lulus</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/data_lulusan_1_2_kali') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_1(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Yudisium</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Yudisium</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_1"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/data_lulusan_1_2_kali') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Lulusan Lanjut Pendidikan -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">Data Lulusan yang Melanjutkan Pendidikan ke jenjang yang lebih tinggi</h5>
        </div>
        <form class="form theme-form" method="post"
          action="<?= base_url('admin/report/data_lulusan_lanjut_pendidikan') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_2(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Yudisium</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Yudisium</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_2"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/data_lulusan_lanjut_pendidikan') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Lulusan Wirausaha < 6 bulan -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">Data Lulusan yang Berwirausaha dalam Kurun Waktu < 6 Bulan Setelah Lulus &
              Berpenghasilan 1,2 X UMR</h5>
        </div>
        <form class="form theme-form" method="post"
          action="<?= base_url('admin/report/data_lulusan_wirausaha_6_bulan') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_3(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Yudisium</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Yudisium</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_3"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/data_lulusan_wirausaha_6_bulan') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Lulusan Wirausaha 1,2 UMR -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">Data Lulusan yang Berwirausaha Sebelum Lulus & Berpenghasilan 1,2 X UMR</h5>
        </div>
        <form class="form theme-form" method="post"
          action="<?= base_url('admin/report/data_lulusan_wirausaha_1_2_kali') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_4(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Yudisium</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Yudisium</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_4"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/data_lulusan_wirausaha_1_2_kali') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 2 Kegiatan MBKM -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 2 Kegiatan MBKM</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_2_kegiatan') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_5(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Semester</option>
                  <option value="4">Download Berdasarkan Prodi & Semester</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_5"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_2_kegiatan') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 2 Riwayat Prestasi -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 2 Riwayat Prestasi</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_2_prestasi') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_6(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tingkat Kegiatan</option>
                  <option value="4">Download Berdasarkan Prodi & Tingkat Kegiatan</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_6"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_2_prestasi') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 3 Data Dosen Tetap -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 3 Data Dosen Tetap</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_3_data_dosen') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_7(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tanggal Mulai/Masuk</option>
                  <option value="4">Download Berdasarkan Prodi & Tanggal Mulai</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_7"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_3_data_dosen') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 4 Data Kualifikasi Dosen -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 4 DATA KUALIFIKASI DOSEN</h5>
        </div>
        <form class="form theme-form" method="post"
          action="<?= base_url('admin/report/iku_4_data_kualifikasi_dosen') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_8(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_8"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_4_data_kualifikasi_dosen') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 5 Recognisi Karya Dosen -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 5 Recognisi Karya Dosen</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_5_recognisi') ?>">
          <div class="card-body">
            <br>
            <div class="row">
              <div class="col-md-12">
                <select name="jenis_print" class="form-select" onchange="jenis_print_lulus_9(this.value)">
                  <option value="">- Pilih Jenis Print - </option>
                  <option value="1">Download Keseluruhan</option>
                  <option value="2">Download Berdasarkan Prodi</option>
                  <option value="3">Download Berdasarkan Tahun Penerbitan</option>
                  <option value="4">Download Berdasarkan Prodi & Tahun Penerbitan</option>
                </select>
              </div>
            </div>
            <div class="row" id="container_print_lulusan_9"></div>
          </div>
          <div class="card-footer d-flex justify-content-between" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_5_recognisi') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 6 Kerjasama Mitra -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 6 DATA KERJASAMA DENGAN MITRA</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_6_mitra') ?>">
          <div class="card-footer mt-4" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_6_mitra') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>

    <!-- IKU 7 Data Matakuliah -->
    <div class="col-sm-12 col-md-6">
      <div class="card card-absolute">
        <div class="card-header bg-primary">
          <h5 class="text-white">IKU 7 DATA MATAKULIAH</h5>
        </div>
        <form class="form theme-form" method="post" action="<?= base_url('admin/report/iku_7_matakuliah') ?>">
          <div class="card-footer mt-4" style="padding: 20px;">
            <button class="btn btn-primary" type="submit">Download</button>
            <button class="btn btn-info button-lihat" type="button"
              data-url="<?= base_url('admin/ReportData/iku_7_matakuliah') ?>">Lihat</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Container-fluid Ends-->
</div>

<!-- Modal -->
<div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="lihatModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <div class="modal-title" id="lihatModalLabel">Modal title</div>
      </div>
      <div class="modal-body">
        <!-- Konten modal akan dimuat di sini -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onClick="$(modal).modal('hide')"
          data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
.modal-header {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  width: 100%;
}

.modal-xl {
  max-width: 90%;
  /* Sesuaikan lebar modal sesuai kebutuhan */
}

.modal-body {
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-body table {
  margin: auto;
  width: auto;
  /* supaya tabel tidak full */
}
</style>

<script>
const btnLihat = document.querySelectorAll('.btn-info');
const modal = document.querySelector('#lihatModal');
const modalHeader = document.querySelector('#lihatModalLabel');
const modalBody = document.querySelector('.modal-body');

btnLihat.forEach(btn => {
  btn.addEventListener('click', function() {
    const url = this.getAttribute('data-url');
    const formType = url.split('/').pop();
    const form = this.closest('form');
    const formData = new FormData(form);

    const params = new URLSearchParams(formData).toString();

    const finalUrl = url + (params ? '?' + params : '');

    fetch(finalUrl, {
        method: 'GET'
      })
      .then(response => response.json())
      .then(res => {
        const headerText = `
				<h4 class="text-center">${res.data.report_info.title}</h4>
				<h5 class="text-center">${res.data.report_info.subtitle}</h5>
				`;
        const dataBody = res.data.records;
        modalHeader.innerHTML = headerText;

        let content = 'No content available';
        switch (formType) {
          case 'data_lulusan_6_bln':
            content = data_lulusan_6_bln(dataBody);
            break;
          case 'data_lulusan_1_2_kali':
            content = data_lulusan_1_2_kali(dataBody);
            break;
          case 'data_lulusan_lanjut_pendidikan':
            content = data_lulusan_lanjut_pendidikan(dataBody);
            break;
          case 'data_lulusan_wirausaha_6_bulan':
            content = data_lulusan_wirausaha_6_bulan(dataBody);
            break;
          case 'data_lulusan_wirausaha_1_2_kali':
            content = data_lulusan_wirausaha_1_2_kali(dataBody);
            break;
          case 'iku_2_kegiatan':
            content = iku_2_kegiatan(dataBody);
            break;
          case 'iku_2_prestasi':
            content = iku_2_prestasi(dataBody);
            break;
          case 'iku_3_data_dosen':
            content = iku_3_data_dosen(dataBody);
            break;
          case 'iku_4_data_kualifikasi_dosen':
            content = iku_4_data_kualifikasi_dosen(dataBody);
            break;
          case 'iku_5_recognisi':
            content = iku_5_recognisi(dataBody);
            break;
          case 'iku_6_mitra':
            content = iku_6_mitra(dataBody);
            break;
          case 'iku_7_matakuliah':
            content = iku_7_matakuliah(dataBody);
            break;
          default:
            content = 'Invalid report type';
        }

        modalBody.innerHTML = content;

        $(modal).modal('show');
      })
      .catch(error => console.error('Error:', error));
  });
});

function data_lulusan_6_bln(records) {
  if (!records || records.length === 0) {
    return 'No content available';
  }

  // Membuat header untuk tabel atau daftar
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jenjang Studi</th>
          <th>Prodi</th>
          <th>Tahun Lulus</th>
          <th>Masa Tunggu (Bulan)</th>
          <th>Tempat Kerja</th>
          <th>Penghasilan</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.prodi}</td>
        <td>${item.tahun_lulus}</td>
        <td>${item.masa_tunggu_bulan}</td>
        <td>${item.tempat_kerja}</td>
        <td>${item.penghasilan_formatted}</td>
      </tr>
    `;
  });

  // Menutup tabel
  tabel += `
      </tbody>
    </table>
  `;

  return tabel;
}

function data_lulusan_1_2_kali(records) {
  if (!records || records.length === 0) return 'No content available';

  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jenjang Studi</th>
          <th>Prodi</th>
          <th>Tahun Lulus</th>
          <th>Tempat Kerja</th>
          <th>Penghasilan</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.prodi}</td>
        <td>${item.tahun_lulus}</td>
        <td>${item.tempat_kerja}</td>
        <td>${item.penghasilan_formatted}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function data_lulusan_lanjut_pendidikan(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jenjang Studi</th>
          <th>Prodi</th>
          <th>Tahun Lulus</th>
					<th>Perguruan Tinggi Tujuan</th>
					<th>Jenjang Studi Tujuan</th>
					<th>Program Studi Tujuan</th>
					<th>Tahun Lanjut Studi</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.prodi}</td>
        <td>${item.tahun_lulus}</td>
        <td>${item.perguruan_tinggi_tujuan}</td>
        <td>${item.jenjang_studi_tujuan}</td>
        <td>${item.program_studi_tujuan	}</td>
        <td>${item.tahun_lanjut_studi}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function data_lulusan_wirausaha_6_bulan(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jenjang Studi</th>
          <th>Prodi</th>
          <th>Tahun Lulus</th>
          <th>Jenis Usaha</th>
          <th>Penghasilan</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.prodi}</td>
        <td>${item.tahun_lulus}</td>
        <td>${item.jenis_usaha}</td>
        <td>${item.penghasilan_formatted}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function data_lulusan_wirausaha_1_2_kali(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Jenjang Studi</th>
          <th>Prodi</th>
          <th>Tahun Lulus</th>
          <th>Jenis Usaha</th>
          <th>Penghasilan</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.prodi}</td>
        <td>${item.tahun_lulus}</td>
        <td>${item.jenis_usaha}</td>
        <td>${item.penghasilan_formatted}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_2_kegiatan(records) {
  if (!records || records.length === 0) {
    return '<p class="text-center">No content available</p>';
  }

  let tabel = `
    <div class="table-responsive d-flex justify-content-center">
      <table class="table table-bordered table-striped w-auto">
        <thead class="table-primary text-center">
          <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Jenjang Studi</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
            <th>Semester</th>
            <th>Jenis MBKM</th>
            <th>Jenis Program MBKM</th>
            <th>Kegiatan MBKM</th>
            <th>Lokasi Mitra</th>
            <th>Jumlah SKS</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
            <th>Tipe Data</th>
          </tr>
        </thead>
        <tbody>
  `;

  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nim}</td>
        <td>${item.nama}</td>
        <td>${item.jenjang_studi}</td>
        <td>${item.program_studi}</td>
        <td>${item.angkatan}</td>
        <td>${item.semester}</td>
        <td>${item.jenis_mbkm}</td>
        <td>${item.jenis_program_mbkm}</td>
        <td>${item.kegiatan_mbkm}</td>
        <td>${item.lokasi_mitra}</td>
        <td>${item.jumlah_sks}</td>
        <td>${item.waktu_mulai}</td>
        <td>${item.waktu_selesai}</td>
        <td>${item.tipe_data}</td>
      </tr>
    `;
  });

  tabel += `
        </tbody>
      </table>
    </div>
  `;

  return tabel;
}

function iku_2_prestasi(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>NIM</th>
          <th>Nama</th>
          <th>Prodi</th>
          <th>Nama Kegiatan</th>
          <th>Tingkat</th>
          <th>Prestasi</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nim}</td>
        <td>${item.nama}</td>
        <td>${item.prodi}</td>
        <td>${item.nama_kegiatan}</td>
        <td>${item.tingkat}</td>
        <td>${item.prestasi}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_3_data_dosen(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>NIDN</th>
          <th>Nama</th>
          <th>Prodi</th>
          <th>Tanggal Masuk</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nidn}</td>
        <td>${item.nama}</td>
        <td>${item.prodi}</td>
        <td>${item.tanggal_masuk}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_4_data_kualifikasi_dosen(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>NIDN</th>
          <th>Nama</th>
          <th>Prodi</th>
          <th>Kualifikasi</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nidn}</td>
        <td>${item.nama}</td>
        <td>${item.prodi}</td>
        <td>${item.kualifikasi}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_5_recognisi(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Dosen</th>
          <th>Prodi</th>
          <th>Judul Karya</th>
          <th>Tahun</th>
          <th>Jenis Recognisi</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama_dosen}</td>
        <td>${item.prodi}</td>
        <td>${item.judul_karya}</td>
        <td>${item.tahun}</td>
        <td>${item.jenis_recognisi}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_6_mitra(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Mitra</th>
          <th>Jenis Kerjasama</th>
          <th>Bidang</th>
          <th>Periode</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.nama_mitra}</td>
        <td>${item.jenis_kerjasama}</td>
        <td>${item.bidang}</td>
        <td>${item.periode}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}

function iku_7_matakuliah(records) {
  if (!records || records.length === 0) return 'No content available';
  let tabel = `
    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode MK</th>
          <th>Nama MK</th>
          <th>Prodi</th>
          <th>Jumlah SKS</th>
        </tr>
      </thead>
      <tbody>
  `;
  records.forEach((item) => {
    tabel += `
      <tr>
        <td>${item.no}</td>
        <td>${item.kode_mk}</td>
        <td>${item.nama_mk}</td>
        <td>${item.prodi}</td>
        <td>${item.sks}</td>
      </tr>
    `;
  });
  tabel += `</tbody></table>`;
  return tabel;
}
</script>
