<?php

class User extends CI_Controller{

    public function all(){
        if($this->access_app()){
                echo json_encode($this->db->get("usuario")->result());
            
        }
    }

    public function get($id = null){
        if($this->access_app()){
            if($id == null){
                $this->output->set_status_header(404, "No se encontro el dato solicitado");
                echo json_encode(array("code" => 404, "message" => "No se encontro el dato solicitado,hay que definir una id:get7{id}"));
            }else{
                $this->load->database();
                $this->db->where("idusuario",$id);
                $query = $this->db->get("usuario");
                if($query->num_rows() == 0){
                    $this->output->set_status_header(404, "No se encontro el dato solicitado");
                    echo json_encode(array("code" => 404, "message" => "No se encontro el dato solicitado,hay que definir una id:get7{id}"));    
                }else{
                echo json_encode($query->row());
                }
            }
        }
    }
    public function add(){
        if($this->access_app()){
            $dataform = $this->input->post();
            if (count(dataform) == 0)
            {
                $this->output->set_status_header(400, "error en la peticion");
                echo json_encode(array("code" => 400, "message" => "error en la peticion"));
            }else{
            $this->load->database();
            $this->db->insert("usuario", $dataform);
            $last_insert_id = $this->db->insert_id();
            $dataform["idusuario"] = $last_insert_id;
            echo json_encode($dataform);
            }
        }    
    }

    
    
    public function update($id = null){
        if($this->access_app()){
            if($id == null){
                $this->output->set_status_header(404, "No se actualizo el dato solicitado");
                echo json_encode(array("code" => 404, "message" => "No se actualizo el dato solicitado,hay que definir una id:update/{id}"));
            }else{
                $dataform = $this->input->post();
                $this->load->database();
                $this->db->where('idusuario', $id);
                $this->db->update('usuario', $dataform);
                $this->db->where('idusuario', $id);
                $userupdate = $this->db->get('usuario')->row();
                echo json_encode($userupdate);
            }
        }

    }
    public function delete($id = null){
        if($this->access_app()){
            if($id == null){
                $this->output->set_status_header(404, "No se elimino el dato solicitado");
                echo json_encode(array("code" => 404, "message" => "No se elimino el dato solicitado,hay que definir una id delete/{id}"));
            }else{
                $dataform = $this->input->post();
                $this->load->database();
                $this->db->where('idusuario', $id);
                $this->db->delete('usuario');
                $this->db->where('idusuario',$id);
                $hasdelete = $this->db->get('usuario')->num_rows() == 0;
                echo json_encode(array("code" => 200, "message" => $hasdelete ? "se elimino el dato" : "no se pudo eliminar el dato" ));
            }
        }

    }
    
    public function access_app(){
        $headers = $this->input->request_headers();
        $keyname = "X-API-Key";
        if (array_key_exists("X-API-Key", $headers)){
            $token = $headers["X-API-Key"];
            $this->load->database();
            $this->db->where("token", $token);
            $query = $this->db->get("app");
            if ($query->num_rows() > 0){
                return true;
            }
                
        }
        $this->output->set_status_header(401,"No se ha definido el API-Key");
        echo json_encode(array("code" => 401, "message" => "no se ha concedido el acceso a la app"));
         
    }
}