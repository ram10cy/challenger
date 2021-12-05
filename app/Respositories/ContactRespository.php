<?php


namespace App\Respositories;

use App\Models\ContactModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class ContactRespository
{

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
            return response()->json(['error' => $validator->messages()], 200);
        }

   $user=auth()->user();

        //Request is valid, create new user
        $contact = ContactModel::create([
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
            'message' => 'Contact created successfully',
            'data' => $contact
        ], Response::HTTP_OK);

    }
    public function info($contactId){
        $contact = ContactModel::find($contactId);
        if($contact)
            return response()->json(['Contact' => $contact]);
        else
            return response()->json(['user' => 'Contact id:'.$contactId.' not found!']);
    }
    public function listContacts(){
        $contact = ContactModel::where('user_id',auth()->user()->id)->get();
        if($contact->count())
            return response()->json(['Contacts' => $contact]);
        else
            return response()->json(['user' => 'You have not  any contact yet, first create one ;)']);
    }
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
            return response()->json(['error' => $validator->messages()], 200);
        }


        if(ContactModel::where('id',$contactData['id'])->doesntExist())
            return response()->json(['error' => 'Contact not found! Please check Contact Id'], 200);

        $user=auth()->user();
        //Request is valid, create new contact
        $contact = ContactModel::find($contactData['id'])->update([
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
            'data' => $contact
        ], Response::HTTP_OK);

    }
    public function delete($contactId){
        if($contact=ContactModel::find($contactId)){
            $contact->delete();
            return response()->json(['user' => 'Contact deleted!']);
        }
            return response()->json(['user' => 'Contact id:'.$contactId.' not found!']);
    }


}
