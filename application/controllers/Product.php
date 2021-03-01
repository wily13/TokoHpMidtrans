<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_Model');
    }

    public function index()
    {
        $data = [
            'semuaproduk'       => $this->Product_Model->getallproduk(),
            'keranjang'         => $this->Product_Model->getallcart(),
            'semuaTransaksi'    => $this->Product_Model->getAllTransaction()
        ];
        $this->load->view('product/cart', $data);
    }

    public function simpan()
    {
        $productid  = $this->input->post('produkid');
        $jumlah  = $this->input->post('jumlah');
        $datainsert =   [
            'product_id'   => $productid,
            'jumlah'       => $jumlah,
            'status'       => 0
        ];

        $insert     = $this->Product_Model->insertcart($datainsert);
        if ($insert > 0) {
            $this->session->set_flashdata('message', 'Data keranjang berhasil ditambah');
        } else {
            $this->session->set_flashdata('message', 'Server sedang sibuk, silahkan coba lagi');
        }
        redirect('product');
    }

    public function delete($id)
    {
        $this->Product_Model->deleteCartById($id);
        $this->session->set_flashdata('message', 'Data keranjang berhasil di hapus');
        return redirect()->to('product');
    }
}
