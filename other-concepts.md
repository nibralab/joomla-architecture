---
layout: default
title: Other Concepts
category: Appendix
author: Niels Braczek
author_email: nbraczek@bsds.de
---

## Other Concepts

This list contains links to concepts and code, that might be interesting for Joomla! 4.
They need some more investigation about how they fit into Joomla. 

### HTTP Server / Client

  * [kemist80/http](https://github.com/kemist80/http)  
    PSR-7 compliant http library 

  * [mekras/psr7-client](https://github.com/mekras/psr7-client)  
    PSR7 compatible HTTP client library.

### Middleware

Middleware seems to be a cool concept.
In conjunction with PSR-7, the middleware approach will allow us to use a growing bunch of functionality.
On the other hand, it will make Joomla! interesting as a library for other projects.

  * [zendframework/zend-stratigility](https://github.com/zendframework/zend-stratigility)  
    Stratigility is a port of Sencha Connect to PHP.
    It allows you to build applications out of middleware.
    
  * [muriloacs/Middleware](https://github.com/muriloacs/Middleware)  
    Creates middleware layer on Zend Framework 2.
    Useful when it's necessary to make some work between route and controller dispatch phases. 

### Streams

On first sight, streams seem to be complicated. In reality, they are easy to handle and extremely powerful.

  * [guzzle/psr7](https://github.com/guzzle/psr7)  
    PSR-7 HTTP message library (currently missing ServerRequestInterface and UploadedFileInterface)
    Has a lot of interesting StreamInterface implementations

### PSR7

See also **Streams** and **Middleware** (Request/Response).

  * [libraries.io/keywords/psr7](https://libraries.io/keywords/psr7)  
    Projects tagged with "psr7"
