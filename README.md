Phalcon Restful Webservice
===

Just another Phalcon RESTful webservice. It use Phalcon annotation routing for advanced and flexible routing configuration. 

Routing & Options
===

All routing must be placed in `app/controllers` and must have `Controller` suffix for class name. If your controller name is `Example` then class name is `ExampleController`. You can limit max request to specific API key or method. Limiting max request for API key can be set in Class Annotation, It will limit API key to access all method inside class. Also if you want to limit specific method you can set in Method Annotation, it will limit all API key to accessing this method. 

Class Annotation Options
---

`@RoutePrefix("your-prefix-url-here")` - `required`

`@Api(level=1-10, limits={"key": {"increment": "1 month", "limit": 1000}})` - `optional`

Method Annotation Options
---

`@Get("/")` - `required`

`@Post("/")` - `required`

`@Put("/)` - `required`

`@Delete("/")` - `required`

`@Auth("your-auth-driver")` - `optional`

`@Limit({"increment": "1 hour", "limit": 50})` - `optional`

Full Example
---

~~~php
use Phalcon\Mvc\Controller;

/**
 * @RoutePrefix("/v1/example")
 * @Api(level=1,
   limits={
    "key" : {
        "increment" : "1 day", "limit" : 1000}
    }
   )
 */
class ExampleController extends Controller
{
	/**
     * @Get("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     * @Auth("basic")
     * @Whitelist()
     */
    public function getAction()
    {
        // Your Logic here
    }

	/**
     * @Post("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     * @Auth("basic")
     */
    public function addAction()
    {
        // Your Logic here
    }
}
~~~

Auth
===
You add additional layer for security by add authentication, Auth can only enabled per class method by add annotation.

Basic
---
This is enable HTTP auth basic when user request webservice.

Response
===
Response are handled by [ellipsesynergie/api-response](https://github.com/ellipsesynergie/api-response) you can access this library via phalcon DI using `apiResponse`

Usage
---

~~~php
	/**
     * @Get("/")
     * @Limit({"increment": "1 hour", "limit": 50});
     * @Auth("basic")
     */
    public function getAction()
    {
        $this->apiResponse->withArray(
			[
				"name" => "Jowy"
			],
			[
				"My-Custom-Response-Header" => "It rocks"
			]
		);
    }
~~~

Migration
===

`php vendor/bin/phinx migrate`

Test
===

`php vendor/bin/codecept run`

To do
===

1. Improve documentation
2. **Add IP based whitelist/blacklist (implemented)**
3. Add IP based limitting
4. Add dashboard for monitoring webservice