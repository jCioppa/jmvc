let's runthrough the structure of the lumen framework

$app => Laravel\Lumen\Application($directoryBase)
$app->registerSingletons();
$app->run();

Laravel\Lumen\Application: 
    extends: 
         Illuminate\Container\Container (IOC Container)
                implements:
                        ArrayAccess   : allows to treat as array, store an array internally
                        ContainerContract : methods for IOC container access, binding, etc
    implements:
        Illuminate\Contracts\Foundation\Application : basic methods for the application
                extends:
                        Illuminate\Contracts\Container\Container
        Symphony\Component\HttpKernel\HttpKernelInterface : allows for handling a request

