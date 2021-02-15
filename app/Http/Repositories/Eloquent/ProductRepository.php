<?php


namespace App\Http\Repositories\Eloquent;


use App\Models\Product;

class ProductRepository extends BaseRepository implements \App\Http\Repositories\IRepositories\IProductRepository
{

    /**
     * @inheritDoc
     */
    function model()
    {
        return Product::class;
    }

    public function getTree()
    {
        return $this->model()->get()->toTree();
    }
}
