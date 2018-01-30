<?php

use Mockery as m;
use ResourceWatcher\Event;

class EventTest extends PHPUnit_Framework_TestCase {


	public function tearDown()
	{
		m::close();
	}


	public function testCanGetEventCode()
	{
		$resource = m::mock('ResourceWatcher\Resource\ResourceInterface');
		$event = new Event($resource, Event::RESOURCE_CREATED);
		$this->assertEquals(Event::RESOURCE_CREATED, $event->getCode());
	}


	public function testCanGetResourceFromEvent()
	{
		$resource = m::mock('ResourceWatcher\Resource\ResourceInterface');
		$event = new Event($resource, Event::RESOURCE_CREATED);
		$this->assertInstanceOf('ResourceWatcher\Resource\ResourceInterface', $event->getResource());
	}


}