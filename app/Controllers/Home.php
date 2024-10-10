<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Request;

class Home extends BaseController
{
    protected $user;
    
    public function __construct()
    {
        helper(['url']);
        $this->user = new UserModel();
    }
    public function index()
    {
        echo view('/inc/header.php');
        $data['users'] = $this->user->orderBy('id', 'desc')->paginate(3, 'group1');
        $data['pager'] = $this->user->pager;    
        echo view('/home.php', $data);
        echo view('/inc/footer.php');
    }

    public function saveUser() {
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');
        $this->user->save(['username' => $username, 'email' => $email]);
        session()->setFlashdata("success", "Data inserted successfully");
        return redirect()->to(base_url());
    }

    public function getSingleUser($id) {
        $data = $this->user->where('id', $id)->first();
        echo json_encode($data);
    }

    public function updateUser() {
        $id = $this->request->getVar('userId');
        $username = $this->request->getVar('username');
        $email = $this->request->getVar('email');

        $data['username'] = $username;
        $data['email'] = $email;

        $this->user->update($id,$data);
        return redirect()->to(base_url());
    }

    public function deleteUser() {
        $id = $this->request->getVar('id');
        $this->user->delete($id);
        echo "1";
    }

    public function deleteMultiUser() {
        $ids = $this->request->getVar('ids');
        for($count = 0; $count < count($ids); $count++) {
            $this->user->delete($ids[$count]);
        }
        echo "1";
    }
}
