This Laravel 4 package will collect schema details from a given table into an array. You can pass in custom schema details, which will override the generated details.

The table schema collector currently collects this information:

    table: 
        name        name of the table 
        display     name to display to an end-user (Proper Case)
        count       number of records in the table

    fields:
        field_name 
            name        name of the field
            display     name to display to the end user
            type        data type (String, Integer, etc.)
            length      length (of string fields)
            searchable  True if the field is indexed and can be searched quickly
            unique      True if the field forces data to be unique


## Installation

Install the package via Composer. Edit your `composer.json` file to require `kalani/table-schema-collector`.

    "require": {
        "laravel/framework": "4.0.*",
        "kalani/table-schema-collector": "dev-master"
    }

Next, update Composer from the terminal:

    composer update

Finally, add the service provider to the providers array in `app\config\app.php`:

    'Kalani\TableSchemaCollector\TableSchemaCollectorServiceProvider',


## Usage

Call `TableSchemaCollector::make($table, $userSchema)`:

    * $table       The name of the table for which to get the schema
    * $userSchema  Custom rules (override automatically generated rules)

The `$table` parameter is required, but `$userSchema` is optional. If supplied, it should be in this format:

    $userSchema = array(
        'fields' => array(
            'field_name' => array(
                'display' => 'This will override the standard display name',
            ),
            'another_field_name' => array(
                'rules' => 'This will insert rules for the given field',
            ),
        ),
    );


