<?php

namespace RefinedDigital\InteractiveMap\Module\Http\Controllers;

use RefinedDigital\CMS\Modules\Core\Http\Controllers\CoreController;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\InteractiveMap\Module\Http\Requests\InteractiveMapRequest;

class InteractiveMapController extends CoreController
{
    protected $model = 'RefinedDigital\InteractiveMap\Module\Models\Map';
    protected $prefix = 'interactive-map::';
    protected $route = 'interactive-map';
    protected $heading = 'Map';
    protected $button = 'a Marker';

    public function __construct(CoreRepository $coreRepository)
    {
        parent::__construct($coreRepository);
    }

    public function setup() {

        $table = new \stdClass();
        $table->fields = [
            // (object) [ 'name' => '#', 'field' => 'id', 'sortable' => true, 'classes' => ['data-table__cell--id']],
            (object) [ 'name' => 'Name', 'field' => 'name', 'sortable' => true],
            (object) [ 'name' => 'Category', 'field' => 'map_category', 'sortable' => false, 'type' => 'map-category'],
            (object) [ 'name' => 'Active', 'field' => 'active', 'type'=> 'select', 'options' => [1 => 'Yes', 0 => 'No'], 'sortable' => true, 'classes' => ['data-table__cell--active']],
        ];
        $table->routes = (object) [
            'edit'      => 'refined.interactive-map.edit',
            'destroy'   => 'refined.interactive-map.destroy'
        ];
        $table->sortable = true;

        $this->table = $table;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($item)
    {
        // get the instance
        $data = $this->model::findOrFail($item);
        return parent::edit($data);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(InteractiveMapRequest $request)
    {
        return parent::storeRecord($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InteractiveMapRequest $request, $id)
    {
        return parent::updateRecord($request, $id);
    }
}
