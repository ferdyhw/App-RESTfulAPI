<?php

namespace App\Controllers;

use App\Models\Mahasiswa_model;

class Mahasiswa extends BaseController
{
    protected $_mahasiswaModel;
    public function __construct()
    {
        $this->_mahasiswaModel = new Mahasiswa_model();
        $this->curl = \Config\Services::curlrequest([
            'base_uri' => 'http://localhost/rest-api/wpu-restserver/api/',
            'auth' => ['admin', '1234']
        ]);
    }

    public function index()
    {

        $data = [
            'judul' => 'Mahasiswa',
            'mahasiswa' => $this->_mahasiswaModel->getAllMahasiswa()
        ];

        return view('mahasiswa/index', $data);
    }

    public function detail($id)
    {
        $data = [
            'judul' => 'Detail',
            'mahasiswa' => $this->_mahasiswaModel->getMahasiswaById($id)
        ];

        if (empty($data['mahasiswa'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID mahasiswa ' . $id . ' tidak di temukan.');
        }

        return view('mahasiswa/detail', $data);
    }

    public function tambah()
    {
        session();
        $data = [
            'judul' => 'Form Tambah Data Mahasiswa',
            'validation' => \Config\Services::validation()
        ];
        return view('mahasiswa\tambah', $data);
    }

    public function prosesTambah()
    {
        if (!$this->validate([
            'nama' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Nama harus diisi.',
                    'alpha_space' => 'Nama harus diisi hanya dengan huruf.'
                ]
            ],
            'nrp' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'NRP harus diisi.',
                    'numeric' => 'NRP harus diisi hanya dengan angka.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_emails',
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_emails' => 'Email harus diisi dengan format yang benar.'
                ]
            ],
            'jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jurusan harus diisi.'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/mahasiswa/tambah')->withInput()->with('validation', $validation);
        } else {
            $data = [
                'nama' => $this->request->getPost('nama'),
                'nrp' => $this->request->getPost('nrp'),
                'email' => $this->request->getPost('email'),
                'jurusan' => $this->request->getPost('nama'),
            ];
            $result = $this->curl->request('POST', 'mahasiswa', [
                'form_params' => $data
            ]);
            session()->setFlashData('flash', 'tambah');
            return redirect()->to('/mahasiswa');
        }
    }

    public function ubah($id)
    {
        session();
        $data = [
            'judul' => 'Form Ubah Data Mahasiswa',
            'validation' => \Config\Services::validation(),
            'mahasiswa' => $this->_mahasiswaModel->getMahasiswaById($id),
            'jurusan' => ['Teknik Informatika', 'Teknik Mesin', 'Teknik Planologi', 'Teknik Pangan', 'Teknik Lingkungan']
        ];

        return view('mahasiswa/ubah', $data);
    }

    public function prosesUbah()
    {
        if (!$this->validate([
            'nama' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Nama harus diisi.',
                    'alpha_space' => 'Nama harus diisi hanya dengan huruf.'
                ]
            ],
            'nrp' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'NRP harus diisi.',
                    'numeric' => 'NRP harus diisi hanya dengan angka.'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_emails',
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_emails' => 'Email harus diisi dengan format yang benar.'
                ]
            ],
            'jurusan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jurusan harus diisi.'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/mahasiswa/ubah/' . $this->request->getVar('id'))->withInput()->with('validation', $validation);
        } else {
            $data = [
                'id' => $this->request->getVar('id'),
                'nama' => $this->request->getVar('nama'),
                'nrp' => $this->request->getVar('nrp'),
                'email' => $this->request->getVar('email'),
                'jurusan' => $this->request->getVar('jurusan')
            ];
            $result = $this->curl->request('PUT', 'mahasiswa', [
                'form_params' => $data
            ]);
            session()->setFlashData('flash', 'ubah');
            return redirect()->to('/mahasiswa');
        }
    }

    public function hapus($id)
    {
        $this->_mahasiswaModel->hapusDataMahasiswa($id);
        session()->setFlashData('flash', 'hapus');
        return redirect()->to('/mahasiswa');
    }
}
