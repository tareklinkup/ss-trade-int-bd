<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
    Replace controller
    Sale replace

*/

class Replaces extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
         if($access == '' ){
            redirect("Login");
        }
        
        $this->load->model('Model_table', "mt", TRUE);
    }

    public function index() {
        $data['title'] = "Sales Replace";
        $data['content'] = $this->load->view('Administrator/sales/sale_replace', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function addReplace() {
        $res = new stdClass;
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);

            $purchaseReplace = array(
                'date' => $data->replace->date,
                'customer_id' => $data->replace->customerId,
                'note' => $data->replace->note,
                'status' => 'a',
                'added_by' => $this->session->userdata("FullName"),
                'add_date' => date("Y-m-d H:i:s"),
                'branch_id' => $this->session->userdata("BRANCHid"),
            );

            $this->db->insert('sale_replaces', $purchaseReplace);
            $replaceId = $this->db->insert_id();

            foreach($data->cart as $product) {
                $detail = array(
                    'replace_id' => $replaceId,
                    'product_id' => $product->productId,
                    'serial_in' => $product->serialIn,
                    'serial_out' => $product->serialOut,
                    'status' => 'a',
                    'branch_id' => $this->session->userdata('BRANCHid')
                ); 

                // replace details insert
                $this->db->insert('replace_details', $detail);

                // old serial sales to change no sales
                $this->db->query("
                    update tbl_product_serial_numbers
                    set ps_s_status = 'no', sale_replace = 'yes'
                    where ps_prod_id = ? 
                    and ps_serial_number = ?
                    and ps_brunch_id = ?
                ", [$product->productId, $product->serialIn, $this->session->userdata('BRANCHid')]);

                // add new serial sale
                $this->db->query("
                    update tbl_product_serial_numbers
                    set ps_s_status = 'yes'
                    where ps_prod_id = ? 
                    and ps_serial_number = ?
                    and ps_brunch_id = ?
                ", [$product->productId, $product->serialOut, $this->session->userdata('BRANCHid')]);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $res->message = 'Replace successfully';
                $res->success = true;
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            $res->message = 'failed'. $e->getMessage();
            $res->success = false;
        }

        echo json_encode($res);
    }

    public function replaceRecord() {
        $data['title'] = "Sales Replace Record";
        $data['content'] = $this->load->view('Administrator/sales/sale_replace_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getReplaceRecord() {
        $res = new stdClass;
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo) {
            $clauses .= " and sr.date between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->replaceId) && $data->replaceId != '') {
            $clauses .= " and sr.id = $data->replaceId";

            $res->details = $this->db->query("
                select
                    rd.*,
                    p.Product_Code,
                    p.Product_Name
                from replace_details rd
                join tbl_product p on p.Product_SlNo = rd.product_id
                where rd.status = 'a'
                and rd.replace_id = ?
            ", $data->replaceId)->result();
        }

        $res->records = $this->db->query("
            select 
                sr.*,
                c.Customer_Code,
                c.Customer_Name,
                c.Customer_Mobile,
                c.Customer_Address
            from sale_replaces sr 
            left join tbl_customer c on c.Customer_SlNo = sr.customer_id
            where sr.status = 'a'
            and sr.branch_id = ?
            $clauses
            order by sr.id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($res);
    }

    public function replaceInvoice($id) {
        $data['title'] = "Sales Replace";
        $data['replaceId'] = $id;
        $data['content'] = $this->load->view('Administrator/sales/replace_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    // puchase replace
    public function purchaseReplace() {
        $data['title'] = "Purchase Replace";
        $data['content'] = $this->load->view('Administrator/purchase/puchase_replace', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function addPurchaseReplace() {
        $res = new stdClass;
        try {
            $this->db->trans_begin();
            $data = json_decode($this->input->raw_input_stream);
     
            $purchaseReplace = array(
                'date' => $data->replace->date,
                'supplier_id' => $data->replace->supplierId,
                'note' => $data->replace->note,
                'status' => 'a',
                'added_by' => $this->session->userdata("FullName"),
                'add_date' => date("Y-m-d H:i:s"),
                'branch_id' => $this->session->userdata("BRANCHid"),
            );

            $this->db->insert('purchase_replaces', $purchaseReplace);
            $replaceId = $this->db->insert_id();

            foreach($data->cart as $product) {
                $detail = array(
                    'replace_id' => $replaceId,
                    'product_id' => $product->productId,
                    'serial_in' => $product->serialIn,
                    'serial_out' => $product->serialOut,
                    'status' => 'a',
                    'branch_id' => $this->session->userdata('BRANCHid')
                ); 

                // replace details insert
                $this->db->insert('purchase_replacedetails', $detail);

                // product serial update purchase replace
                $this->db->query("
                    update tbl_product_serial_numbers
                    set purchase_replace = 'yes'
                    where ps_prod_id = ? 
                    and ps_serial_number = ?
                    and ps_brunch_id = ?
                ", [$product->productId, $product->serialOut, $this->session->userdata('BRANCHid')]);

                // replace old serial to new product serial
                $this->db->query("
                    update tbl_product_serial_numbers
                    set ps_serial_number = ?, sale_replace = 'no'
                    where ps_prod_id = ? 
                    and ps_serial_number = ?
                    and ps_brunch_id = ?
                ", [$product->serialIn, $product->productId, $product->serialOut, $this->session->userdata('BRANCHid')]);
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $res->message = 'Replace successfully';
                $res->success = true;
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            $res->message = 'failed'. $e->getMessage();
            $res->success = false;
        }

        echo json_encode($res);
    }

    public function purchaseReplaceRecord() {
        $data['title'] = "Purchase Replace Record";
        $data['content'] = $this->load->view('Administrator/purchase/purchase_replace_record', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getPurchaseReplaceRecord() {
        $res = new stdClass;
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";

        if(isset($data->dateFrom) && $data->dateFrom != '' && isset($data->dateTo) && $data->dateTo) {
            $clauses .= " and pr.date between '$data->dateFrom' and '$data->dateTo'";
        }

        if(isset($data->replaceId) && $data->replaceId != '') {
            $clauses .= " and pr.id = $data->replaceId";

            $res->details = $this->db->query("
                select
                    rd.*,
                    p.Product_Code,
                    p.Product_Name
                from purchase_replacedetails rd
                join tbl_product p on p.Product_SlNo = rd.product_id
                where rd.status = 'a'
                and rd.replace_id = ?
            ", $data->replaceId)->result();
        }

        $res->records = $this->db->query("
            select 
                pr.*,
                s.Supplier_Code,
                s.Supplier_Name,
                s.Supplier_Mobile,
                s.Supplier_Address
            from purchase_replaces pr 
            left join tbl_supplier s on s.Supplier_SlNo = pr.supplier_id
            where pr.status = 'a'
            and pr.branch_id = ?
            $clauses
            order by pr.id desc
        ", $this->session->userdata('BRANCHid'))->result();

        echo json_encode($res);
    }

    public function purchaseReplaceInvoice($id) {
        $data['title'] = "Purchase Replace Invoice";
        $data['replaceId'] = $id;
        $data['content'] = $this->load->view('Administrator/purchase/replace_invoice', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
}