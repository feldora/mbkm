<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
use application\libraries\JwtEdDSA;

class Report extends RestController {
    
    // Public variables untuk mengganti hardcoded values
    public $umr = 2303711;
    public $umr_multiplier = 1.2;
    public $valid_jenis_print = ['1', '2', '3', '4'];
    public $valid_jenis_laporan = ['1', '2', '3', '4'];
    public $fakultas_name = 'FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO';
    public $upload_path_prestasi = './uploads/prestasi/';
    public $base_url_prestasi = 'uploads/prestasi/';
    
    function __construct() {
        parent::__construct();
        $this->load->library('JwtEdDSA', null, 'JwtEdDSA');
        $this->load->model("users_model");
        $this->load->model("report_model");
        date_default_timezone_set("Asia/Makassar");
    }

    /**
     * Get data lulusan dengan masa tunggu < 6 bulan dan gaji > 1,2 kali UMR
     * Method: GET
     * Parameters: jenis_print, kd_prodi (optional), date_from (optional), date_to (optional)
     */
    public function data_lulusan_6_bln_get() {
        try {
            $jenis_print = $this->get('jenis_print');
            $kd_prodi = $this->get('kd_prodi');
            $date_from = $this->get('date_from');
            $date_to = $this->get('date_to');

            // Validasi parameter wajib
            if (!$jenis_print || !in_array($jenis_print, $this->valid_jenis_print)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter jenis_print wajib diisi dengan nilai 1-4',
                    'data' => null
                ], 400);
                return;
            }

            // Validasi parameter untuk jenis_print tertentu
            if (in_array($jenis_print, ['2', '4']) && !$kd_prodi) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter kd_prodi wajib untuk jenis_print 2 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['3', '4']) && (!$date_from || !$date_to)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter date_from dan date_to wajib untuk jenis_print 3 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            $gaji_1_2_kali = $this->umr * $this->umr_multiplier;
            $result = [];
            $report_title = '';
            $report_subtitle = '';

            // Tentukan data berdasarkan jenis_print
            switch ($jenis_print) {
                case '1':
                    $list_data = $this->report_model->get_pendidikan_yudisium()->result();
                    $report_title = 'DATA LULUSAN DENGAN MASA TUNGGU < 6 BULAN DAN GAJI > 1,2 KALI UMR';
                    $report_subtitle = $this->fakultas_name;
                    break;
                
                case '2':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response([
                            'status' => false,
                            'message' => 'Program studi tidak ditemukan',
                            'data' => null
                        ], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi($kd_prodi)->result();
                    $report_title = 'DATA LULUSAN DENGAN MASA TUNGGU < 6 BULAN DAN GAJI > 1,2 KALI UMR';
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
                
                case '3':
                    $list_data = $this->report_model->get_pendidikan_yudisium_tanggal($date_from, $date_to)->result();
                    $report_title = 'DATA LULUSAN DENGAN MASA TUNGGU < 6 BULAN DAN GAJI > 1,2 KALI UMR';
                    $report_subtitle = $this->fakultas_name;
                    break;
                
                case '4':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response([
                            'status' => false,
                            'message' => 'Program studi tidak ditemukan',
                            'data' => null
                        ], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi_tanggal($kd_prodi, $date_from, $date_to)->result();
                    $report_title = 'DATA LULUSAN DENGAN MASA TUNGGU < 6 BULAN DAN GAJI > 1,2 KALI UMR';
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
            }

            $number = 1;
            foreach ($list_data as $row) {
                $list_lulusan = $this->report_model->get_lulusan_pekerjaan($row->id_mhsw, $row->tanggal_yudisium, $gaji_1_2_kali)->result();
                foreach ($list_lulusan as $row_1) {
                    $result[] = [
                        'no' => $number,
                        'nama' => $row_1->nama,
                        'jenjang_studi' => $row->jenjang,
                        'prodi' => $row->nama_prodi,
                        'tahun_lulus' => $row->tahun_lulus,
                        'masa_tunggu_bulan' => $row_1->masa_tunggu,
                        'tempat_kerja' => $row_1->nama_perusahaan,
                        'penghasilan' => $row_1->gaji,
                        'penghasilan_formatted' => "Rp " . number_format($row_1->gaji, 0, ',', '.')
                    ];
                    $number++;
                }
            }

            $this->response([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => [
                    'report_info' => [
                        'title' => $report_title,
                        'subtitle' => $report_subtitle,
                        'umr' => $this->umr,
                        'gaji_minimum' => $gaji_1_2_kali,
                        'total_records' => count($result),
                        'generated_at' => date('Y-m-d H:i:s')
                    ],
                    'records' => $result
                ]
            ], 200);

        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get data lulusan yang telah berpenghasilan > 1,2 kali UMR sebelum lulus
     * Method: GET
     */
    public function data_lulusan_1_2_kali_get() {
        try {
            $jenis_print = $this->get('jenis_print');
            $kd_prodi = $this->get('kd_prodi');
            $date_from = $this->get('date_from');
            $date_to = $this->get('date_to');

            if (!$jenis_print || !in_array($jenis_print, $this->valid_jenis_print)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter jenis_print wajib diisi dengan nilai 1-4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['2', '4']) && !$kd_prodi) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter kd_prodi wajib untuk jenis_print 2 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['3', '4']) && (!$date_from || !$date_to)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter date_from dan date_to wajib untuk jenis_print 3 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            $gaji_1_2_kali = $this->umr * $this->umr_multiplier;
            $result = [];
            $report_title = 'DATA LULUSAN YANG TELAH BERPENGHASILAN > 1,2 KALI UMR SEBELUM LULUS';
            $report_subtitle = $this->fakultas_name;

            switch ($jenis_print) {
                case '1':
                    $list_data = $this->report_model->get_pendidikan_yudisium()->result();
                    break;
                case '2':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi($kd_prodi)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
                case '3':
                    $list_data = $this->report_model->get_pendidikan_yudisium_tanggal($date_from, $date_to)->result();
                    break;
                case '4':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi_tanggal($kd_prodi, $date_from, $date_to)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
            }

            $number = 1;
            foreach ($list_data as $row) {
                $list_lulusan = $this->report_model->get_lulusan_sebelum_kerja($row->id_mhsw, $row->tanggal_yudisium, $gaji_1_2_kali)->result();
                foreach ($list_lulusan as $row_1) {
                    $result[] = [
                        'no' => $number,
                        'nama' => $row_1->nama,
                        'jenjang_studi' => $row->jenjang,
                        'prodi' => $row->nama_prodi,
                        'tahun_lulus' => $row->tahun_lulus,
                        'jenis_pekerjaan' => $row_1->bergerak_bidang,
                        'tempat_kerja' => $row_1->nama_perusahaan,
                        'penghasilan' => $row_1->gaji,
                        'penghasilan_formatted' => "Rp " . number_format($row_1->gaji, 0, ',', '.')
                    ];
                    $number++;
                }
            }

            $this->response([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => [
                    'report_info' => [
                        'title' => $report_title,
                        'subtitle' => $report_subtitle,
                        'umr' => $this->umr,
                        'gaji_minimum' => $gaji_1_2_kali,
                        'total_records' => count($result),
                        'generated_at' => date('Y-m-d H:i:s')
                    ],
                    'records' => $result
                ]
            ], 200);

        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get data lulusan yang melanjutkan pendidikan ke jenjang yang lebih tinggi
     * Method: GET
     */
    public function data_lulusan_lanjut_pendidikan_get() {
        try {
            $jenis_print = $this->get('jenis_print');
            $kd_prodi = $this->get('kd_prodi');
            $date_from = $this->get('date_from');
            $date_to = $this->get('date_to');

            if (!$jenis_print || !in_array($jenis_print, $this->valid_jenis_print)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter jenis_print wajib diisi dengan nilai 1-4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['2', '4']) && !$kd_prodi) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter kd_prodi wajib untuk jenis_print 2 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['3', '4']) && (!$date_from || !$date_to)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter date_from dan date_to wajib untuk jenis_print 3 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            $result = [];
            $report_title = 'DATA LULUSAN YANG MELANJUTKAN PENDIDIKAN KE JENJANG YANG LEBIH TINGGI';
            $report_subtitle = $this->fakultas_name;

            switch ($jenis_print) {
                case '1':
                    $list_data = $this->report_model->get_lulusan_pendidikan()->result();
                    break;
                case '2':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_lulusan_pendidikan_prodi($kd_prodi)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
                case '3':
                    $list_data = $this->report_model->get_lulusan_pendidikan_tanggal($date_from, $date_to)->result();
                    break;
                case '4':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_lulusan_pendidikan_prodi_tanggal($kd_prodi, $date_from, $date_to)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
            }

            $number = 1;
            foreach ($list_data as $row) {
                if ($this->report_model->get_lulusan_after($row->jenjang, $row->id_mhsw)->num_rows() > 0) {
                    $data_pendidikan_after = $this->report_model->get_lulusan_after($row->jenjang, $row->id_mhsw)->row();
                    $result[] = [
                        'no' => $number,
                        'nama' => $row->nama,
                        'jenjang_studi' => $row->jenjang,
                        'prodi' => $row->nama_prodi,
                        'tahun_lulus' => $row->tahun_lulus,
                        'perguruan_tinggi_tujuan' => $data_pendidikan_after->nama_sekolah,
                        'jenjang_studi_tujuan' => $data_pendidikan_after->jenjang,
                        'program_studi_tujuan' => $data_pendidikan_after->prodi,
                        'tahun_lanjut_studi' => $data_pendidikan_after->tahun_masuk
                    ];
                    $number++;
                }
            }

            $this->response([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => [
                    'report_info' => [
                        'title' => $report_title,
                        'subtitle' => $report_subtitle,
                        'total_records' => count($result),
                        'generated_at' => date('Y-m-d H:i:s')
                    ],
                    'records' => $result
                ]
            ], 200);

        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get data lulusan yang berwirausaha dalam kurun waktu < 6 bulan setelah lulus
     * Method: GET
     */
    public function data_lulusan_wirausaha_6_bulan_get() {
        try {
            $jenis_print = $this->get('jenis_print');
            $kd_prodi = $this->get('kd_prodi');
            $date_from = $this->get('date_from');
            $date_to = $this->get('date_to');

            if (!$jenis_print || !in_array($jenis_print, $this->valid_jenis_print)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter jenis_print wajib diisi dengan nilai 1-4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['2', '4']) && !$kd_prodi) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter kd_prodi wajib untuk jenis_print 2 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            if (in_array($jenis_print, ['3', '4']) && (!$date_from || !$date_to)) {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter date_from dan date_to wajib untuk jenis_print 3 dan 4',
                    'data' => null
                ], 400);
                return;
            }

            $gaji_1_2_kali = $this->umr * $this->umr_multiplier;
            $result = [];
            $report_title = 'DATA LULUSAN YANG BERWIRAUSAHA DALAM KURUN WAKTU < 6 BULAN SETELAH LULUS & BERPENGHASILAN 1,2 X UMR';
            $report_subtitle = $this->fakultas_name;

            switch ($jenis_print) {
                case '1':
                    $list_data = $this->report_model->get_pendidikan_yudisium()->result();
                    break;
                case '2':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi($kd_prodi)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
                case '3':
                    $list_data = $this->report_model->get_pendidikan_yudisium_tanggal($date_from, $date_to)->result();
                    break;
                case '4':
                    $prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
                    if (!$prodi) {
                        $this->response(['status' => false, 'message' => 'Program studi tidak ditemukan'], 404);
                        return;
                    }
                    $list_data = $this->report_model->get_pendidikan_yudisium_prodi_tanggal($kd_prodi, $date_from, $date_to)->result();
                    $report_subtitle = 'PROGRAM STUDI ' . $prodi->nama_prodi;
                    break;
            }

            $number = 1;
            foreach ($list_data as $row) {
                $list_lulusan = $this->report_model->get_lulusan_wirausaha($row->id_mhsw, $row->tanggal_yudisium, $gaji_1_2_kali)->result();
                foreach ($list_lulusan as $row_1) {
                    $result[] = [
                        'no' => $number,
                        'nama' => $row_1->nama,
                        'program_studi' => $row->nama_prodi,
                        'jenjang_studi' => $row->jenjang,
                        'tahun_lulus' => $row->tahun_lulus,
                        'kurun_waktu_memulai_usaha_bulan' => $row_1->masa_tunggu,
                        'nama_usaha' => $row_1->nama_usaha,
                        'bidang_usaha' => $row_1->jenis_usaha,
                        'alamat_tempat_usaha' => $row_1->alamat_usaha,
                        'penghasilan' => $row_1->rata_rata_omset,
                        'penghasilan_formatted' => "Rp " . number_format($row_1->rata_rata_omset, 0, ',', '.')
                    ];
                    $number++;
                }
            }

            $this->response([
                'status' => true,
                'message' => 'Data berhasil diambil',
                'data' => [
                    'report_info' => [
                        'title' => $report_title,
                        'subtitle' => $report_subtitle,
                        'umr' => $this->umr,
                        'gaji_minimum' => $gaji_1_2_kali,
                        'total_records' => count($result),
                        'generated_at' => date('Y-m-d H:i:s')
                    ],
                    'records' => $result
                ]
            ], 200);

        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

	/**
	 * API untuk mendapatkan data lulusan wirausaha dengan penghasilan > 1.2 x UMR
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional), date_from (optional), date_to (optional)
	 */
	public function data_lulusan_wirausaha_1_2_kali_get()
	{
		try {
			// Validasi input
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');
			$date_from = $this->get('date_from');
			$date_to = $this->get('date_to');

			if (!$jenis_laporan || !in_array($jenis_laporan, ['1', '2', '3', '4'])) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-4)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			// Konstanta UMR dan perhitungan
			$umr = 2303711;
			$gaji_1_2_kali = $umr * 1.2; // 2,764,453.2

			$result_data = [];
			$header_info = [
				'title' => 'DATA LULUSAN YANG BERWIRAUSAHA SEBELUM LULUS & BERPENGHASILAN > 1,2 X UMR',
				'subtitle' => 'FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO',
				'umr_amount' => $umr,
				'threshold_amount' => $gaji_1_2_kali,
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Logika berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					// Semua data
					$list_data = $this->report_model->get_pendidikan_yudisium()->result();
					$header_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					// Berdasarkan program studi
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
					if (!$prodi) {
						$this->response([
							'status' => false,
							'message' => 'Program studi tidak ditemukan',
							'data' => null
						], RestController::HTTP_NOT_FOUND);
						return;
					}

					$list_data = $this->report_model->get_pendidikan_yudisium_prodi($kd_prodi)->result();
					$header_info['subtitle'] = 'PROGRAM STUDI ' . $prodi->nama_prodi . ' - FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO';
					$header_info['filter_type'] = 'Program Studi';
					$header_info['filter_value'] = $prodi->nama_prodi;
					break;

				case '3':
					// Berdasarkan rentang tanggal
					if (!$date_from || !$date_to) {
						$this->response([
							'status' => false,
							'message' => 'Parameter date_from dan date_to wajib diisi untuk jenis laporan 3',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$list_data = $this->report_model->get_pendidikan_yudisium_tanggal($date_from, $date_to)->result();
					$header_info['filter_type'] = 'Rentang Tanggal';
					$header_info['filter_value'] = $date_from . ' s/d ' . $date_to;
					break;

				case '4':
					// Berdasarkan program studi dan rentang tanggal
					if (!$kd_prodi || !$date_from || !$date_to) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi, date_from, dan date_to wajib diisi untuk jenis laporan 4',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$prodi = $this->report_model->get_prodi_one($kd_prodi)->row();
					if (!$prodi) {
						$this->response([
							'status' => false,
							'message' => 'Program studi tidak ditemukan',
							'data' => null
						], RestController::HTTP_NOT_FOUND);
						return;
					}

					$list_data = $this->report_model->get_pendidikan_yudisium_prodi_tanggal($kd_prodi, $date_from, $date_to)->result();
					$header_info['subtitle'] = 'PROGRAM STUDI ' . $prodi->nama_prodi . ' - FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO';
					$header_info['filter_type'] = 'Program Studi & Rentang Tanggal';
					$header_info['filter_value'] = $prodi->nama_prodi . ' (' . $date_from . ' s/d ' . $date_to . ')';
					break;
			}

			// Proses data lulusan
			$no = 1;
			foreach ($list_data as $row) {
				$list_lulusan = $this->report_model->get_lulusan_sebelum_wirausaha($row->id_mhsw, $row->tanggal_yudisium, $gaji_1_2_kali)->result();

				foreach ($list_lulusan as $lulusan) {
					$result_data[] = [
						'no' => $no,
						'nama' => $lulusan->nama,
						'program_studi' => $row->nama_prodi,
						'jenjang_studi' => $row->jenjang,
						'tahun_lulus' => $row->tahun_lulus,
						'nama_usaha' => $lulusan->nama_usaha,
						'bidang_usaha' => $lulusan->jenis_usaha,
						'alamat_usaha' => $lulusan->alamat_usaha,
						'penghasilan' => [
							'raw' => (float)$lulusan->rata_rata_omset,
							'formatted' => 'Rp ' . number_format($lulusan->rata_rata_omset, 0, ',', '.')
						],
						'id_mahasiswa' => $row->id_mhsw,
						'tanggal_yudisium' => $row->tanggal_yudisium
					];
					$no++;
				}
			}

			// Struktur response yang diinginkan
			$response = [
				'status' => true,
				'message' => 'Data berhasil diambil',
				'data' => [
					'report_info' => [
						'title' => 'DATA LULUSAN YANG BERWIRAUSAHA SEBELUM LULUS & BERPENGHASILAN > 1,2 X UMR',
						'subtitle' => 'FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO',
						'umr' => $umr,
						'gaji_minimum' => $gaji_1_2_kali,
						'total_records' => count($result_data),
						'generated_at' => date('Y-m-d H:i:s')
					],
					'records' => $result_data
				]
			];

			// Response
			$this->response($response, RestController::HTTP_OK);

		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data kegiatan MBKM (IKU 2)
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional), semester (optional)
	 */
	public function iku_2_kegiatan_get()
	{
		try {
			// Validasi input
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');
			$semester = $this->get('semester');

			if (!$jenis_laporan || !in_array($jenis_laporan, ['1', '2', '3', '4'])) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-4)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			$header_info = [
				'title' => 'DATA PENGALAMAN MAHASISWA DI LUAR KAMPUS',
				'subtitle' => 'FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO',
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Pastikan result_data didefinisikan sebagai array kosong
			$result_data = [];

			// Validasi dan pengambilan data berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					// Semua data
					$mbkm_data = $this->report_model->get_pendaftaran_mbkm()->result();
					$mbkm_pertukaran = $this->report_model->get_pendaftaran_mbkm_pertukaran()->result();
					$mbkm_kegiatan_lain = $this->report_model->get_pendaftaran_mbkm_kegiatan_lain()->result();
					$header_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					// Berdasarkan program studi
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$mbkm_data = $this->report_model->get_pendaftaran_mbkm_prodi($kd_prodi)->result();
					$mbkm_pertukaran = $this->report_model->get_pendaftaran_mbkm_pertukaran_prodi($kd_prodi)->result();
					$mbkm_kegiatan_lain = $this->report_model->get_pendaftaran_mbkm_kegiatan_lain_prodi($kd_prodi)->result();
					$header_info['filter_type'] = 'Program Studi';
					$header_info['filter_value'] = $kd_prodi;
					break;

				case '3':
					// Berdasarkan semester
					if (!$semester) {
						$this->response([
							'status' => false,
							'message' => 'Parameter semester wajib diisi untuk jenis laporan 3',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$mbkm_data = $this->report_model->get_pendaftaran_mbkm_semester($semester)->result();
					$mbkm_pertukaran = $this->report_model->get_pendaftaran_mbkm_pertukaran_semester($semester)->result();
					$mbkm_kegiatan_lain = $this->report_model->get_pendaftaran_mbkm_kegiatan_lain_semester($semester)->result();
					$header_info['filter_type'] = 'Semester';
					$header_info['filter_value'] = $semester;
					break;

				case '4':
					// Berdasarkan program studi dan semester
					if (!$kd_prodi || !$semester) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi dan semester wajib diisi untuk jenis laporan 4',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}

					$mbkm_data = $this->report_model->get_pendaftaran_mbkm_prodi_semester($kd_prodi, $semester)->result();
					$mbkm_pertukaran = $this->report_model->get_pendaftaran_mbkm_pertukaran_prodi_semester($kd_prodi, $semester)->result();
					$mbkm_kegiatan_lain = $this->report_model->get_pendaftaran_mbkm_kegiatan_lain_prodi_semester($kd_prodi, $semester)->result();
					$header_info['filter_type'] = 'Program Studi & Semester';
					$header_info['filter_value'] = $kd_prodi . ' - Semester ' . $semester;
					break;
			}

			// Fungsi helper untuk memproses data MBKM
			$process_mbkm_data = function($data_array, $type) {
				$processed = [];
				$no = count($processed) + 1; // Ganti penggunaan $result_data menjadi $processed

				foreach ($data_array as $row) {
					// Ambil jumlah SKS berdasarkan tipe
					switch ($type) {
						case 'regular':
							$jumlah_sks = $this->report_model->get_jumlah_sks($row->id)->row()->jumlah_sks ?? 0;
							break;
						case 'pertukaran':
							$jumlah_sks = $this->report_model->get_jumlah_sks_pertukaran($row->id)->row()->jumlah_sks ?? 0;
							break;
						case 'kegiatan_lain':
							$jumlah_sks = $this->report_model->get_jumlah_sks_kegiatan_lain($row->id)->row()->jumlah_sks ?? 0;
							break;
						default:
							$jumlah_sks = 0;
					}

					$processed[] = [
						'no' => $no,
						'nama' => $row->nama,
						'nim' => $row->nim,
						'jenjang_studi' => $row->jenjang,
						'program_studi' => $row->nama_prodi,
						'semester' => $row->semester,
						'angkatan' => $row->angkatan,
						'jenis_mbkm' => $row->jenis_mbkm,
						'jenis_program_mbkm' => $row->nama_program,
						'kegiatan_mbkm' => $row->nama_kegiatan,
						'waktu_mulai' => ($type !== 'kegiatan_lain') ? $this->tgl_indo($row->waktu_mulai) : $row->waktu_mulai,
						'waktu_selesai' => ($type !== 'kegiatan_lain') ? $this->tgl_indo($row->waktu_selesai) : $row->waktu_selesai,
						'lokasi_mitra' => $row->nama_mitra,
						'jumlah_sks' => (int)$jumlah_sks,
						'id' => $row->id,
						'tipe_data' => $type
					];
					$no++;
				}

				return $processed;
			};

			// Proses semua jenis data MBKM
			$result_data = array_merge(
				$process_mbkm_data($mbkm_data, 'regular'),
				$process_mbkm_data($mbkm_pertukaran, 'pertukaran'),
				$process_mbkm_data($mbkm_kegiatan_lain, 'kegiatan_lain')
			);

			// Hitung statistik
			$total_sks = array_sum(array_column($result_data, 'jumlah_sks'));
			$statistics = [
				'total_mahasiswa' => count($result_data),
				'total_sks' => $total_sks,
				'rata_rata_sks' => count($result_data) > 0 ? round($total_sks / count($result_data), 2) : 0,
				'breakdown_by_type' => [
					'regular' => count(array_filter($result_data, function($item) { return $item['tipe_data'] === 'regular'; })),
					'pertukaran' => count(array_filter($result_data, function($item) { return $item['tipe_data'] === 'pertukaran'; })),
					'kegiatan_lain' => count(array_filter($result_data, function($item) { return $item['tipe_data'] === 'kegiatan_lain'; }))
				]
			];

			// Struktur response yang diinginkan
			$response = [
				'status' => true,
				'message' => 'Data berhasil diambil',
				'data' => [
					'report_info' => [
						'title' => 'DATA PENGALAMAN MAHASISWA DI LUAR KAMPUS',
						'subtitle' => 'FAKULTAS EKONOMI DAN BISNIS UNIVERSITAS TADULAKO',
						'umr' => 2303711, // contoh nilai UMR
						'gaji_minimum' => 2764453.1999999997, // contoh gaji minimum
						'total_records' => count($result_data),
						'generated_at' => date('Y-m-d H:i:s')
					],
					'records' => $result_data
				]
			];

			// Response
			$this->response($response, RestController::HTTP_OK);

		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data prestasi mahasiswa (IKU 2)
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional), tingkat_kegiatan (optional)
	 */
	public function iku_2_prestasi_get()
	{
		try {
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');
			$tingkat_kegiatan = $this->get('tingkat_kegiatan');

			// Validasi parameter jenis_laporan
			if (!$jenis_laporan || !in_array($jenis_laporan, $this->valid_jenis_laporan)) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-4)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			// Header informasi laporan
			$header_info = [
				'title' => 'DATA PRESTASI MAHASISWA',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Logic berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					$data_prestasi = $this->report_model->get_prestasi()->result();
					$header_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_prestasi = $this->report_model->get_prestasi_prodi($kd_prodi)->result();
					$header_info['filter_type'] = 'Program Studi';
					$header_info['filter_value'] = $kd_prodi;
					break;

				case '3':
					if (!$tingkat_kegiatan) {
						$this->response([
							'status' => false,
							'message' => 'Parameter tingkat_kegiatan wajib diisi untuk jenis laporan 3',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_prestasi = $this->report_model->get_prestasi_tingkat($tingkat_kegiatan)->result();
					$header_info['filter_type'] = 'Tingkat Kegiatan';
					$header_info['filter_value'] = $tingkat_kegiatan;
					break;

				case '4':
					if (!$kd_prodi || !$tingkat_kegiatan) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi dan tingkat_kegiatan wajib diisi untuk jenis laporan 4',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_prestasi = $this->report_model->get_prestasi_prodi_tingkat($kd_prodi, $tingkat_kegiatan)->result();
					$header_info['filter_type'] = 'Program Studi & Tingkat Kegiatan';
					$header_info['filter_value'] = $kd_prodi . ' - ' . $tingkat_kegiatan;
					break;
			}

			// Menyusun data prestasi
			$result_data = [];
			$base_url = base_url() . $this->base_url_prestasi;

			$no = 1;
			foreach ($data_prestasi as $prestasi) {
				// Memeriksa ketersediaan file
				$files = [
					'foto' => [
						'exists' => file_exists($this->upload_path_prestasi . $prestasi->foto),
						'url' => $prestasi->foto ? $base_url . $prestasi->foto : null
					],
					'sertifikat' => [
						'exists' => file_exists($this->upload_path_prestasi . $prestasi->sertifikat),
						'url' => $prestasi->sertifikat ? $base_url . $prestasi->sertifikat : null
					],
					'sk' => [
						'exists' => file_exists($this->upload_path_prestasi . $prestasi->sk),
						'url' => $prestasi->sk ? $base_url . $prestasi->sk : null
					],
					'link' => [
						'exists' => file_exists($this->upload_path_prestasi . $prestasi->link),
						'url' => $prestasi->link ? $base_url . $prestasi->link : null
					]
				];

				// Menambahkan data prestasi ke array hasil
				$result_data[] = [
					'no' => $no,
					'nama' => $prestasi->nama,
					'nim' => $prestasi->nim,
					'jenjang' => $prestasi->jenjang,
					'program_studi' => $prestasi->nama_prodi,
					'angkatan' => $prestasi->angkatan,
					'nama_kegiatan' => $prestasi->nama_kegiatan,
					'nama_pelaksana' => $prestasi->nama_pelaksana,
					'tingkat_kegiatan' => $prestasi->tingkat_kegiatan,
					'nama_pembimbing' => $prestasi->nama_pembimbing,
					'dana_diterima' => [
						'raw' => (float)$prestasi->dana_diterima,
						'formatted' => 'Rp ' . number_format($prestasi->dana_diterima, 0, ',', '.')
					],
					'tanggal_mulai' => $prestasi->tanggal_mulai,
					'tanggal_selesai' => $prestasi->tanggal_selesai,
					'peringkat' => $prestasi->peringkat,
					'jumlah_negara' => (int)$prestasi->jml_negara,
					'jumlah_perguruan_tinggi' => (int)$prestasi->jml_pt,
					'jenis_peserta' => $prestasi->jenis_peserta,
					'nomor_sertifikat' => $prestasi->nomor_sertifikat,
					'masa_pelaksanaan' => $prestasi->m_pelaksana,
					'nomor_sk' => $prestasi->nomor_sk,
					'keterangan' => $prestasi->keterangan,
					'files' => $files
				];
				$no++;
			}

			// Response format
			$this->response([
				'status' => true,
				'message' => 'Data prestasi berhasil diambil',
				'data' => [
					'report_info' => [
						'title' => 'DATA PRESTASI MAHASISWA',
						'subtitle' => $this->fakultas_name,
						'generated_at' => date('Y-m-d H:i:s'),
						'total_records' => count($result_data)
					],
					'records' => $result_data
				]
			], RestController::HTTP_OK);

		} catch (Exception $e) {
			// Error handling
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data dosen tetap (IKU 3)
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional), date_from (optional), date_to (optional)
	 */
	public function iku_3_data_dosen_get()
	{
		try {
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');
			$date_from = $this->get('date_from');
			$date_to = $this->get('date_to');

			if (!$jenis_laporan || !in_array($jenis_laporan, $this->valid_jenis_laporan)) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-4)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			$report_info = [
				'title' => 'DATA DOSEN TETAP',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Logic berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					$data_pekerjaan = $this->report_model->get_pekerjaan_dosen()->result();
					$data_wirausaha = $this->report_model->get_wirausaha_dosen()->result();
					$report_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_pekerjaan = $this->report_model->get_pekerjaan_dosen_prodi($kd_prodi)->result();
					$data_wirausaha = $this->report_model->get_wirausaha_dosen_prodi($kd_prodi)->result();
					$report_info['filter_type'] = 'Program Studi';
					$report_info['filter_value'] = $kd_prodi;
					break;

				case '3':
					if (!$date_from || !$date_to) {
						$this->response([
							'status' => false,
							'message' => 'Parameter date_from dan date_to wajib diisi untuk jenis laporan 3',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_pekerjaan = $this->report_model->get_pekerjaan_dosen_waktu($date_from, $date_to)->result();
					$data_wirausaha = $this->report_model->get_wirausaha_dosen_waktu($date_from, $date_to)->result();
					$report_info['filter_type'] = 'Rentang Waktu';
					$report_info['filter_value'] = $date_from . ' s/d ' . $date_to;
					break;

				case '4':
					if (!$kd_prodi || !$date_from || !$date_to) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi, date_from, dan date_to wajib diisi untuk jenis laporan 4',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_pekerjaan = $this->report_model->get_pekerjaan_dosen_prodi_waktu($kd_prodi, $date_from, $date_to)->result();
					$data_wirausaha = $this->report_model->get_wirausaha_dosen_prodi_waktu($kd_prodi, $date_from, $date_to)->result();
					$report_info['filter_type'] = 'Program Studi & Rentang Waktu';
					$report_info['filter_value'] = $kd_prodi . ' (' . $date_from . ' s/d ' . $date_to . ')';
					break;
			}

			$result_data = [];
			$no = 1;

			// Process data pekerjaan
			foreach ($data_pekerjaan as $dosen) {
				$tanggal_selesai = ($dosen->tanggal_berhenti == '0000-00-00') 
					? 'Masih Aktif' 
					: $this->tgl_indo($dosen->tanggal_berhenti);

				$result_data[] = [
					'no' => $no,
					'nama' => $dosen->nama,
					'nip' => $dosen->nip,
					'program_studi' => $dosen->nama_prodi,
					'pangkat_golongan' => $dosen->pangkat_gol,
					'jabatan_fungsional' => $dosen->jabatan_fungsional,
					'jenis_pekerjaan' => $dosen->jabatan,
					'nama_perusahaan' => $dosen->nama_perusahaan,
					'tanggal_mulai' => $this->tgl_indo($dosen->tanggal_masuk),
					'tanggal_selesai' => $tanggal_selesai,
					'status' => ($dosen->tanggal_berhenti == '0000-00-00') ? 'Aktif' : 'Tidak Aktif',
					'kategori' => 'Pekerjaan'
				];
				$no++;
			}

			// Process data wirausaha
			foreach ($data_wirausaha as $dosen) {
				$tanggal_selesai = ($dosen->tanggal_selesai == '0000-00-00') 
					? 'Masih Aktif' 
					: $this->tgl_indo($dosen->tanggal_selesai);

				$result_data[] = [
					'no' => $no,
					'nama' => $dosen->nama,
					'nip' => $dosen->nip,
					'program_studi' => $dosen->nama_prodi,
					'pangkat_golongan' => $dosen->pangkat_gol,
					'jabatan_fungsional' => $dosen->jabatan_fungsional,
					'jenis_pekerjaan' => 'Wirausaha',
					'nama_perusahaan' => $dosen->nama_usaha,
					'tanggal_mulai' => $this->tgl_indo($dosen->tanggal_mulai),
					'tanggal_selesai' => $tanggal_selesai,
					'status' => ($dosen->tanggal_selesai == '0000-00-00') ? 'Aktif' : 'Tidak Aktif',
					'kategori' => 'Wirausaha'
				];
				$no++;
			}

			// Statistics
			$statistics = [
				'total_records' => count($result_data),
				'pekerjaan' => count($data_pekerjaan),
				'wirausaha' => count($data_wirausaha),
				'aktif' => count(array_filter($result_data, function($item) { return $item['status'] === 'Aktif'; })),
				'tidak_aktif' => count(array_filter($result_data, function($item) { return $item['status'] === 'Tidak Aktif'; }))
			];

			$this->response([
				'status' => true,
				'message' => 'Data dosen tetap berhasil diambil',
				'data' => [
					'report_info' => $report_info,
					'records' => $result_data
				]
			], RestController::HTTP_OK);

		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data kualifikasi dosen (IKU 4)
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional)
	 */
	public function iku_4_data_kualifikasi_dosen_get()
	{
		try {
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');

			if (!$jenis_laporan || !in_array($jenis_laporan, ['1', '2'])) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-2)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			$report_info = [
				'title' => 'DATA KUALIFIKASI DOSEN BERSERTIFIKAT PROFESIONAL',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Logic berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					$data_s3 = $this->report_model->get_pendidikan_s3_dosen()->result();
					$data_sertifikasi = $this->report_model->get_sertifikasi_dosen()->result();
					$data_profesional = $this->report_model->get_pekerjaan_dosen()->result();
					$report_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_s3 = $this->report_model->get_pendidikan_s3_dosen_prodi($kd_prodi)->result();
					$data_sertifikasi = $this->report_model->get_sertifikasi_dosen_prodi($kd_prodi)->result();
					$data_profesional = $this->report_model->get_pekerjaan_dosen_prodi($kd_prodi)->result();
					$report_info['filter_type'] = 'Program Studi';
					$report_info['filter_value'] = $kd_prodi;
					break;
			}

			$result_sertifikat = [];
			$result_profesional = [];
			$no = 1;

			// Process data S3
			foreach ($data_s3 as $dosen) {
				$tahun_yudisium = ($dosen->tanggal_yudisium == '0000-00-00') 
					? 'Masih Aktif' 
					: date('Y', strtotime($dosen->tanggal_yudisium));

				$result_sertifikat[] = [
					'no' => $no,
					'nama' => $dosen->nama,
					'nip' => $dosen->nip,
					'program_studi' => $dosen->nama_prodi,
					'pangkat_golongan' => $dosen->pangkat_gol,
					'jabatan_fungsional' => $dosen->jabatan_fungsional,
					'jenjang_pendidikan' => $dosen->jenjang,
					'nama_kegiatan_sertifikasi' => '-',
					'tahun_selesai' => $tahun_yudisium,
					'kategori' => 'Pendidikan S3'
				];
				$no++;
			}

			// Process data sertifikasi
			foreach ($data_sertifikasi as $dosen) {
				$tahun_selesai = ($dosen->tanggal_selesai == '0000-00-00') 
					? 'Masih Aktif' 
					: date('Y', strtotime($dosen->tanggal_selesai));

				$result_sertifikat[] = [
					'no' => $no,
					'nama' => $dosen->nama,
					'nip' => $dosen->nip,
					'program_studi' => $dosen->nama_prodi,
					'pangkat_golongan' => $dosen->pangkat_gol,
					'jabatan_fungsional' => $dosen->jabatan_fungsional,
					'jenjang_pendidikan' => '-',
					'nama_kegiatan_sertifikasi' => $dosen->nama_kegiatan,
					'tahun_selesai' => $tahun_selesai,
					'kategori' => 'Sertifikasi'
				];
				$no++;
			}

			// Process data profesional
			$no_profesional = 1;
			foreach ($data_profesional as $dosen) {
				$result_profesional[] = [
					'no' => $no_profesional,
					'nama' => $dosen->nama,
					'nip' => $dosen->nip,
					'program_studi' => $dosen->nama_prodi,
					'pangkat_golongan' => $dosen->pangkat_gol,
					'jabatan_fungsional' => $dosen->jabatan_fungsional,
					'nama_perusahaan' => $dosen->nama_perusahaan,
					'jabatan' => $dosen->jabatan
				];
				$no_profesional++;
			}

			// Statistics
			$statistics = [
				'total_records' => count($result_sertifikat) + count($result_profesional),
				'breakdown_sertifikat' => [
					'pendidikan_s3' => count($data_s3),
					'sertifikasi' => count($data_sertifikasi)
				]
			];

			$this->response([
				'status' => true,
				'message' => 'Data kualifikasi dosen berhasil diambil',
				'data' => [
					'report_info' => $report_info,
					'records' => array_merge($result_sertifikat, $result_profesional)
				]
			], RestController::HTTP_OK);

		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data recognisi karya dosen (IKU 5)
	 * Method: GET
	 * Parameters: jenis_laporan, kd_prodi (optional), tahun (optional)
	 */
	public function iku_5_recognisi_get()
	{
		try {
			$jenis_laporan = $this->get('jenis_laporan');
			$kd_prodi = $this->get('kd_prodi');
			$tahun = $this->get('tahun');

			if (!$jenis_laporan || !in_array($jenis_laporan, $this->valid_jenis_laporan)) {
				$this->response([
					'status' => false,
					'message' => 'Parameter jenis_laporan wajib diisi (1-4)',
					'data' => null
				], RestController::HTTP_BAD_REQUEST);
				return;
			}

			$report_info = [
				'title' => 'DATA RECOGNISI KARYA DOSEN',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s')
			];

			// Logic berdasarkan jenis laporan
			switch ($jenis_laporan) {
				case '1':
					$data_karya = $this->report_model->get_karya_ilmiah()->result();
					$report_info['filter_type'] = 'Semua Data';
					break;

				case '2':
					if (!$kd_prodi) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi wajib diisi untuk jenis laporan 2',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_karya = $this->report_model->get_karya_ilmiah_prodi($kd_prodi)->result();
					$report_info['filter_type'] = 'Program Studi';
					$report_info['filter_value'] = $kd_prodi;
					break;

				case '3':
					if (!$tahun) {
						$this->response([
							'status' => false,
							'message' => 'Parameter tahun wajib diisi untuk jenis laporan 3',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_karya = $this->report_model->get_karya_ilmiah_tahun($tahun)->result();
					$report_info['filter_type'] = 'Tahun';
					$report_info['filter_value'] = $tahun;
					break;

				case '4':
					if (!$kd_prodi || !$tahun) {
						$this->response([
							'status' => false,
							'message' => 'Parameter kd_prodi dan tahun wajib diisi untuk jenis laporan 4',
							'data' => null
						], RestController::HTTP_BAD_REQUEST);
						return;
					}
					$data_karya = $this->report_model->get_karya_ilmiah_prodi_tahun($kd_prodi, $tahun)->result();
					$report_info['filter_type'] = 'Program Studi & Tahun';
					$report_info['filter_value'] = $kd_prodi . ' - ' . $tahun;
					break;
			}

			$result_data = [];
			$no = 1;

			// Process data and format to match the expected response
			foreach ($data_karya as $karya) {
				$result_data[] = [
					'no' => $no,
					'nama' => $karya->nama,
					'nip' => $karya->nip,
					'program_studi' => $karya->nama_prodi,
					'pangkat_golongan' => $karya->pangkat_gol,
					'jabatan_fungsional' => $karya->jabatan_fungsional,
					'jenjang_pendidikan' => $karya->jenjang, // Assuming the field is 'jenjang'
					'nama_kegiatan_sertifikasi' => $karya->nama_kegiatan ? $karya->nama_kegiatan : '-', // If there's no name of certification, use '-'
					'tahun_selesai' => $karya->tanggal_selesai == '0000-00-00' ? 'Masih Aktif' : date('Y', strtotime($karya->tanggal_selesai)),
					'kategori' => $karya->kategori // Assuming the category is part of the data.
				];
				$no++;
			}

			// Final response format
			$this->response([
				'status' => true,
				'message' => 'Data recognisi karya dosen berhasil diambil',
				'data' => [
					'report_info' => $report_info,
					'records' => $result_data
				]
			], RestController::HTTP_OK);

		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data kerjasama dengan mitra (IKU 6)
	 * Method: GET
	 */
	public function iku_6_mitra_get()
	{
		try {
			// Header info untuk laporan
			$header_info = [
				'title' => 'DATA KERJASAMA PRODI DENGAN MITRA',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s'),
				'filter_type' => 'Semua Data'
			];

			// Ambil data dari model
			$data_iku6 = $this->report_model->get_mitra()->result(); // Tridharma
			$data_iku6_1 = $this->report_model->get_mitra_kurikulum()->result(); // Kurikulum
			$data_iku6_2 = $this->report_model->get_mitra_magang()->result(); // Magang

			// Persiapkan data untuk respon JSON
			$result_data = [
				'mitra_tridarma' => $data_iku6,
				'mitra_kurikulum'	=> $data_iku6_1,
				'mitra_magang'	=> $data_iku6_2
			];

			// Persiapkan data untuk response JSON
			$response_data = [
				'status' => true,
				'message' => 'Data kerjasama mitra berhasil diambil',
				'data' => [
					'report_info' => $header_info,
					'records' => $result_data
				]
			];

			// Kirimkan response JSON
			$this->response($response_data, RestController::HTTP_OK);

		} catch (Exception $e) {
			// Jika terjadi error, kirimkan pesan error dalam format JSON
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * API untuk mendapatkan data mata kuliah (IKU 7)
	 * Method: GET
	 */
	public function iku_7_matakuliah_get()
	{
		try {
			// Header info untuk laporan
			$header_info = [
				'title' => 'DATA MATKUL CM TMP MIX',
				'subtitle' => $this->fakultas_name,
				'generated_at' => date('Y-m-d H:i:s'),
				'filter_type' => 'Semua Data'
			];

			// Ambil data dari model
			$data_iku7 = $this->report_model->get_matakuliah()->result();

			// Menyiapkan array untuk menyimpan hasil data
			$result_data = [];
			$no = 1;

			// Proses data mata kuliah
			foreach ($data_iku7 as $show) {
				$result_data[] = [
					'no' => $no,
					'kode_mata_kuliah' => $show->kd_mk,
					'nama_mata_kuliah' => $show->matakuliah,
					'sks' => $show->sks
				];
				$no++;
			}

			// Persiapkan data untuk response JSON
			$response_data = [
				'status' => true,
				'message' => 'Data matakuliah berhasil diambil',
				'data' => [
					'report_info' => $header_info,
					'records' => $result_data
				]
			];

			// Kirimkan response dalam format JSON
			$this->response($response_data, RestController::HTTP_OK);
			
		} catch (Exception $e) {
			// Jika terjadi error, kirimkan pesan error dalam format JSON
			$this->response([
				'status' => false,
				'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
				'data' => null
			], RestController::HTTP_INTERNAL_ERROR);
		}
	}

	/**
	 * Helper function untuk format tanggal Indonesia
	 */
	private function tgl_indo($tanggal)
	{
		if (!$tanggal) return '';
		
		$bulan = [
			1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
			'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];
		
		$pecahkan = explode('-', $tanggal);
		if (count($pecahkan) === 3) {
			return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
		}
		
		return $tanggal;
	}

}
