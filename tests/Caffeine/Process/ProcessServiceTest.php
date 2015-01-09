<?php

namespace Caffeine;

use Caffeine\Process\ProcessService;

class ProcessServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlingRuntime()
    {
        $mock = $this->getProcessMock();

        $mock->expects($this->once())
            ->method('run')
            ->with(
                $this->equalTo([
                    Runtime::CHANNEL => 'channel-test',
                    Runtime::DEBUG => true,
                    Runtime::CONFIG => 'config-file-test'
                ])
            )
            ->willReturn(true);

        $mock->expects($this->once())
            ->method('getPid')
            ->willReturn(42);

        $mock->expects($this->once())
            ->method('close');

        /** @var \Caffeine\Process\ProcessInterface $mock */
        $result = ProcessService::handle($mock, 'channel-test', 'config-file-test');

        $this->assertEquals(42, $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getProcessMock()
    {
        return $this->getMock('Caffeine\Process\ProcessInterface', [
            'run',
            'close',
            'getPid',
            'updateStatus'
        ], [], 'RunetimeProcess');
    }
}
