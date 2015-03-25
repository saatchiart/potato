potato
======

Simple ODM for Couchbase

Changes
=======

_0.10.0_

 * Move objects not related to ODM for Couchbase into separate source folders. Core holds Object and FilteredObject
 as these classes have a wider usage. Filter contains all of the string filtering functions we use.
 * Rename DocumentObject to FilteredObject as the main goal of the class is to apply filtering to keys and ensure
 attached objects are treated as objects versus array.
 