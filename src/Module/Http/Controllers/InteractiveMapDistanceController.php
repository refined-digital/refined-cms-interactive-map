<?php

namespace RefinedDigital\InteractiveMap\Module\Http\Controllers;

use RefinedDigital\CMS\Modules\Core\Http\Controllers\CoreController;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\InteractiveMap\Module\Http\Requests\InteractiveMapDistanceRequest;

class InteractiveMapDistanceController extends CoreController
{
    protected $model = 'RefinedDigital\InteractiveMap\Module\Models\MapDistance';
    protected $prefix = 'interactive-map::';
    protected $route = 'interactive-map-distance';
    protected $heading = 'Distance from';
    protected $button = 'an Item';

    public function __construct(CoreRepository $coreRepository)
    {
        parent::__construct($coreRepository);
    }

    public function setup() {

        $table = new \stdClass();
        $table->fields = [
            (object) [ 'name' => '#', 'field' => 'id', 'sortable' => true, 'classes' => ['data-table__cell--id']],
            (object) [ 'name' => 'Name', 'field' => 'name', 'sortable' => true],
            (object) [ 'name' => 'Distance', 'field' => 'distance', 'sortable' => true],
            (object) [ 'name' => 'Active', 'field' => 'active', 'type'=> 'select', 'options' => [1 => 'Yes', 0 => 'No'], 'sortable' => true, 'classes' => ['data-table__cell--active']],
        ];
        $table->routes = (object) [
            'edit'      => 'refined.interactive-map-distance.edit',
            'destroy'   => 'refined.interactive-map-distance.destroy'
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
    public function store(InteractiveMapDistanceRequest $request)
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
    public function update(InteractiveMapDistanceRequest $request, $id)
    {
        return parent::updateRecord($request, $id);
    }
}
