<?php

namespace Cerad\Bundle\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Cerad\Bundle\AuthBundle\DependencyInjection\Extension;
//  Cerad\Bundle\AuthBundle\DependencyInjection\Compiler\Pass;

class CeradAuthBundle extends Bundle
{
  public function getContainerExtension()
  {
    return $this->extension = new Extension();
  }
  public function build(ContainerBuilder $container)
  {
    parent::build($container);

  //$container->addCompilerPass(new Pass());
  }
}
