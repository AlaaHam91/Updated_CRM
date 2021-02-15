<?php


namespace App\Http\Repositories\Eloquent;

use Helpers\JsonResponse;
use Helpers\Mapper;
use Helpers\Constants;
use Illuminate\Container\Container as App;
use Helpers\ResponseStatus;
use App\Http\Repositories\IRepositories\IBaseRepository;

/**
 * Class BaseRepository
 * @package App\Http\Repositories\Eloquent
 */
abstract class BaseRepository implements IBaseRepository
{

    /**
     * @var App
     */
    private $app;
    /**
     * @var array
     */
    private $requestData;

    /**
     * @var
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param App $app
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function __construct(App $app)
    {
        try {
            $this->app = $app;
            $this->makeModel();
            $this->requestData = Mapper::toUnderScore(\Request()->all());
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param array $columns
     * @param array $conditions
     * @return mixed
     * @throws \Exception
     */
    public function all($conditions = [], $columns = array('*'))
    {
        try {
            $order_by = isset($this->requestData[Constants::ORDER_BY]) ? $this->requestData[Constants::ORDER_BY] : null;
            $order_by_direction = isset($this->requestData[Constants::ORDER_By_DIRECTION]) ? $this->requestData[Constants::ORDER_By_DIRECTION] : "asc";
            $filter_operator = isset($this->requestData[Constants::FILTER_OPERATOR]) ? $this->requestData[Constants::FILTER_OPERATOR] : "=";
            $filters = $this->requestData[Constants::FILTERS] ?? [];
            $per_page = isset($this->requestData[Constants::PER_PAGE]) ? $this->requestData[Constants::PER_PAGE] : 10;
            $paginate = isset($this->requestData[Constants::PAGINATE])?$this->requestData[Constants::PAGINATE]:true;
            $query = $this->model;
            $allConditions = array_merge($conditions, $filters);
            $query = $query->filter($allConditions, $filter_operator);
            if (isset($order_by)) $query = $query->orderBy($order_by, $order_by_direction);
            //dd($query->toSql());
            if($paginate) return $query->paginate($per_page, $columns);
            else return $query->get($columns);
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     * @throws \Exception
     */
    public function update(array $data, $id, $attribute = "id")
    {
        try {
            $model_data = array();
            foreach ($this->model->getFillable() as $var) {
                if (isset($data[($var)]))
                    $model_data[$var] = $data[$var];
            }
            $modelFounded = $this->model->where($attribute, $id)->first();
            if (!isset($modelFounded)) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND), ResponseStatus::NOT_FOUND);
            }
            //return $modelFounded->update($model_data);
            return $modelFounded->update($data);
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateOrCreate($key, $value, $data)
    {
        try {
            $object = $this->findBy($key, $value);

            if (!$object)
                return $this->create($data);
            else
                return $this->update($data, $value, $key);
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }
    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($model)
    {
        try {
            $this->model->where("id", $model->id)->firstOrFail();
            return $this->model->destroy($model->id);
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        try {
            return $this->model->findOrFail($id, $columns);
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        try {
            $attribute = Mapper::camelToSnake($attribute);
            return $this->model->where($attribute, '=', $value)->firstOrFail($columns);
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function latestRecord()
    {
        try {
            return $this->model->latest('created_at')->firstOrFail();
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();

    /**
     * @return mixed
     */
    public function makeModel()
    {
        try {
            $model = $this->app->make($this->model());
            return $this->model = $model;
        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

    public function next($id)
    {
        return $this->model->where('id','>',$id)->min('id');
    }

    public function previous($id)
    {
        return $this->model->where('id','<',$id)->max('id');
    }

    public function last()
    {
        return $this->model->latest();
    }

    public function first()
    {
        return $this->model->orderBy('created_at','asc')->firstOrFail();
    }

}
