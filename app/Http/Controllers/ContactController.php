<?php

namespace App\Http\Controllers;
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

    // Create new contact
    public function create(Request $request){
        return $this->contactRespository->store($request->all());
    }
    //Contact info
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
    public function delete(Request $request){
        return $this->contactRespository->delete($request->id);
    }

}
