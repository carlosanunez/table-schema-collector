<?php

namespace Kalani\TableSchemaCollector;

use Kalani\ValidationRuleGenerator\ValidationRuleGenerator;

/**
 * Collect schema details from a table into an array
 * Optionally override or extend schema parameters
 */
class TableSchemaCollector
{
    const UNIQUE_INDEX = 2;

    protected $db;
    protected $schemaManager;

    public function __construct($db)
    {
        $this->db = $db;
        $this->schemaManager = $db->connection()->getDoctrineSchemaManager();
    }

    /**
     * Make the array
     * 
     * @param  string $table        The name of the table for which to gather schema
     * @param  array $userSchema    User-overrides for the schema
     * @return array                Associative array of schema values
     */
    public function make($table, $userSchema=Null)
    {
        $calculatedSchema = array();

        $calculatedSchema['table'] = $this->getTableDetails($table);
        $calculatedSchema['fields'] = $this->getFields($table);

        if ($userSchema===Null) {
            return $calculatedSchema;
        }

        return array_replace_recursive($calculatedSchema, $userSchema);
    }

    /**
     * Return calculated attributes of the table that we're interested in seeing
     * 
     * @param  string $table    Name of the table
     * @return array            Associative array of data
     */
    public function getTableDetails($table)
    {
        $tableData = array(
            'name'      => $table,
            'display'   => $this->getDisplayName($table),
            'count'     => $this->db->table($table)->count(),
        );
        return $tableData;
    }

    /**
     * Get calculated attributes of each field in the given table
     * 
     * @param  string $table    Name of the table
     * 
     * @return array  Associative array of data. Includes:
     *     name         The database column name of the field
     *     display      What should be shown to the user as the field's name
     *     type         Data type for field data (eg, string, integer, etc.) 
     *     length       Maximum length (for string fields)
     *     searchable   True/False: Can this field be searched? (True for all indexed fields)
     *     unique       True/False: Does this field require unique values?
     */
    public function getFields($table)
    {
        $fields = array();

        $cols = $this->schemaManager->listTableColumns($table);
        foreach($cols as $col) {
            $colName = $col->getName();
            $index   = $this->isIndexed($table, $colName);
            $attributes = array(
                'name'      => $colName,
                'display'   => $this->getDisplayName($colName),
                'type'      => (string) $col->getType(),
                'length'    => $col->getLength(),
                'searchable'=> ($index !== False),
                'unique'    => ($index == self::UNIQUE_INDEX),
            );

            $fields[$colName] = $attributes;
        }
        return $fields;
    }

    public function getIndexes($table)
    {
        return $this->schemaManager->listTableIndexes($table);
    }

    public function isIndexed($table, $column)
    {
        $indexArray = array();
        $indexList = $this->getIndexes($table);
        foreach($indexList as $item) {
            if(strpos($item->getName(), $column)!==False && count($item->getColumns())==1) {
                if ($item->isUnique()) {
                    return self::UNIQUE_INDEX;
                }     
                return True;    
            }
        }
        return False;
    }

    public function getDisplayName($name)
    {
        $name = implode(array_map('ucfirst',explode(' ',$name)),' ');
        $name = implode(array_map('ucfirst',explode('-',$name)),' ');
        $name = implode(array_map('ucfirst',explode('_',$name)),' ');
        return $name;        
    }

}