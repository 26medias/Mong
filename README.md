# Mong #

## What is it? ##

Mong is a PHP class that makes it super easy and flexible to query your MongoDB databases.

Mong was created when all I could find was overly complicated libraries with way too many options, too much code required and not enough flexibility.

If you know how to query Mongo on a console, then you know how to query Mongo using Mong.

## Quick start ##

First, include the lib:

```require_once('mong.php');```

Then, connect to your database:

``` $mongo = new mong('your_database_name'); ```

## Queries ##

### Find() ###
Returns a PHP array of documents.


#### Basic ####
```
$response = $mongo->find(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here
    )
));
```

#### Sort, skip and limit ####
```
$response = $mongo->find(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here
    ),
    "sort"          => array(
        "date"  => 1    // Sort by date asc
    ),
    "skip"          => 5,   // Skip the first 5 results
    "limit"         => 10   // Limit to 10 results
));
```


#### Only return part of the documents ####
```
$response = $mongo->find(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here
    ),
    "fields"        => array(   // This will only return the "_id" and "date" field
        "_id"   => true,
        "date"  => true
    )
));
```
```
$response = $mongo->find(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here
    ),
    "fields"        => array(   // This will return everything except the "_id" field
        "_id"   => false
    )
));
```

#### Paginate the response ####
```
$response = $mongo->find(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here
    ),
    "perpage"       => 10,  // Will return 10 document per query
    "page"          => 2    // Will display page #2 (documents #11 to #20)
));
```

### Insert() ###
Insert a new document to the collection.

```
$response = $mongo->insert("my_collection",array(
    // Your document, as a PHP indexed array
));
```


### Remove() ###
Delete documents from the collection.

```
$response = $mongo->remove("my_collection",array(
    // your query matching documents to remove
));
```


### Update() ###
Update documents.

#### Simple ####
```
$response = $mongo->update(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here, matching documents to update
    ),
    "data"          => array(
        // Your update data
    )
));
```
#### Options ####
```
$response = $mongo->update(array(
    "collection"    => "my_collection",
    "query"         => array(
        // Your query here, matching documents to update
    ),
    "data"          => array(
        // Your update data
    ),
    "options"       => array(
        "upsert"    => false,   // Do not create the document if the query doesn't match anything (default: true)
        "multi"     => true     // Update more than one document if required (default: only the 1st)
    )
));
```



### Count() ###
Count the number of documents.

```
$count = $mongo->count("my_collection",array(
    // your query matching documents to count
));
```




### distinct() ###
Return the distinct values from documents.

```
// Returns an array of distinct usernames:
$count = $mongo->distinct("my_collection","username",array(
    // your query matching documents
));
```


