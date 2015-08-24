<?php namespace Pisa\Api\Gizmo\Repositories;

interface BaseRepositoryInterface
{
    //public function create(BaseModelInterface $model);
    //public function delete(BaseModelInterface $model);
    //public function update(BaseModelInterface $model);
    //public function save(BaseModelInterface $model);
    public function all($limit, $skip, $orderBy);
    public function findBy(array $criteria, $limit, $skip, $orderBy);
    public function findOneBy(array $criteria, $caseSensitive);
    public function get($id);
    public function has($id);
    public function make(array $attributes);
}
