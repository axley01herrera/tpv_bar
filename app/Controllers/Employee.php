<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class Employee extends BaseController
{
    protected $objSession;

    function  __construct()
    {
        $this->objSession = session();
    }

   

    

    

     // OK

    public function showModalSetClave()
    {
        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['userID'] = $this->request->getPost('userID');
        $data['action'] = $this->request->getPost('action');

        $objModel = new EmployeeModel;
        $userData = $objModel->getUserData($data['userID']);

        if ($data['action'] == 'set_clave')
            $data['title'] = 'Creando Contraseña para ' . $userData[0]->name . ' ' . $userData[0]->lastName;
        else
            $data['title'] = 'Actualizando Contraseña de ' . $userData[0]->name . ' ' . $userData[0]->lastName;

        return view('admin/modals/setClave', $data);
    } // OK

    public function setClave()
    {
        $response = array();

        # VERIFY SESSION
        if (empty($this->objSession->get('user')['hash']))
            return view('logoutAdmin');

        $data = array();
        $data['clave'] = password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT); // ENCRYPT PASSWORD

        $objModel = new EmployeeModel;
        $result = $objModel->updateUser($data, $this->request->getPost('userID'));

        if ($result['error'] == 0) {

            $response['error'] = 0;
            $response['msg'] = 'Proceso realizado con éxito';
        } else {

            $response['error'] = 1;
            $response['msg'] = 'Ha ocurrido un error en el proceso';
        }

        return json_encode($response);
    } // OK

     // OK

     // OK
}
