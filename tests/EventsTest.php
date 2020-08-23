<?php
namespace GoetasWebservices\XML\WSDLReader\Tests;

use GoetasWebservices\XML\WSDLReader\DefinitionsReader;
use Jmikola\WildcardEventDispatcher\WildcardEventDispatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Event;

class EventsTest extends TestCase
{

    /**
     *
     * @var \GoetasWebservices\XML\WSDLReader\DefinitionsReader
     */
    protected $reader;

    /**
     *
     * @var \Jmikola\WildcardEventDispatcher\WildcardEventDispatcher
     */
    protected $dispatcher;

    public function setUp(): void
    {
        $this->dispatcher = new WildcardEventDispatcher();
        $this->reader = new DefinitionsReader(null, $this->dispatcher);
    }

    public function testReadFile()
    {
        $events = array();
        $this->dispatcher->addListener("#", function (Event $e, $name) use (&$events) {
            $events[] = [$e, $name];
        });
        $this->reader->readFile(__DIR__ . '/resources/base-wsdl-events.wsdl');

        $expected = [];
        $expected[] = ['definitions_start', 'GoetasWebservices\XML\WSDLReader\Events\DefinitionsEvent'];

        $expected[] = ['service', 'GoetasWebservices\XML\WSDLReader\Events\ServiceEvent'];
        $expected[] = ['service.port', 'GoetasWebservices\XML\WSDLReader\Events\Service\PortEvent'];

        $expected[] = ['message', 'GoetasWebservices\XML\WSDLReader\Events\MessageEvent'];
        $expected[] = ['message.part', 'GoetasWebservices\XML\WSDLReader\Events\Message\PartEvent'];
        $expected[] = ['message', 'GoetasWebservices\XML\WSDLReader\Events\MessageEvent'];
        $expected[] = ['message.part', 'GoetasWebservices\XML\WSDLReader\Events\Message\PartEvent'];

        $expected[] = ['portType', 'GoetasWebservices\XML\WSDLReader\Events\PortTypeEvent'];
        $expected[] = ['portType.operation', 'GoetasWebservices\XML\WSDLReader\Events\PortType\OperationEvent'];
        $expected[] = ['portType.operation.param', 'GoetasWebservices\XML\WSDLReader\Events\PortType\ParamEvent'];
        $expected[] = ['portType.operation.param', 'GoetasWebservices\XML\WSDLReader\Events\PortType\ParamEvent'];

        $expected[] = ['portType.operation', 'GoetasWebservices\XML\WSDLReader\Events\PortType\OperationEvent'];
        $expected[] = ['portType.operation.param', 'GoetasWebservices\XML\WSDLReader\Events\PortType\ParamEvent'];
        $expected[] = ['portType.operation.param', 'GoetasWebservices\XML\WSDLReader\Events\PortType\ParamEvent'];
        $expected[] = ['portType.operation.fault', 'GoetasWebservices\XML\WSDLReader\Events\PortType\FaultEvent'];

        $expected[] = ['binding', 'GoetasWebservices\XML\WSDLReader\Events\BindingEvent'];

        $expected[] = ['binding.operation', 'GoetasWebservices\XML\WSDLReader\Events\Binding\OperationEvent'];
        $expected[] = ['binding.operation.message', 'GoetasWebservices\XML\WSDLReader\Events\Binding\MessageEvent'];
        $expected[] = ['binding.operation.message', 'GoetasWebservices\XML\WSDLReader\Events\Binding\MessageEvent'];

        $expected[] = ['binding.operation', 'GoetasWebservices\XML\WSDLReader\Events\Binding\OperationEvent'];
        $expected[] = ['binding.operation.message', 'GoetasWebservices\XML\WSDLReader\Events\Binding\MessageEvent'];
        $expected[] = ['binding.operation.message', 'GoetasWebservices\XML\WSDLReader\Events\Binding\MessageEvent'];

        $expected[] = ['binding.operation.fault', 'GoetasWebservices\XML\WSDLReader\Events\Binding\FaultEvent'];
        $expected[] = ['binding.operation.fault', 'GoetasWebservices\XML\WSDLReader\Events\Binding\FaultEvent'];

        $expected[] = ['definitions_end', 'GoetasWebservices\XML\WSDLReader\Events\DefinitionsEvent'];

        $this->assertCount(count($expected), $events);

        foreach ($expected as $index => $expectedData) {
            list($event, $name) = $events[$index];
            $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $event);
            $this->assertInstanceOf('GoetasWebservices\XML\WSDLReader\Events\WsdlEvent', $event);
            $this->assertInstanceOf($expectedData[1], $event, "Event name '$expectedData[0]'");
            $this->assertEquals($expectedData[0], $name);
        }
    }

}
