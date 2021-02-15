<?php


namespace App\Http\Repositories\IRepositories;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Model;
interface IBaseRepository
{
    /**
     * @return Model
     */
    public function makeModel();

    /**
     * @param array $conditions
     * @param array $columns
     * @return mixed
     */
    public function all($conditions = [], $columns = array('*'));

    /**
     * @param $model
     * @return mixed
     */
    public function create($model);

    /**
     * @param $key
     * @param $value
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($key, $value, $data);

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id");

    /**
     * @param mixed $id
     *
     * @return void
     */
    public function delete($model);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'));

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'));

    /**
     * @return mixed
     *
     */
    public function latestRecord();
    /**
     * @param $id
     * @return BigInteger
     */
    public function next($id);

    /**
     * @param $id
     * @return BigInteger
     */
    public function previous($id);

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();


    function model();
}
