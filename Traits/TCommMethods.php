<?php


namespace Traits;


trait TCommMethods
{
   public function saveMethod($data)
   {
       $method = $this->commRepo->create($data);

       switch ($data["type"])
       {
           case "mobile":
           case "phone":
           case "fax":
               foreach ($data[$data["type"]] as $da)
               {
                   $number = $this->numRepo->create($da);
                   $this->numRepo->createRelated($number,$method->id);
               }
               $method->load('contactConfiguration');
               return $method;
               break;
           case "address":
                   $address = $this->addRepo->create($data['address']);
                   $this->addRepo->createRelated($address,$data['address']['details'],$method->id);
                   $method->load('contactConfiguration');
               return $method;

           case "email":
           case "note":
           case "location":
               return $method;
               break;

           default:
               break;
       }
   }
}
