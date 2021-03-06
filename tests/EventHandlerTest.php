<?php
class EventHandlerTest extends FlattenTests
{
	public function testReturnsNothingIfNoCache()
	{
		$empty = $this->events->onApplicationBoot();

		$this->assertNull($empty);
	}

	public function testCanStoreCache()
	{
		$response = Mockery::mock('Symfony\Component\HttpFoundation\Response');
		$response->shouldReceive('isRedirection')->once()->andReturn(false);
		$response->shouldReceive('getContent')->once()->andReturn('foobar');

		// Pass the response
		$this->events->onApplicationDone($response);

		// Assert response
		$response = $this->flatten->getResponse();
		$this->assertContains('foobar', $response->getContent());
	}

	public function testCancelIfRedirect()
	{
		$response = Mockery::mock('Symfony\Component\HttpFoundation\Response');
		$response->shouldReceive('isRedirection')->once()->andReturn(true);
		$response->shouldReceive('getContent')->once()->andReturn('foobar');

		$this->assertFalse($this->events->onApplicationDone($response));
	}
}
