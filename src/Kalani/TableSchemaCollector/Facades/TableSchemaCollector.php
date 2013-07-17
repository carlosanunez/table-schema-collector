<?php 

namespace Kalani\TableSchemaCollector\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class TableSchemaCollector extends Facade 
{
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'table-schema-collector'; }
 
}

