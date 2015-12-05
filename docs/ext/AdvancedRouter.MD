# OpenGears Framework 
**Documentation**

### Extensions / Advanced Router

#### Information

Open Gears supports an advanced routing features by using a special routing module instead **BaseRouter**.
This router can be enabled in **index.php** or another bootstrap file.

#### Tutorial
**index.php**
```
<?php
// Routes (can be stored in app bootstrap)
// can be used wildcards:
// :any - any char
// :num - digits only
// result expression in stored ad $1, $2 and etc by order

$routes = array(
    // 'url' => 'controller/action/param1/param2/param3'
    '/' => 'MainController/index', // Home page
    '/contacts' => 'MainController/contacts', // contacts page
    '/blog' => 'BlogController/index', // blog posts page
    '/blog/:num' => 'BlogController/viewPost/$1' // view a single post, for ex: /blog/123
  '/blog/:any/:num' => 'BlogController/$1/$2' // actions related to post, for ex:, /blog/edit/123 или /blog/dеlete/123
  '/:any' => 'MainController/anyAction' // case default
));

// Add all routes in one time
Router::addRoute($routes);


// or one bу one
Router::addRoute('/about', 'MainController/about');

// Execute router
Router::dispatch();


```
