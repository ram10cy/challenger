<?php


namespace App\Respositories;

use App\Models\AppointmentModel;
use App\Models\ContactModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class ContactRespository
{
    protected $contact;

    /**
     * AppointModel constructor.
     * @param AppointmentModel $user
     */
    public function __construct(ContactModel $contact)
    {
        $this->contact = $contact;
    }
    /**
     * @param $contactData
     * @return mixed
     */
    public function store($contactData){
        //Validate data
        $validator = Validator::make($contactData, [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|min:8',
            'address' => 'required|string'

        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }

        //Request is valid, create new contact
        $contact = $this->contact->create([
            'name' => $contactData['name'],
            'surname' => $contactData['surname'],
            'email' => $contactData['email'],
            'phone' => $contactData['phone'],
            'address' => $contactData['address'],
            'user_id'=>auth()->user()->id,

        ]);
        //Contact created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Contact created successfully',
            'data' => $contact
        ], Response::HTTP_OK);

    }
    /**
     * @param $contactId
     * @return mixed
     */
    public function info($contactId){
        $contact = $this->contact->find($contactId);
        if($contact)
            return response()->json([
                'success'=>true,
                'data' => $contact]);
        else
            return response()->json([
                'success'=>false,
                'error' => 'Contact id:'.$contactId.' not found!'
          ]);
    }
    public function listContacts(){
        $contact = $this->contact->where('user_id',auth()->user()->id)->get();
        if($contact->count())
            return response()->json([
                'success'=>true,
                'data' => $contact],200);
        else
            return response()->json([
                'success'=>false,
                'error' => 'You have not  any contact yet, first create one ;)'
              ]);
    }
    /**
     * @param $contactData
     * @return mixed
     */
    public function update($contactData){

        //Validate data
        $validator = Validator::make($contactData, [
            'id'=>'required|numeric|',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|min:8',
            'address' => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'error' => $validator->messages()], 200);
        }


        if($this->contact->where('id',$contactData['id'])->doesntExist())
            return response()->json([
                'success'=>false,
                'error' => 'Contact not found! Please check Contact Id'], 200);

        $user=auth()->user();
        //Request is valid, create new contact
        $contact = $this->contact->find($contactData['id'])->update([
            'name' => $contactData['name'],
            'surname' => $contactData['surname'],
            'email' => $contactData['email'],
            'phone' => $contactData['phone'],
            'address' => $contactData['address'],
            'user_id'=>$user->id,

        ]);
        //Contact created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Contact updated successfully',
            'data' => $this->contact->find($contactData['id'])
        ], Response::HTTP_OK);

    }
    /**
     * @param $contactId
     * @return mixed
     */
    public function delete($contactId){
        if($contact=$this->contact->find($contactId)){
            AppointmentModel::where('contact_id',$contactId)->delete();
            $contact->delete();
            return response()->json([
                'success'=>true,
                'message' => 'Contact deleted!']);
        }
            return response()->json([
                'success'=>false,
                'error' => 'Contact id:'.$contactId.' not found!']);
    }


}
