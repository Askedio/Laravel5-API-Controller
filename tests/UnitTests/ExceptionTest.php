<?php

namespace Askedio\Tests\UnitTests;

use Askedio\Tests\UnitTestCase;

class ExceptionTest extends UnitTestCase
{
    public function testBlankException()
    {

      $this->setExpectedException(\Askedio\Laravel5ApiController\Exceptions\BadRequestException::class);
      throw new \Askedio\Laravel5ApiController\Exceptions\BadRequestException();

    }
    public function testNoTemplateException()
    {

      $this->setExpectedException(\Askedio\Laravel5ApiController\Exceptions\BadRequestException::class);
      throw new \Askedio\Laravel5ApiController\Exceptions\BadRequestException('badtemplate');


    }

}
