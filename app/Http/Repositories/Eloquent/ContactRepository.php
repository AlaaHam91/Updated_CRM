<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Contact;
use App\Models\CpersonLife;

class ContactRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IContactRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Contact::class;
    }

    /**
     * @param Contact $contact
     * @param $data
     * @return CpersonLife
     */
    public function createRelated(Contact $contact, $data):CpersonLife
    {
        return $contact->cpersonLife()->create($data);
    }

    /**
     * @param CpersonLife $life
     * @param $data
     * @return array
     */
    public function synActivities(CpersonLife $life, $data)
    {
          return $life->lifeActivities()->sync($data);
    }

    /**
     * @param CpersonLife $life
     * @param $data
     * @return array
     */
    public function synInterests(CpersonLife $life, $data)
    {
         return $life->lifeInterests()->sync($data);
    }

    /**
     * @param Contact $contact
     * @param $data
     * @param $rel_name
     * @return mixed
     */
    public function syncRelated(Contact $contact , $data , $rel_name)
    {
        $relation = $contact->{$rel_name}();
        return $relation->sync($data);
    }

    /**
     * @param Contact $contact
     * @param $data
     * @param $rel_name
     * @return mixed
     */
    public function createManyRelated(Contact $contact , $data , $rel_name)
    {
        $relation = $contact->{$rel_name}();
        $relation->delete();
        return $relation->createMany($data);
    }

}
