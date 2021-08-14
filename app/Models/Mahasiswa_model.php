<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa_model extends Model
{
    public function __construct()
    {
        $this->curl = \Config\Services::curlrequest([
            'base_uri' => 'http://localhost/rest-api/wpu-restserver/api/',
            'auth' => ['admin', '1234']
        ]);
    }

    public function getAllMahasiswa()
    {
        $response = $this->curl->request('GET', 'mahasiswa', [
            'query' => [
                'api-key' => 'letmein'
            ]
        ]);
        $result = json_decode($response->getBody(), TRUE);
        return $result['data'];

        // $response =  $this->client->request('GET', 'mahasiswa', [
        //     'query' => [
        //         'api-key' => 'letmein'
        //     ]
        // ]);
        // $result = json_decode($response->getBody(), true);
        // return $result['data'];
    }

    public function getMahasiswaById($id)
    {
        $response = $this->curl->request('GET', 'mahasiswa', [
            'query' => [
                'api-key' => 'letmein',
                'id' => $id
            ]
        ]);
        $result = json_decode($response->getBody(), TRUE);
        return $result['data'][0];

        // $response =  $this->client->request('GET', 'mahasiswa', [
        //     'query' => [
        //         'api-key' => 'letmein',
        //         'id' => $id
        //     ]
        // ]);
        // $result = json_decode($response->getBody(), true);
        // return $result['data'][0];
    }

    public function hapusDataMahasiswa($id)
    {
        $response = $this->curl->request('DELETE', 'mahasiswa', [
            'form_params' => [
                'api-key' => 'letmein',
                'id' => $id
            ]
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }
}
