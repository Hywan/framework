<?php
namespace {
    use Sohoa\Framework\Kit;

    require_once __DIR__ . '/../Runner.php';

    class Xyl extends Kit\Kitable
    {

    }

}

namespace Application\Controller {
    use Sohoa\Framework\Kit;

    class Foo extends Kit
    {


        public function IndexAction()
        {

            return $this->xyl;
        }
    }
}
namespace Sohoa\Framework\Tests\Unit {

    use Application\Controller\Foo;
    use Hoa\Dispatcher\Basic;
    use Hoa\Router\Http;
    use Sohoa\Framework\Kit as _Kit;

    class Kit extends \atoum\test
    {
        public function testAdd()
        {
            _Kit::add('xyl', new \Xyl());
            _Kit::add('xyls', new \Xyl());
            _Kit::add('xylsx', new \Xyl());

            $kit = new _Kit(new Http(), new Basic());

            $this->sizeof($kit->getAllKits())->isEqualto(3);

            $this->object($kit->kit('xyl'))->isInstanceOf('\Xyl');

            $kit = $kit->kit('xyl');

            $this->object($kit->getRouter())->isInstanceOf('\Hoa\Router\Http');
            $this->variable($kit->getView())->isNull();
        }

        public function testLimitless()
        {
            _Kit::add('xyl', new \Xyl());

            $kit = new _Kit(new Http(), new Basic());

            $this->sizeof($kit->getAllKits())->isEqualto(1);

            $this->object($kit->kit('xyl'))->isInstanceOf('\Xyl');

            $kit = $kit->kit('xyl');

            $this->object($kit->getRouter())->isInstanceOf('\Hoa\Router\Http');
            $this->variable($kit->getView())->isNull();


            _Kit::add('xyl', new \Xyl());
            $kit = new _Kit(new Http(), new Basic());
            $this->sizeof($kit->getAllKits())->isEqualto(1);

            $this->object($kit->kit('xyl'))->isInstanceOf('\Xyl');

            $kit = $kit->kit('xyl');
            $this->object($kit->getRouter())->isInstanceOf('\Hoa\Router\Http');
            $this->variable($kit->getView())->isNull();
        }

        public function testKitInController()
        {
            _Kit::add('xyl', new \Xyl());

            $controller = new Foo(new Http(), new Basic());
            $xyl        = $controller->IndexAction();

            $this->object($xyl)->isInstanceOf('\Xyl');
        }

		public function testKitGet() {
            $this
                ->if($miscKit = new _Kit\Kitable)
                    ->and(_Kit::add('foo', $miscKit))
                    ->and($controller = new Foo(new Http(), new Basic()))
                ->assert('Kit property is a Kitable')
                    ->object($controller->foo)->isIdenticalTo($miscKit)

                ->if($miscObj = new \stdClass())
                ->and(\Sohoa\Framework\Framework::services('bar', $miscObj))
                ->assert('Kit property is get from Registry')
                    ->object($controller->bar)->isIdenticalTo($miscObj)

                ->if(_Kit::add('bar', $miscKit))
                ->assert('Kit property is get from Kitable first')
                    ->object($controller->bar)->isIdenticalTo($miscKit)
            ;
        }




    }
}