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
            $app->registerProviders();

            $app->get('hey/there', "\App\Controllers\IndexController@test");

            $response = $app->dispatch(
                Request::make(['url' => 'hey/there', 'method' => 'GET'])
            );

            $this->assertEquals($response->statusCode(), 200);
            $this->assertEquals($response->getContent(), "this is a test\n");

        }

        public function testPostRequest() {
    
            $app = App::app(realpath(__DIR__ . "/../"));
            $app->registerProviders();

            $app->post("testpost", function() {});
            $response = $app->dispatch(
                Request::make(['url' => 'testpost', 'method' => 'POST'])
            );

            $this->assertEquals($response->statusCode(), 200);

        }

        public function testGetRequestWithClosure() {

            $app = App::app(realpath(__DIR__ . "/../"));
            $app->registerProviders();
            $app->get('hey/there', function() {return "hello world";});
            $response = $app->dispatch(
                Request::make(['url' => 'hey/there', 'method' => 'GET'])
            );
            $this->assertEquals($response->statusCode(), 200);
            $this->assertEquals($response->getContent(), "hello world");

        }

        public function testGetRequestWithoutResponse() {
            $app = App::app(realpath(__DIR__ . "/../"));
            $app->registerProviders();
            $app->post('hey/there', function() {return "hello world!";});
            $_GET['url'] = "hey/there";
            $_SERVER['REQUEST_METHOD'] = "POST";
            $response = $app->dispatch();
            $this->assertEquals($response->statusCode(), 200);
            $this->assertEquals($response->getContent(), "hello world!");
        }




    }
