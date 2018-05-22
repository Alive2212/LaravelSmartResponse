<?php

namespace Alive2212\LaravelSmartResponseTest\Unit;

use Alive2212\LaravelSmartMeta\Cache;
use Alive2212\LaravelSmartMeta\SmartMeta;
use Alive2212\LaravelSmartMeta\SmartMetaClass;
use Alive2212\LaravelSmartResponse\ResponseModel;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use PHPUnit\Framework\TestCase;

class DemoTest extends TestCase
{
    /**
     * @var string
     */
    private $PACKAGE_NAME = "LaravelSmartResponse";

    /**
     * @var array
     */
    private $PACKAGE_CLASSES = [
        "ResponseModel",
        "SmartResponse",
    ];

    /**
     * @var string
     */
    private $VENDOR = 'Alive2212';

    protected $app;

    public function __construct()
    {
        parent::__construct();
        $this->createApplication();
    }

    public function createApplication()
    {
        $app = new Container();
        $app->bind('app', 'Illuminate\Container\Container');

        foreach ($this->PACKAGE_CLASSES as $PACKAGE_CLASS) {
            $app->bind($PACKAGE_CLASS, $this->VENDOR.'\\'.$this->PACKAGE_NAME.'\\'.$PACKAGE_CLASS);
        }

        $app->bind('Cache', 'Illuminate\Support\Facades\Cache');

        $this->app = $app;
        Facade::setFacadeApplication($app);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // create String Helper
        foreach ($this->PACKAGE_CLASSES as $PACKAGE_CLASS) {
            ${$PACKAGE_CLASS} = $this->app->make($PACKAGE_CLASS);
        }

        $ResponseModel = new ResponseModel();

        $testArray = [
            'key1' => 'value1',
            'key2' => [
                'key21' => 'value21',
            ]
        ];

        $ResponseModel->setStatus(true);
        $this->assertTrue($ResponseModel->getStatus());

        $ResponseModel->setStatusCode(200);
        $this->assertTrue($ResponseModel->getStatusCode()==200);

        $ResponseModel->setMessage('test message');
        $this->assertTrue($ResponseModel->getMessage() == 'test message');

        $ResponseModel->setData(collect(['hello']));
        $this->assertTrue($ResponseModel->getData()->toArray() == ['hello']);

        $ResponseModel->setBeautifulData(collect(['data'=>$testArray]), ['key1' => 'key1']);

        $this->assertTrue(is_array($ResponseModel->getData()->toArray()));
    }
}
