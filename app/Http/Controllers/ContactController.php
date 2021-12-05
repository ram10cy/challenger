<?php

namespace App\Http\Controllers;

use App\Models\ContactModel;
use App\Respositories\ContactRespository;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * @var ContactRespository
     */
    private $contactRespository;
    public function __construct(ContactRespository $contactRespository){
        $this->contactRespository = $contactRespository;
    }

    // Register new contact
    public function register(Request $request){
        return $this->contactRespository->store($request->all());
    }
    //Contact Info
    public function info(Request $request){
      return $this->contactRespository->info($request->contactId);
    }
    //Contact update
    public function update(Request $request){
        return $this->contactRespository->update($request->all());
    }
    //List user's contacs
    public function listContacts(){
        return $this->contactRespository->listContacts();
    }
    //delete contact
    public function delete($contactId){
        return $this->contactRespository->delete($contactId);
    }

}
