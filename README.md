# PhpMemcachedSession
Provides a useful session handler using memcached for php

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0a0cea977f37463db99d0e2380155511)](https://www.codacy.com/app/Idrinth/PhpMemcachedSession?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Yet-Another-Web-Stack/Session-PHP-Memcached&amp;utm_campaign=Badge_Grade)
[![Code Climate](https://codeclimate.com/github/Yet-Another-Web-Stack/Session-PHP-Memcached/badges/gpa.svg)](https://codeclimate.com/github/Yet-Another-Web-Stack/Session-PHP-Memcached)

This project requires either Memcached to work as a storage medium. memcache can be made to work, but will unlikely be the system of choice for most users.

## How to use

Include the Initializer before starting a session and call run on it. It requires a callable , that can be used to change DI-Mappings or configuration.

## Ini-Settings used

The required and proposed defaults can be set with the Configuration-Class.

### session
* serialize_handler: is required to be set to php_serialize
* name: should be set to something less obviously php
* use_cookies: should be set to true
* use_only_cookies: should be set to true

### yetanotherwebstack_session
* memcache_server: defaults to localhost
* memcache_port: defaults to 11211
* sid_pepper: defaults to "this is not quite secret" and should be changed
* memcache_user: no default, only use if the memcache is password protected
* memcache_password: no default, only use if the memcache is password protected
* serializer: a callable to serialize the session
* unserializer: a callable to unserialize the session
