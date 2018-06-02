# PHP JSON Marshaller

PHP JSON Marshaller is a library to marshall and unmarshall JSON strings to populated PHP classes, driven by annotations.

It exists, because developers should have more fine grain control over marshalling and unmarshalling objects:

* json_decode always returns a StdClass object. We want to be able to decode into our own classes.
* We can add expected type information about the data we are receiving/sending and validate it before using it.
* json_encode cannot be controlled on a property by property basis. This will allow us to do that.

The latest version, instructions, and examples can all be found [on my GitHub page](https://github.com/AnujRNair/php-json-marshaller).
