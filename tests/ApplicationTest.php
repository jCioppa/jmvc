<?php 

    namespace Test;

    include 'vendor/autoload.php';
    include 'config/paths.php';
    include 'config/helpers.php';

    use App\App;
    use App\Http\Request\Request;
    use App\Http\Response\Response;
    
    class ApplicationTest extends \PHPUnit_Framework_TestCase {

        public function testGetRequest() {

            $app = App::app(realpath(__DIR__ . "/../"));

            $app->get('', "\App\Controllers\IndexController@test");

            $response = $app->dispatch(
                Request::make(['url' => '', 'method' => 'GET'])
            );

            $this->assertEquals($response->statusCode(), 200);
            $this->assertEquals($response->getContent(), "this is a test\n");

        }

    }
